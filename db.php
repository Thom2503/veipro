<?php
		//zie foutmelding staan
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

		//inlog gegevens enz voor de database
		$db_hostname = 'localhost';
		$db_username = '';
		$db_password = '';
		$db_database = '';

		//connect met de db
		$mysql = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);

		//check of de db is geconnect ander geef een foutmelding
		if(!$mysql) {
			echo "FOUT: Er is geen verbinding kunnen gemaakt worden met de database<br>";
			echo "Error: ". mysqli_connect_error() ."<br>";
		}
	?>
