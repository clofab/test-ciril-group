<?php
	include "loader/config_loader.php";
	include "model/simulateur.php";
	
	$config = new ConfigLoader();
	$simulateur = new Simulateur();
	$simulateur->startNewSimulation($config->getDimensions(),$config->getDeparts());
?>
<html>
	<title>Simulateur de feux</title>
	<style>
		table{
			margin-bottom : 15px;
		}
		tr{
			height : 15px;			
		}
		td{
			width : 15px;			
		}
		.green{
			background-color : green;
		}
		.fire{
			background-color : red;
		}
		.burned{
			background-color : grey;
		}
	</style>
	<body>
		<div id="simulations"></div>
		<button onclick="etapeSuivante()">Etape suivante</button> 
	</body>
	<script>		
		var uid = "<?php echo $simulateur->getUid(); ?>";
		var dimensions = <?php echo json_encode($simulateur->getDimensions()); ?>;
		var feux = <?php echo json_encode($simulateur->getFeux()); ?>;
		var deja_brules = <?php echo json_encode($simulateur->getDejaBrules()); ?>;
		
		var simulation = {
			"uid" : uid,
			"dimensions" : dimensions,
			"feux" : feux,
			"deja_brules" : deja_brules,
		}
		
		function displaySim(simulation){
			height = simulation.dimensions[0];
			width = simulation.dimensions[1];
			var grid = [];
			for(i = 0; i < height; i++){
				grid.push([]);
				for(j = 0; j < width; j++){
					grid[i][j] = "green";
				}
			}
			for(i = 0; i < simulation.feux.length; i++){
				grid[simulation.feux[i][0]][simulation.feux[i][1]] = "fire";
			}
			for(i = 0; i < simulation.deja_brules.length; i++){
				grid[simulation.deja_brules[i][0]][simulation.deja_brules[i][1]] = "burned";
			}
			table_content = []
			for(i = 0; i < height; i++){
				tr = "<tr>";
				for(j = 0; j < width; j++){					
					tr += "<td class='"+grid[i][j]+"'</td>";
				}
				tr += "</tr>";
				table_content.push(tr);
			}
			document.getElementById("simulations").innerHTML = document.getElementById("simulations").innerHTML + "<table>" + table_content.join("") + "</table>";
			console.log(grid);
		}
		function etapeSuivante(){
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				   reponse = JSON.parse(xhttp.responseText);
				   simulation.feux = reponse.feux;
				   simulation.deja_brules = reponse.deja_brules;
				   displaySim(simulation);
				}
			};
			xhttp.open("GET", "etape_suivante.php?uid="+simulation.uid, true);
			xhttp.send();			
		}
		
		displaySim(simulation);
	</script>
</html>