<?php 
	//Inizializza la sessione
	session_start(); 
	require('config.inc.php');
	
	//Controlla che il pg sia loggato (Opzione per il GDRCD 3.x per gli altri OS consultare il codice dell'OS per recuperare la variabile di sessione del PG
	if(empty($login_control)) {
		
		exit('Non sei loggato al sito');
	
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Chat - Customer Module</title>
		<meta charset="UTF-8" />
		<meta name="description" content="Engine di gioco DLight" />
		<meta name="keywords" content="gdr, rpg, fantasy" />
		<meta name="author" content="Davide 'Dyrr' Grandi" />
		<meta name="robots" content="index,follow" />	
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>		
		<script type="text/javascript">
			/*
			** utilizzare JQuery 1.7 o superiore in quanto è stata usata la funzione .on() invece di .delegate() o .live() nel javascript per adeguarsi alla nuova versione della libreria.
			** Per utilizzare versioni precedenti della libreria consultare la pagina http://api.jquery.com/live/ per le sintassi per le varie versioni.
			*/
			var lines = 0;
			//Oggetto con le opzioni per lo script della chat
			var chatOptions = {
				maxLines: <?php echo htmlentities($max_lines); ?>,															//Numero massimo di post precedenti visualizzati al momento del primo accesso alla chat
				refreshInterval: "<?php echo htmlentities($refresh_time); ?>",													//Intervallo di refresh
				fileName: "<?php echo htmlentities($file); ?>",							//nome del file del log
				moderazione: "<?php echo htmlentities($moderazione) ?>",				//il controllo dei permessi per cancellar ei post avviene anche comunque lato server quindi è inutile modificare a mano questo valore.
				//Pattern di ricerca e di replace del mini bbcode della chat
				bbcodeIn:[
					new RegExp('\\[u\\](.*?)\\[\\/u\\]','g'),
					new RegExp('\\[b\\](.*?)\\[\\/b\\]','g'),
					new RegExp('\\[i\\](.*?)\\[\\/i\\]','g'),
					new RegExp('\\[color=([a-zA-Z0-9\#]*)\\](.*?)\\[\\/color\\]','g'),
				],
				bbcodeOut:[
					"<u>\$1</u>",														//sottolineato
					"<b>\$1</b>",														//grassetto
					"<i>\$1</i>",														//corsivo
					'<span style="color:\$1;">\$2</span>'								//colore testo
				],				
			}
		</script>		
		<script src="application/viewer/js/bbcode_ui.js"></script>			
		<script src="application/viewer/js/chat_off.js"></script>			
		<link href='http://fonts.googleapis.com/css?family=Iceland' rel='stylesheet' type='text/css'>	
		<link type="text/css" rel="stylesheet" href="application/viewer/css/chatoff.css" />	
	</head>
	<body>
		<div id="wrapper">
			<div id="menu">
				<div id="menu_inner">	
					<div class="d_table">	
						<div class="d_cell">	
							<div id="user_name">Benvenuto <b><?php echo $login_control; ?></b></div>
						</div>
					</div>
				</div>
			</div>	
			<div id="chatbox_wrapper">
				<div id="chatbox"></div>
			</div>
			<div id="input">
				<div id="input_inner">
					<div class="d_table">	
						<div class="d_cell">					
							<div class="bbcode_button" title="b" onclick="bbcode('usermsg',this.title,this.title);return false;" style="background:white url('application/viewer/img/bbcode.png') top left no-repeat;"></div>
							<div class="bbcode_button" title="u" onclick="bbcode('usermsg',this.title,this.title);return false;" style="background:white url('application/viewer/img/bbcode.png') -40px top no-repeat;"></div>
							<div class="bbcode_button" title="i" onclick="bbcode('usermsg',this.title,this.title);return false;" style="background:white url('application/viewer/img/bbcode.png') -20px top no-repeat;"></div>
							<div class="bbcode_button" title="color" onclick="bbcode('usermsg',this.title,this.title);return false;" style="background:white url('application/viewer/img/bbcode.png') -60px top no-repeat;"></div>
							<form name="message" action="chatoff.php" method="post">
								<input name="usertag" type="text" class="input_tag" />								
								<input name="usermsg" type="text" class="input_text" id="usermsg" />
								<input name="op" type="hidden" id="op" value="invia" />
								<button name="submitmsg" id="submitmsg" class="cupid-blue">Invia</button>
							</form>
						</div>
					</div>
					<!--Inizio div nascosto che funge da template per i messaggi della chat-->
					<div id="chat_template" style="display:none;">
						<div class="[{color}]" id="msg_[{id}]">
						<div style="display:inline;" id="moderazione">
							<form style="display:inline">
								<input name="op" type="hidden" value="hide" />
								<input name="post_id" type="hidden" value="[{id}]" />
								<input type="image" class="hide" src="application/viewer/img/cross.png" />
							</form>
						</div>
						[{data}] <b>[{autore}] :</b>[[{tag}]] [{testo}]</div>
					</div>
					<!--Fine div nascosto che funge da template per i messaggi della chat-->				
				</div>
			</div>
		</div>
	</body>
</html>