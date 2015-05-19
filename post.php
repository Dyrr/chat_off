<?php
	session_start();
	require('config.inc.php');	
	//Controllo che il pg sia loggato
	if(isset($login_control)) {
		function replacingNode($currentNode, $node) {
		   $node->parentNode->replaceChild($currentNode, $node);   
		}; 	

		$op = $_POST['op'];
		
		switch(strtoupper($op)) {
		//MODERAZIONE MESSAGGIO			
			case 'HIDE' :
				
				if($moderazione == 1)
				{
				
					$post_id = (isset($_POST['post_id'])) ? intval($_POST['post_id']) : -1;
					$xml = new DomDocument();
					$xml->preserveWhiteSpace = false;
					$xml->formatOutput = true; 
					$xml->load($file);

					if ($post_id >= 0 && $xml->getElementsByTagName('visible')->item($post_id)->nodeValue == 'yes' && $moderazione == 1)
					{
						
						$element = $xml->getElementsByTagName('visible')->item($post_id);
						$code = $xml->createElement('visible');
						$text = $xml->createCDATASection('no');
						$text = $code->appendChild($text);				
						replacingNode($code,$element);
						$xml->save($file);
					
					}
				
				}
			
			break;
		//INVIO MESSAGGIO	
			case 'INVIA' :
				
				if(isset($_POST['usermsg']) && $_POST['usermsg'] != '')
				{

					//Se il file del log giornaliero non esiste ancora crea la base del file del log giornaliero
					if(!file_exists($file))
					{
	
						$xml = new DOMDocument('1.0', 'UTF-8');	
						$xml->preserveWhiteSpace = false;
						$xml->formatOutput = true; 
						$node = $xml->createElement("chat");
						$newnode = $xml->appendChild($node);		
						$xml->save($file);
					
					}

					$xml = new DomDocument();
					$xml->preserveWhiteSpace = false;
					$xml->formatOutput = true; 
					$xml->load($file);
					
					$xpath = new DOMXPath($xml);    
					$results = $xpath->query('/chat');   
					$baz_node_of_xml = $results->item(0);
					
					$id_no =  $xml->getElementsByTagName('item')->length;

					$item = $xml->createElement('item');
					$autore = $xml->createElement('autore');
					$autore = $item->appendChild($autore);
					$text = $xml->createCDATASection($login_control);
					$text = $autore->appendChild($text);

					$id = $xml->createElement('id');
					$id = $item->appendChild($id);
					$text = $xml->createCDATASection(''.$id_no);
					$text = $id->appendChild($text);		
					
					$data = $xml->createElement('data');
					$data = $item->appendChild($data);
					$text = $xml->createCDATASection(date("d/m/Y H:i:s"));
					$text = $data->appendChild($text);

					$visible = $xml->createElement('visible');
					$visible = $item->appendChild($visible);
					$text = $xml->createCDATASection('yes');
					$text = $visible->appendChild($text);		
					
					$tag = $xml->createElement('tag');
					$tag = $item->appendChild($tag);
					$text = $xml->createCDATASection(stripslashes(htmlentities($_POST['usertag'])));
					$text = $tag->appendChild($text);					
					
					$testo = $xml->createElement('testo');
					$testo = $item->appendChild($testo);
					$text = $xml->createCDATASection(stripslashes(htmlentities($_POST['usermsg'])));
					$text = $testo->appendChild($text);

					$baz_node_of_xml->appendChild($item);
					$xml->save($file);

				}			
			break;
			
			default :
			break;
		}	
	}
?>