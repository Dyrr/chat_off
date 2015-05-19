/**
 *  @brief Funzione per l'interfaccia grafica del bbcode
 *  
 *  @param [in] obj il campo text o textarea da monitorare
 *  @param [in] str_pre il tag di apertura
 *  @param [in] str_post il tag di chiusura
 *  @param [in] arg eventuali parametri
 
 *  @details La funzione gestisce l'inserimento dei tag del bbcode sul testo evidenziato nel campo da monitorare
 */
function bbcode(obj, str_pre, str_post, arg) {
	var arg = typeof arg !== 'undefined' ? '='+arg : '';  
	var str_pre = typeof str_pre !== 'undefined' ? '['+str_pre+arg+']' : '';	
	var str_post = typeof str_post !== 'undefined' ? '[/'+str_post+']' : '';  
	var obj = document.getElementById(obj);
	if (document.selection) { // IE
		obj.focus();
		sel = document.selection.createRange();
		sel.text = str_pre + sel.text + str_post;
	} else if (obj.selectionStart || obj.selectionStart == '0') { // altri browser
		var pos_1 = obj.selectionStart;
		var pos_2 = obj.selectionEnd;
		obj.value = obj.value.substring(0, pos_1)
					+ str_pre
					+ obj.value.substring(pos_1, pos_2)
					+ str_post
					+ obj.value.substring(pos_2, obj.value.length);
	} else { // Fallback
		obj.value += str_pre + str_post;
	}
}