<?php 
    
    date_default_timezone_set('America/Bahia');

    $servername = "localhost";
    $dBUsername = "id20883252_fivetech";
    $dBPassword = "Five-5tech";
    $dBName     = "id20883252_fivetech";
	$conexao = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
?>
<style>
    #dados {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        font-family: Arial, sans-serif;
        font-size: 30px;
        color: #3D5A68
    }

    #dados span {
        margin-right: 60px;
    }

	.mensagem-umidade {
        text-align: center;
        font-family: Arial, sans-serif;
        font-size: 30px;
        color: #3D5A68
    }
</style>

<div id="dados">
    <?php
    $consulta = 'SELECT * FROM medicoes ORDER BY ID DESC LIMIT 1';
    $con = $conexao->query($consulta);

    $id = '';
    $umidade = '';
    $temperatura = '';
    $dia = '';
    $horario = '';

    while ($linha = $con->fetch_array()) {
        //$id = $linha['id'];
        $umidade = $linha['Umidade'];
        $temperatura = $linha['Temperatura'];
        $dia = $linha['Dia'];
        $horario = $linha['Horario'];
    }
    ?>

    <span class="id"> <?php echo $id; ?></span>
    <span class="umidade"> <?php echo $umidade; ?>%</span>
    <span class="temperatura"> <?php echo $temperatura; ?>ºC</span>
    <span class="dia"> <?php echo $dia; ?></span>
    <span class="horario"> <?php echo $horario; ?></span>
</div>

<div class="mensagem-umidade">
    <?php
	echo '</br></br>';
    if ($umidade < 50) {
        echo 'Umidade Baixa. Umidificador Ligado!';
    } else if ($umidade >= 55 && $umidade < 60) {
        echo 'Umidade Ideal. Desligado!';
    } else if ($umidade > 60) {
        echo 'Umidade Alta. Ar Condicionado Ligado!';
    }
    ?>
</div>


