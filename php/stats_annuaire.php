<?php
include('common.php');
$donnees = array();
if($dbi->test_connexion()) {
	$resultat = $dbi->db_find_record("personnes", "sexe, count(sexe) as nb_sexe", array("group"=>"sexe"));
	while ($infos=$dbi->db_fetch($resultat)) {
		$donnees['Sexe'][$infos['sexe']]= $infos['nb_sexe'];
	}
	$resultat = $dbi->db_find_record("personnes", "categorie, count(categorie) as nb_categorie", array("group"=>"categorie"));
	while ($infos=$dbi->db_fetch($resultat)) {
		$donnees['Categorie'][$infos['categorie']]= $infos['nb_categorie'];
	}
	/*$resultat = $dbi->db_find_record("personnes", "sexe, categorie, count(*) as nb_categorie_and_sexe", array("group"=>"sexe, categorie"));
	while ($infos=$dbi->db_fetch($resultat)) {
		$donnees['Sexe et Categorie'][$infos['sexe']." et ".$infos['categorie']]= $infos['nb_categorie_and_sexe'];
	}*/
	$dbi->db_close();	
}

$infosStatsAnnuaire = infosStatsAnnuaire();
?>
<div class="w3-container w3-responsive">
	<table  id="myTable" class="w3-table-all">
		<tr>
			<th>Nombre de visite <i class="fa fa-user"></i></th>
			<td><?php echo intval(@$infosStatsAnnuaire['visite_utilisateur']); ?></td>
		</tr>
		<tr>
			<th>Nombre de vues de l'annuaire <i class="fa fa-eye"></i></th>
			<td><?php echo intval(@$infosStatsAnnuaire['page']['annuaire']['vues']); ?></td>
		</tr>
	</table>
</div>
<div class="w3-row w3-section w3-border w3-responsive">
	<div class="w3-col w3-border w3-responsive" style="width:50%">
		<canvas id="Sexe"></canvas>
	</div>
	<div class="w3-col w3-border w3-responsive" style="width:50%">
		<canvas id="Categorie"></canvas>
	</div>
</div>
<!--<div style="position: relative; margin: auto; height: 80vh; width: 80vw;">
	<canvas id="Sexe et Categorie"></canvas>
</div>-->
<script src="../js/Chart.bundle.min.js"></script>
<script type="text/javascript">
// pass PHP array to JavaScript array
var myObj = JSON.parse( '<?php echo json_encode($donnees); ?>');
for (key in myObj) {
	//console.log("Cle du tableau 1er niveau | "+key);
	donnees = myObj[key];
	var ctx = document.getElementById(key);
	var labelName = key;
	var arrayLabels = [];
	var arrayData = [];
	var totalValeur = 0;
	for(keyDonnees in donnees) {
		data = donnees[keyDonnees];
		//console.log("Tableau 2nd Niveau "+keyDonnees+" == "+data);
		arrayLabels.push(keyDonnees);
		arrayData.push(data);
		totalValeur = totalValeur + parseInt(data, 10);
	}
	
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: arrayLabels,
			datasets: [{
				label: labelName,
				data: arrayData,
				backgroundColor: [
					'rgba(54, 162, 235, 0.2)',
					'rgba(54, 162, 235, 0.2)'
				],
				borderColor: [
					'rgba(75, 192, 192, 1)',
					'rgba(75, 192, 192, 1)'
					// 'rgba(255,99,132,1)',
					// 'rgba(255, 206, 86, 1)',
					// 'rgba(75, 192, 192, 1)',
					// 'rgba(153, 102, 255, 1)',
					// 'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero:true,
						max: totalValeur,
						stepSize : 1
					}
				}]
			},
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						if(totalValeur == 0) return "0%";
						var total = parseInt(totalValeur);
						var currentValue = parseInt(tooltipItem.yLabel);
						var precentage = Math.floor(((currentValue/total) * 100)+0.5);         
						return ["Valeur : "+currentValue, "Pourcentage : "+precentage + "%"];	
					}
				}
			},
			legend: {
				labels: {
					// This more specific font property overrides the global property
					fontColor: 'black'
				}
			}
		}
		});
}
</script>