<?php
    $servername = "localhost";
    $dBUsername = "id20883252_fivetech";
    $dBPassword = "Five-5tech";
    $dBName     = "id20883252_fivetech";
    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);    
    if (!$conn) {
    	die("Connection failed: ".mysqli_connect_error());
    }    
    
    if (isset($_POST['toggle_LED'])) {
    	$sql = "SELECT * FROM LED_status;";
    	$result   = mysqli_query($conn, $sql);
    	$row  = mysqli_fetch_assoc($result);
    	
    	if($row['status'] == 0){
    		$update = mysqli_query($conn, "UPDATE LED_status SET status = 1 WHERE id = 1;");		
    	}		
    	else{
    		$update = mysqli_query($conn, "UPDATE LED_status SET status = 0 WHERE id = 1;");		
    	}
    }
    
    $sql = "SELECT * FROM LED_status;";
    $result   = mysqli_query($conn, $sql);
    $row  = mysqli_fetch_assoc($result);
    
    if(isset($_POST['enviar_insert'])){
    
        $sqlMedicoes = "INSERT INTO medicoes (Umidade, Temperatura, Dia, Horario) VALUES ('$u', '$t', '$dia', '$hora');";
    
        $resultado = mysqli_query($$conn,$sqlMedicoes);
    }
?>

<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>PROJETO FIVETECH</title>
</head>
<body>
	<div id="container">
        <div class="wrapper" id="refresh">
			<h1 style="text-align: center; font-family: Arial, sans-serif; font-size: 40; color: #3D5A68">PROJETO FIVETECH</h1>
			<h3 style="text-align: center; font-family: Arial, sans-serif; color: #3D5A68">Sistema de monitoramento e controle da umidade relativa do ar</h3>
			
			<div class="image-container">
				<img src="img/umi.png">
				<img src="img/temp.png">
				<img src="img/data.png">
				<img src="img/hora.png">
			</div>

			<div class="iframe-container" >
					<iframe style="width: 90%; height: 180px; margin: 10px; padding: 20px;" frameborder=0 src= "atualiza.php"></iframe>
			</div>			
	
			<div class="container_status">
					<div class="col_3" style="display: inline-block; text-align: center; margin: 10px;">
						<form action="index.php" method="post" id="LED" enctype="multipart/form-data">			
							<input id="submit_button" type="submit" name="toggle_LED" value="Mudar status" />
						</form>
					</div>
					
					<div class="col_3" style="display: inline-block; margin: 0 30px;">
						<h1 style="text-align: center; font-family: Arial, sans-serif; color: #3D5A68">Automático: </h1>
					</div>

					<div class="col_3" style="text-align: center; margin: -50 10px">										
						<script type="text/javascript">
						$(document).ready (function () {
							var updater = setTimeout (function () {
								$('div#refresh').load ('index.php', 'update=true');
							}, 3000);
						});
						</script>
						<br>

						<?php
							if($row['status'] == 0){?>
							<div class="led_img">
								<img id="contest_img" src="img/OFF.png" >
							</div>
						<?php	
							}
							else{ ?>
								<div class="led_img">
									<img id="contest_img" src="img/ON.png">
								</div>
						<?php
							}
						?>	
					</div>
				</div>	
				<footer>
                    <div class="containerfot">
                
                        <div class="bottonsfot">
                            <div id="nomes">
                              DANIELA DE CASTRO SANTOS<br>
                	      GABRIEL BALIZA BERTUNES<br>
                              JOELSON LIMA DA SILVA<br>
                              RAFAEL SOUZA BARBOSA<br>
                              VALÉRIA VALESCA S OLIVEIRA<br>
                            </div>
                        </div>
                
                        <div class="bottonsfot">
                            <img src="../img/logo.png" id="logofot">
                        </div>
                
                        <div class="bottonsfot">
                           <div id="nomes">
                            ORIENTAÇÃO: <br>
                            LOURIVALDO BARRETO PEREIRA
                           </div>
                        </div>
                        
                    </div>
                
                    <div class="containerfot2">
                        <div id="nomes">
                            <hr>
                            ©2023, FIVETECH. TODOS OS DIREIROS RESERVADOS.   
                        </div>
                    </div>
                </footer>
			</div>
		</div>
</body>

</html>

<style>

    .wrapper{
    width: 100%;
    padding-top: 10px;
    }
    
    #submit_button {
    background-color: #FF4546;
    color: #FFF;
    font-weight: bold;
    font-size: 30px;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
    }
    
    #submit_button:hover {
        background-color: #E63E3F;
    }
    
    .led_img{
        height: 220px;		
        width: 100%;
        margin-right: 10%;
        margin-left: 10%;
        object-fit: cover;
        object-position: center;
    }
    
    
    
    body {
        background-image: url('img/fundo.png');
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
    }
    
    @keyframes parallax {
        from {
            background-position: 0 0;
        }
        to {
            background-position: 0 100%;
        }
    }
    
    #container {
        animation: parallax 10s infinite;
    }
    .container_status {
        display: flex;
        justify-content: center;
    }
    
    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 150px;
    }
    
    .image-container img {
        max-width: 10%;
        height: auto;
    }


     
    footer{
        background-color: #3D5A68;
        margin-top: 1%;
    }
    
    
    .containerfot{
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        width: 100%;
        height: 150px;
    }
    
    .bottonsfot{
        display: flex;
        width: 350px;
        height: 180px;
        align-items: center;
        justify-content: center;
    }
    
    #nomes{
       text-align: center;
       color: white;
       font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
       font-weight: bolder;
    }
     
    #logofot{
        width: 100px;
        height: 150px;
    }
    
    .containerfot2{
        height: 40px;
    }
    
    hr{
        border-style: solid;
    }
    
    #contest_img {
    width: 80px;
    height: 80px;
	margin-top: 30px;
	}
</style>