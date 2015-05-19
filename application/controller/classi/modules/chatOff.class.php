<?php
	
	namespace application\controller\classi\modules;
	
	/** 
	 *  @class		chatOff
	 *  
	 *  @author		Davide 'Dyrr' Grandi
	 *  
	 *  @version	1.0
	 *  @date		26/06/2013
	 *  
	 *  @brief		Classe per la gestione delle funzioni della chat off
	 *  @details	La classe crea un wrapper per la connesisone al database tramite PDO e l'interrogazione del database stesso.
	 *  			Si è scelto di utilizzare un wrapper piuttosto che direttamente pdo, per semplificare l'interrogaizone al database e rendere minime
	 *  			le modifiche in caso di cambio del motore di connessione al database
	 *  
	 *  @details	Changelog:
	 *  			- Ver. 1.0 (26/06/2013)
	 *  				- Prima versione della classe 
	 */		
	class chatOff {	

		
		/**
		 *  @fn 		__construct
		 *  
		 *  @brief 		Costruttore della classe
		 *  @details 	Imposta i parametri di default della classe
		 */		
		function __construct()
		{
			
			$this->options = new \stdClass();
			$this->options->path = 'log/';													//Directory in cui salvare i log
			$this->options->tipoLog = 'giornaliero';										// tipo di log: giornaliero/orario
			$this->options->os = 'debug';													//open source abbinato alla chat off
			date_default_timezone_set('Europe/Rome');
		
		}
		
		/**
		 *  @fn 		selectOS
		 *  
		 *  @brief 		Metodo per l'impostazione della chat in base all'OS utilizzato
		 *  @details 	Il metodo permette di impostare da quali variabili di sessione prendere:
		 *  			- Nome utente
		 *  			- Permessi
		 *  			In base all'open source utilizzato senza dover impostare altro.<br />
		 *  			I principali open source supportati sono:
		 *  			- GDRCD 3.X;
		 *  			- GDRCD 5.X;
		 *  			- ACCENT 2.X;
		 *  			- Città Virtuale GPL (TRIAMO)
		 *  			- Tana del ladro (TDL) sia nel caso di utente loggato che di pg loggato
		 *  
		 *  @param [in] $os <b>(string)</b> La sigla dell'os utilizzato
		 *  
		 *  @return [out] <b>(array)</b> Il nome dell'utente per il controllo del login e i suoi permessi di moderazione
		 */	
		function selectOS($os=null)
		{	
			
			//controllo per poter settare l'os sia direttamente da funzione che da attributo
			$os = (empty($os)) ? $this->options->os : $os;
			
		//SELEZIONE DELL'OS IN BASE ALLE IMPOSTAZIONI
			switch(strtoupper($os))
			{
				case 'GDRCD3.X' :
					
					$login_control = $_SESSION['Login'];
					$moderazione = (isset($_SESSION['Admin_S']) && $_SESSION['Admin_S'] == 1) ? 1 : 0;
				
				break;
				
				case 'GDRCD5.X' :
					
					$login_control = $_SESSION['login'];
					//Se l'utente ha permessi di 3 = MODERATOR o superiori
					$moderazione = (isset($_SESSION['permessi']) && $_SESSION['permessi'] >= 3) ? 1 : 0;		
				
				break;	
				
				case 'ACCENT2.X' :
					
					$login_control = $_SESSION['unm'];
					$moderazione = (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) ? 1 : 0;		
				
				break;
				
				case 'TRAIMO' :
					
					$login_control = $_SESSION['USERNAME'];
					//Da verificare la variabile di sessione
					$moderazione = (isset($_SESSION['FUNZIONE_TIPO']) && $_SESSION['FUNZIONE_TIPO'] == 1) ? 1 : 0;		
				
				break;		
				//Tana del ladro loggati solo con account
				case 'TDL_USER' :
					
					$login_control = $_SESSION['username'];
					//non è disponibile una variabile di sessione che identifichi admin oo dm, bisogna creare una procedura di controllo tramite query e database
					$moderazione = 0;		
				
				break;	
				
				//Tana del ladro loggati con il pg
				case 'TDL_PG' :
					
					$login_control = $_SESSION['utente']['nomePg'];
					//non è disponibile una variabile di sessione che identifichi admin oo dm, bisogna creare una procedura di controllo tramite query e database
					$moderazione = 0;		
				
				break;		
				
				case 'CUSTOM' :
					
					//$login_control = inserire qui la variabile di sessione che identifica il nome del pg;
					//$moderazione = inserire qui la condizione che se vera da i permessi di moderazione ? 1 : 0;	
				
				break;					
				
				case 'DEBUG' : //per il test della chat non associato ad un os
					
					$login_control = 'Super';
					$moderazione = 1;
				
				break;		
				
				default:
				
				break;
			
			}
			
			
			$dati = array (
				'login_control' => $login_control,
				'moderazione' => $moderazione
			);
			
			return $dati;
		
		}
		
		/**
		 *  @fn tipoLog
		 *  
		 *  @brief 		Metodo per la generazione del nome del file del log di chat
		 *  @details 	Il metodo genera automaticamente il nome per il file del log di chat i log in base alle esigenze secondo questi criteri:
		 *  			- GIORNALIERO
		 *  			- ORARIO		 
		 *  
		 *  @param [in] $tipoLog <b>(string)</b> Tipo di log richiesto

		 *  
		 *  @return [out] <b>(string)</b> nome del log generato
		 */
		function tipoLog($tipoLog=null)
		{

			switch(strtoupper($tipoLog))
			{
				
				case 'ORARIO' :	
					
					$file = 'log_'.date('Y_m_d_H').'.xml';
				break;
				
				case 'GIORNALIERO' :
				default :
					
					$file = 'log_'.date('Y_m_d').'.xml';			
				
				break;
			
			}
			
			return $this->options->path.$file;
		
		}
	
	}
?>