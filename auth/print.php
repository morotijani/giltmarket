<?php 
	require_once ("../db_connection/conn.php");

	$data = $_GET['data'] ?? "";
	
	$obj = json_decode($data, true);
	dnd($obj);