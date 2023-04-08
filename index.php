<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: log.php');
    exit;
}

// identifiants de connexion
$id = $_SESSION['id'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Calendrier - ProgWeb</title>
  <link rel="stylesheet" href="css/style.css">
  <div class="circulation_dates">
    <?php
    // navigation dates
    echo "<div class = 'button'>";
/*     echo "<button type='submit' value='".($_POST['date']-604800)."' class='button' name='date' style='width: 30px; height: 30px;'><img src='images/Bflechedroite.png' style='width: 30px; height: 30px;'></button>";
 */    echo "<button type='submit' value= '".($_POST['date']-604800)."'class='button' name='date'><-</button>";
    echo "Semainier de la semaine du ".date('d/m/Y', $_POST['date'])." au ".date('d/m/Y', $_POST['date']+428400)."";
    echo "<button type='submit' value= '".($_POST['date']+604800)."'class='button' name='date'>-></button>";
    echo "</div>";
    echo "Id: ".$id."\n Role: ".$role;
    ?>
  </div>
</head>
<body>
    <form action='log.php' method='post'>
        <input type='hidden' name='logout' value='true'>
        <input type='submit' value='Se deconnecter'>
    </form>

<?php
//*************************************    variables    ************************************/
// Tableau des jours de la semaine
$jours_semaine = array('LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI');

// Tableau des horaires par quart d'heure
$horaires = array();
for ($i = 8; $i < 20; $i++) {
    for ($j = 0; $j < 4; $j++) {
        $horaires[] = $i . ':' . ($j * 15);
    }
}
// Tableau des groupes
$groupes = array('G1', 'G2', 'G3');


//*************************************    fichier json    ************************************/

// Charger le fichier JSON en chaîne de caractères
$jsonString = file_get_contents('json/cours.json');

// Décoder la chaîne JSON en tableau associatif PHP
$coursData = json_decode($jsonString, true);

// Parcourir et afficher les informations sur le cours
foreach ($coursData as $cours) {
    $Ctype = $cours['type'];
    $Cmatiere = $cours['matiere'];
    $Censeignant = $cours['enseignant'];
    $Csalle = $cours['salle'];
    $Cjour = $cours['jour'];
    $CdebutH = $cours['debutH'];
    $CdebutM = $cours['debutM'];
    $Cduree = $cours['duree'];
    $Cgroupe = $cours['groupe'];
    $Ccouleur = $cours['couleur'];

    // Afficher les informations sur le cours
/*     echo "<p> Type : $Ctype <br> Matière : $Cmatiere <br> Enseignant : $Censeignant <br> Salle : $Csalle <br> Jour : $Cjour <br> Début : $Cdebut <br> Durée : $Cduree <br> Groupe : $Cgroupe </p>";
 */
    for($i=0; $i<$Cduree; $i++){
        $calendrier[$Cjour][$CdebutH.':'.$CdebutM] = array($Cmatiere, $Ccouleur, $Cgroupe, $Cduree, $CdebutH, $CdebutM);
        $CdebutM += 15;
        if($CdebutM == 60){
            $CdebutM = 0;
            $CdebutH += 1;
        }
    }

}
/********************************************************************************************************** */
//si admin
if($role = 'enseignant'){
    echo '<div id="myModal" class="modal">';
    echo '<div class="modal-content">';
        echo '<span class="close">&times;</span>';
        include 'ajoutCours.php';
    echo '</div>';
    echo '</div>';
    echo '<button id="myBtn">Ajouter un cours</button>';
}
?>

<script>
// Récupérer la fenêtre modale et le bouton qui l'ouvre
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");

// Récupérer le bouton Fermer
var span = document.getElementsByClassName("close")[0];

// Ouvrir la fenêtre modale quand l'utilisateur clique sur le bouton
btn.onclick = function() {
  modal.style.display = "block";
}

// Fermer la fenêtre modale quand l'utilisateur clique sur le bouton Fermer ou en dehors de la fenêtre
span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>
<!---------------------------------------------------------------------------------------------------------->
<?php
// Affichage du tableau calendrier
echo '<table>';

// les jours de la semaine
echo '<thead><tr><th></th>';
foreach ($jours_semaine as $jour) {
    echo '<th colspan=3 #87CEEB >' . $jour . '</th>';
}
echo '</tr></thead>';

// les groupes
echo '<tr><td></td>';
foreach($jours_semaine as $jour) {
    foreach($groupes as $groupe){
        echo '<td bgcolor = "#FFA07A">' . $groupe . '</td>';
    }
}
echo '</tr>';

// les horaires
$i = 0;
echo '<tbody>';
foreach ($horaires as $horaire) {
    $color = "#C0C0C0";
    if ($i % 2 == 0) $color = "#FFFFFF";
    echo '<tr> <td id=creneau bgcolor = "#BBBBBBB"" >' . $horaire . '</td>';
    foreach ($jours_semaine as $jour) {

        for ($col = 0; $col < 3; $col++) {
            $slot = $calendrier[$jour][$horaire];

            $cellContent = '';
            $bgColor = $color;

            // Si le créneau est pour les trois groupes (amphi)
            if (is_array($slot) && $slot[2] === -1) {
                $cellContent = $slot[0];
                $bgColor = $slot[1];
                echo '<td id=creneau colspan="3" bgcolor="' . $bgColor . '">' . $cellContent . '</td>';
                break;

            } else {
                // Si le créneau est pour un groupe
                if (is_array($slot) && ($slot[2] == $col + 1)) {
                    $cellContent = $slot[0];
                    $bgColor = $slot[1];
                }
                echo '<td id=creneau bgcolor="' . $bgColor . '">' . $cellContent . '</td>';
            }
        }
    }
    echo '</tr>';
    $i = $i + 1;
}


/*     echo $calendrier['JEUDI']['9:30'];
 */

echo '</tbody>';
echo '</table>';
?>

</body>
</html>