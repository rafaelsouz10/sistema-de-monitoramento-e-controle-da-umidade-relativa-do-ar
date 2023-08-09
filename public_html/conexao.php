<?php
date_default_timezone_set('America/Bahia');

	$servername = "localhost";
    $dBUsername = "id20883252_fivetech";
    $dBPassword = "Five-5tech";
    $dBName     = "id20883252_fivetech";
    $conexao = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

$dia = date ("Y-m-d");
$hora = date ("H:i:s");

if (isset($_POST['u'])) {
	$u = $_POST['u'];
} else {
	echo "Erro ao receber o valor de umidade";
	die;
}

if (isset($_POST['t'])) {
	$t = $_POST['t'];
} else {
	echo "Erro ao receber o valor de temperatura";
	die;
}

if($conexao){
	echo "Conexão com o Banco de Dados realizada com Sucesso! \n";
}else{
	echo "Erro ao conectar com o Banco de Dados!";
	die;
}

if(isset($_POST['enviar_insert'])){
    
	$sql = "INSERT INTO medicoes (Umidade, Temperatura, Dia, Horario) VALUES ('$u', '$t', '$dia', '$hora');";
    
	$result = mysqli_query($conexao,$sql);
}

/*echo "Umidade: $u ", "Temperatura: $t \n";*/
echo "Dia: $dia ";
echo "Horario: $hora";
?> 