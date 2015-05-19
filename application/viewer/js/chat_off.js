$(document).ready(function(){			
	//Recupera il template per i messaggi della chat
	var chat_template = $('#chat_template').html();
	//Rimuove il template della pagina web per maggiore ordine
	$('#chat_template').html('');
	var full_refresh = 0;	
	var real_lines = 0;	


	$("#submitmsg").click(function(){	
		//recupero del contenuto dell'input di testo della chat
		var post_data = $(this).parent().serialize();
		//invio vi ajax dei dati recuperati dal form alla pagina di inserimento della chat
		$.post("post.php",post_data);				
		//svuotamento del campo dell'input del testo della chat
		$("#usermsg").val('');
		//refresh forzato della chat per simulare un aggiornamento in tempo reale
		setTimeout(function(){$.XMLChat();},250);
		return false;
	});

//FUNZIONE PER NASCONDERE UN MESSAGGIO DELLA CHAT	
	$(document).on("click", ".hide", function(event){  
		event.preventDefault();
		var post_data = $(this).parent().serialize();
		$.post("post.php", post_data);
	});
	
//FUNZIONE DI REFRESH DELLA CHAT 
	$.XMLChat = function() {
		//Recupera l'altezza del div contenente la chat prima del nuovo refresh
		var oldscrollHeight = $('#chatbox')[0].scrollHeight - 20;
		//Funzione di recupero dei dati della chat tramite ajax con l'esecuzione delel funzioni di visualizzazine chat al caricamento con successo del file.
		$.ajax({
			url: chatOptions.fileName,								//Indirizzo del file giornaliero del log della chat
			dataType: "xml",										//Tipo di file da recuperare
			cache: false,											//Recupero del file dalla cache
			//Al recupero con successo del file
			success: function(xml){		
				var a = 0;
				//Se il log ha più post di quelli impostati come massimo numero di post visibili al primo ingresso chat ed è il primo ingresso in chat
				if($(xml).find('item').length >= chatOptions.maxLines && lines == 0) {
					//imposta il numero di post da cui partire a visualizzare
					lines = $(xml).find('item').length - chatOptions.maxLines;
				}
				//Funzione che cicla i post inseriti nella chat
				$(xml).find('item').each(function() {
					//Nel caso ci siano post nuovi o nel caso di più post oltre il numero massimo visualizzabile in chat
					if (a >= lines) {
						var visible_post = $(this).find('visible').text();
						if((visible_post == 'yes') || (visible_post == 'no' && chatOptions.moderazione == 1))
						{
							//Recupera i dati di autore, data, e testo del post
							var testo = $(this).find('testo').text();
							if(visible_post == "no")
							{
								testo = "<s>"+testo+"</s>";
							}
							//esegue il controllo e sostituzione del bbcode
							for (var b = 0; b < chatOptions.bbcodeIn.length; b++) {
								testo=testo.replace(chatOptions.bbcodeIn[b],chatOptions.bbcodeOut[b]);
							};						
							//assegna una classe diversa per i post pari e dispari in maniera da poter fare una visualizzazione a sfondo alternato
							((real_lines % 2) == 0) ? color="msgln_light" : color="msgln_dark";						
							var dati = {
								data : $(this).find('data').text(),
								autore : $(this).find('autore').text(),
								tag : $(this).find('tag').text(),
								testo : testo,
								color : color,
								id : $(this).find('id').text(),
							}
							//recupera lo schema del template
							if (chatOptions.moderazione == 0) {
								$('#moderazione').html('');
							}
							var output = chat_template;
							//effettua le sostituzioni all'interno del template del messaggio
							for (prop in dati)	{
								var re = new RegExp('\\[\\{'+prop+'\\}\\]','g');
								output = output.replace(re,dati[prop]);
							}
							//appende il messaggio
							$("#chatbox").append(output);
							real_lines = ++real_lines;
						}
					}
					//aggiorna il contatore della riga del messaggio
					a = ++a;					
					//controlla se nella lista caricata vi sono messaggi eliminati

					if($(this).find('visible').text() == 'no' && chatOptions.moderazione != 1) {
						var suffix = $(this).find('id').text();
						//se vi sono messaggi eliminati visualizzati
						if($('#msg_'+suffix).length != 0) {
							//imposta un full refresh della chat
							full_refresh = 1;
						}
					}

				});
				//imposta il numero dell'ultimo messaggio visualizzato in maniera da poter appendere solo i messaggi nuovi al prossimo ciclo
				lines = a;
				//recupera l'altezza del div della chat dopo il nuovo refresh
				var newscrollHeight = $('#chatbox')[0].scrollHeight - 20;
				//se la vecchia altezza e quella nuova sono diverse
				if (newscrollHeight != 	oldscrollHeight) {
					//esegue lo scrol al fondo della chat
					$('#chatbox').animate({   
							 scrollTop: $('#chatbox')[0].scrollHeight}  
					 );  
				}		
				//in caso di full reresh della chat
				if(full_refresh == 1) {
					//imposta a no il refresh completo della chat
					full_refresh = 0;
					//resetta le varie variabili globali
					a=0;
					lines = 0;
					real_lines = 0;
					oldscrollHeight = 0;
					//svuota la chat
					$("#chatbox").html('');
					//richiede un refresh nuovo
					$.XMLChat()
				}			
			
			}
		});
	}
	//esegue il refresh della chat al primoingresso della chat
	$.XMLChat()
	//imposta l'aggiornamento ciclico della chat
	setInterval (function(){$.XMLChat()}, chatOptions.refreshInterval);	//Reload file every 2.5 seconds
});