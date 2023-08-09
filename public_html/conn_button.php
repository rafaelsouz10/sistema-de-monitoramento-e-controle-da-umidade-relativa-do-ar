<?php
    //conexão com banco de dados
    $servername = "localhost";
    $dBUsername = "id20883252_fivetech";
    $dBPassword = "Five-5tech";
    $dBName     = "id20883252_fivetech";
    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
    
    //verifica a conexão, caso falha, imprime a mensagem de erro.
    if (!$conn) {
    	die("Connection failed: ".mysqli_connect_error());
    }
    
    //Lê o banco de dados
    if (isset($_POST['check_LED_status'])) {        //se o que recebeu pelo POST foi 'check_LED_status=1' 
    	$led_id = $_POST['check_LED_status'];	    //led_id = 1
    	$sql = "SELECT * FROM LED_status WHERE id = '$led_id';";        //consulta sql da tabela LED_status do id =1
    	$result   = mysqli_query($conn, $sql);      //salva o resultado da consulta
    	$row  = mysqli_fetch_assoc($result);        //organiza os dados recebidos 
    	if($row['status'] == 0){    
    		echo "LED_is_off";      
    	}                       //'LED_is_off' ou 'LED_is_on' será lido no esp na variável 
    	else{                  //response_body para ligar ou desligar o sistema             
    		echo "LED_is_on";
    	}	
    }	
    
    //Atualiza o banco de dados
    if (isset($_POST['toggle_LED'])) {
    	$led_id = $_POST['toggle_LED'];	
    	$sql = "SELECT * FROM LED_status WHERE id = '$led_id';";
    	$result   = mysqli_query($conn, $sql);
    	$row  = mysqli_fetch_assoc($result);
    	if($row['status'] == 0){
    		$update = mysqli_query($conn, "UPDATE LED_status SET status = 1 WHERE id = 1;");
    		echo "LED_is_on";
    	}
    	else{
    		$update = mysqli_query($conn, "UPDATE LED_status SET status = 0 WHERE id = 1;");
    		echo "LED_is_off";
    	}	
    }	
?>