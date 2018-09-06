<!-- 
  /////////////////////
//       TI-Trash      //
//                     //
//       Author:       //
//   Tiago Goncalves   //
//     with help of    //
//    Tiago Cardoso    //
  /////////////////////



/////////////////////AVISOS/WARNINGS/////////////////////
-WEB APP OTIMIZADA E TESTADA PARA/COM GOOGLE CHROME. 
    -THIS WEP APP IS OPTIMIZED AND TESTED FOR/WITH GOOGLE CHROME.

-QUAISQUER OUTROS BROWSERS UTILIZADOS PODERÃO SUJEITAR A ALTERAÇÕES, QUER NO VISUAL, QUER NO FUNCIONAMENTO DA WEB APP.
    -USING THE WEB APP WITH ANOTHER BROWSER MAY CAUSE CHANGE EITHER ON VISUAL OR IN PERFORMANCE.

-TODO O PROCESSO TI-TRASH ESTÁ INSERIDO NA LICENÇA CC BY-SA 4.0. PARA MAIS DETALHES, ACEDER A
    -ALL THE TI-TRASH PROCESS IS UNDER THE CC BY-SA 4.0. LICENSE. FOR MORE DETAILS, GO TO
https://creativecommons.org/licenses/by-sa/4.0/ 

-PARA SUPORTE, CONTACTAR 
    -FOR SUPPORT ISSUES, CONTACT
support@titrash.com    
/////////////////////////////////////////////////////////



///TARIFARIOS DE PAGAMENTO///

PARA OS UTILIZADORES QUE POSSUEM NEGOCIOS RELACIONADOS COM A INDUSTRIA QUE TÊM COMO CONSEQUÊCIA A PRODUÇÃO DE RESÍDUOS PAGAM MAIS 3% DO QUE OS UTILIZADORES PARTICULARES
                
PARTICULAR: 10%
PROFISSIONAL: 13%
/////////////////////////////
-->

<?php
		$db_name = 'db.db';
		$db_drvr = 'sqlite';
		
		$user_info = Array();
		$user_dumps = Array();
		$containers = Array();
        $users_dumps_map = Array();

		try{
			$pdo = new PDO("$db_drvr:$db_name");
			$ui_query = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');
			$ui_query->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_INT);
			$ui_query->execute();
			$user_info = $ui_query->fetch(PDO::FETCH_ASSOC);
			
			$ud_query = $pdo->prepare('SELECT * FROM garbage WHERE user_id = :user_id');
			$ud_query->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_INT);
			$ud_query->execute();
			$user_dumps = $ud_query->fetchAll(PDO::FETCH_ASSOC);
			
			$c_query = $pdo->prepare('SELECT * FROM containers c INNER JOIN garbage g ON (c.id = g.container_id) WHERE g.user_id = :user_id');
            $c_query->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_INT);
			$c_query->execute();
			$c_results = $c_query->fetchAll(PDO::FETCH_ASSOC);
			foreach( $c_results as $dump ){
				$containers[$dump['id']] = $dump['location'];
                $users_dumps_map[$dump['id']] = $dump['coord'];
			}
            
		}

		catch(PDOException $e){
			var_dump($e);
			$user_info = Array();
			$user_dumps = Array();
		} 
	?>

    <html lang="pt">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TI-Trash | Gestão de Resíduos</title>
        <link rel="stylesheet" href="/css.css">
    </head>
    <style>
        body {
            font-family: Calibri;
            background-color: ghostwhite;
        }

        html {
            font-family: Deansgate;
        }

        .perfil {
            border: 2px solid #4CAF50;
            border-radius: 50%;
            padding: 5px;
            float: left;
            width: 220px;
            height: 220px;

        }

        #map {
            height: 650px;
            width: 680px;
            position: absolute;
            overflow: hidden;
            margin-left: 670px;
            margin-right: auto;
            top: 80;
        }

        .flexy {
            display: block;
            width: 90%;
            border: 1px solid #eee;
            max-height: 320px;
            overflow: auto;
        }

        .flexy thead {
            display: -webkit-flex;
            -webkit-flex-flow: row;
        }

        .flexy thead tr {
            padding-right: 15px;
            display: -webkit-flex;
            width: 100%;
            -webkit-align-items: stretch;
        }

        .flexy tbody {
            display: -webkit-flex;
            height: 50px;
            overflow: auto;
            -webkit-flex-flow: row wrap;
        }

        .flexy tbody tr {
            display: -webkit-flex;
            width: 100%;
        }

        .flexy tr td {
            width: 15%;
        }

        tr {
            height: 50px;
        }

        tbody {
            height: 50px;
        }

        thead {
            height: 50px;
        }


        .submit {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }


        .button {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            -webkit-transition-duration: 0.4s;
            /* Safari */
            transition-duration: 0.4s;
        }


        .button:hover {
            box-shadow: 0 12px 16px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
        }

        /*ScrollBar*/

        /* width */

        ::-webkit-scrollbar {
            width: 12px;
        }

        /* Track */

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */

        ::-webkit-scrollbar-thumb {
            background: #4CAF50;
        }

        /* Handle on hover */

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }


        /* Center the loader */

        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #4CAF50;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */

        .animate-bottom {
            position: static;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }
            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }
            to {
                bottom: 0;
                opacity: 1
            }
        }

    </style>

    <body onload="myFunction()">



        <div id="loader"></div>

        <div style="display:none;" id="myDiv" class="animate-bottom">

            <?php
			if( $user_info ){
                
                echo '<header>

            <form action="index.php">
                <input type="submit" class="button " value="PRINCIPAL" style="float:right">
            </form>
        </header>';
                
                if( $user_info['id'] == '1' ){
                    echo '<br><img src="img/users/tiago1.png"style="float:left;margin-top: -10; width:"220px" height:"220px"; class="perfil"">';
                }
                
                else if( $user_info['id'] == '2'){
                    echo '<br><img src="img/users/Alexandre.png" style="float:left;margin-top: -10; width="233.2px" height="220px"; class="perfil"">';
                }
                
                else if( $user_info['id'] == '3'){
                    echo '<br><img src="img/users/Ricardo.png" style="float:left;margin-top: -10; width="233.2px" height="220px"; class="perfil"">';
                }
                
                else if( $user_info['id'] == '4'){
                    echo '<br><img src="img/users/Corticeira Amorim.png" style="float:left;margin-top: -10; width="233.2px" height="220px"; class="perfil"">';
                }
                
                echo '<p style="text-align:top; font-size: 26px;margin-top: 5px;">&nbsp;<b> ' . $user_info['nome'] . '</b></p>' ;
                echo '<p style="margin-top: 40px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EMAIL:&nbsp;&nbsp;<b>' . $user_info['email'] . '</b></p>';
                echo '<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTACTO:&nbsp;&nbsp;<b>'  . $user_info['telemovel'] . '</b></p>';
                
                if($user_info['categoria'] == "Particular"){
                    echo '<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLIENTE:&nbsp;&nbsp;<b>'  . $user_info['categoria'] . '</b></p>';
                    echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarifário de Pagamento: <b>10%</b><br><br><br>';
                }
                
                else{
                    echo '<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLIENTE:&nbsp;&nbsp;<b>'  . $user_info['categoria'] . '</b></p>';
                    echo '<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarifário de Pagamento: <b>13%</b></b><br><br><br>';
                }
                
				echo '<table border="1px" class="flexy" style="width: 625px;>';
                echo '<thead style="background-color:white;">';
                echo '<tbody style="height: 50px; background-color:white;">';
				echo '<tr >';
					echo '<th style="width: 150px;">Quantidade<br> em kg</th>';
					echo '<th style="width: 300.49px;">Localização do Depósito&nbsp;&nbsp;&nbsp;</th>';
					echo '<th style="width: 167px;height: 46px;">Data/Hora do Depósito</th>';
				echo '</tr>';
                echo '</tbody>';
				echo '</thead>';
                echo '</table>';
                
                echo '<table border="1px" class="flexy" style="width:625px; height:320px; background-color:white;">';
				$total = 0.0 ;
				foreach( $user_dumps as $dump ){
					$total += $dump['quantity'];
                    echo '<tbody style="height: 50px">';
					echo '<tr>';
						echo '<td style="width: 149px;" align="center">' . $dump['quantity'] . '</td>';
						echo '<td style="width: 298.9px;" align="center">' . $containers[$dump['container_id']] . '</td>';
						echo '<td style="width: 154px;" align="center">' . $dump['timestamp'] . '</td>';
					echo '</tr>';
                    echo '</tbody>';
				}
				echo '</table>';
				
				
				echo '<h3 style="color: 4CAF50;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total de Resíduos Registados: ' . $total . ' kg<br></h3>';
                
                //O valor a pagar é 10% do peso dos resíduos registados
                if($user_info['categoria'] == "Particular"){
                echo '<h3 style="color: 4CAF50;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A pagar: ' .round($total*0.10, 2, PHP_ROUND_HALF_EVEN) . '€</h3>';
                    }
                else{
                    echo '<h3 style="color: 4CAF50;" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A pagar: ' .round($total*0.13, 2, PHP_ROUND_HALF_EVEN) . '€</h3>';
                }
                
                
                echo'<div id="map"></div>';

                echo' <script>
                    function myMap() {
                        var myCenter = new google.maps.LatLng(37.2175340809057, -8.160426093749948);
                        var mapCanvas = document.getElementById("map");
                        var mapOptions = {
                            center: myCenter,
                            zoom: 7
                        };
                        
                        var map = new google.maps.Map(mapCanvas, mapOptions);
                        var image = "img/icon/recycle-bin.png";';
                        foreach ($users_dumps_map as $value) {
                            echo ' var marker = new google.maps.Marker({
                                position: new google.maps.LatLng('.$value.'), map:map, icon:image});
                            marker.setMap(map);';
                            
                                echo 'var infowindow = new google.maps.InfoWindow({
                            content: "Peso: <b>'.$dump['quantity'].'</b> kg<br>Data/Hora: <b>'.$dump['timestamp'].'</b><br>Endereço: <b>'.$containers[$dump['container_id']].'</b></b>"
                        });';
                                    
                    echo 'infowindow.open(map, marker);';
                            }
                            
                        
                        echo '}
            
                </script>';
      
                echo'<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQLYmqoQA-KRJUs7gQ_ahwiLAiuUdVPd0&callback=myMap"></script>';
             
           }
            
        
        else{ 
        echo '<header>

            <form action="index.php">
                <input type="submit" class="button " value="PRINCIPAL" style="float:right">
            </form>
        </header><br><br><br><br><h1 style="text-align:center;">Nenhum utilizador com o ID introduzido atribuido!</h1>'; 
        }
?>
            
        <!-- PRELOADER ANIMATION -->
        </div>
        <script>
            var myVar;

            function myFunction() {
                myVar = setTimeout(showPage, 1200); //Set Time of Animation
            }

            function showPage() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("myDiv").style.display = "block";
            }

        </script>
    </body>

    </html>
