<?php 

    

	require dirname(__DIR__)  . '/jspence/vendor/autoload.php';

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();
