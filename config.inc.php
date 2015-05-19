<?php
//CARICAMENTO E INIZIALIZZAZIONE DELLA CLASSE
	require('application/controller/classi/modules/chatOff.class.php');
	$chatOff = new application\controller\classi\modules\chatOff();
	
//IMPOSTAZIONI DELLA CHAT
	//selezione dell'os utilizzato e recupero dei dati dell'utente
	//$os = $chatOff->selectOS('GDRCD5.X');
	$os = $chatOff->selectOS('debug');
	//selezione del path di salvataggio del log
	$chatOff->options->path = 'log/';
	//selezione del tipo di log desiderato
	$file = $chatOff->tipoLog('giornaliero');
	//numero massimo di messaggi visibili inizialmente
	$max_lines = 50;
	//intervallo di refresh della chat in millisecondi
	$refresh_time = 10000;
	
//RECUPERO DELLE VARIABILI 	
	$login_control = $os['login_control'];
	$moderazione = $os['moderazione'];
?>