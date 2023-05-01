<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: log.php');
    exit;
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

$week_offset = isset($_GET['week_offset']) ? intval($_GET['week_offset']) : 0;

$week_start = strtotime("this week +{$week_offset} week");
$week_end = strtotime("this week +6 days +{$week_offset} week");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendrier - ProgWeb</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<body>
<!-- BANDEAU EN TETE DE PAGE -->
<div class="header-container" style="display: flex; justify-content: space-between; align-items: center;">
    <!-- Informations utilisateur (id + role)-->
    <?php
    echo '<img src="images/logo_utilisateur.png" alt="Utilisateur" width="30" height="30">';
    echo '&nbsp <strong>' . $id . '</strong> (' . strtolower($role) . ')';
    ?>

    <!-- Circulation des dates -->
    <div class="circulation_dates">
        <?php
        echo "<a href='?week_offset=" . ($week_offset - 1) . "'><img src='images/Bflechedroite.png' alt='<--' width='30' height='30'></a> ";
        echo "&nbsp;&nbsp;Semaine du " . date('d/m/Y', $week_start) . " au " . date('d/m/Y', $week_end) . "&nbsp;&nbsp;";
        echo "<a href='?week_offset=" . ($week_offset + 1) . "'><img src='images/Bflechegauche.png' alt='-->' width='30' height='30'></a> ";
        ?>
    </div>

    <!-- Bouton Ajouter un cours (si admin) -->
    <?php
    //si admin
    if($_SESSION['role'] == 'admin'){
        echo '<div id="myModal" class="modal">';
        echo '<div class="modal-content">';
            echo '<span class="close">&times;</span>';
            include 'ajoutCours.php';
        echo '</div>';
        echo '</div>';
        
        echo '<button id="myBtn" style="border: none; background: none; padding: 0; margin-right: 5px; cursor: pointer;"><img src="images/logo_ajouterCours.png" alt="Ajouter un cours" title="Ajouter un cours" width="30" height="30"></button>';
    }
    ?>
    <!-- Bouton de déconnexion -->
    <form action="log.php" method="post" style="margin: 0;">
        <input type="hidden" name="logout" value="true">
        <input type="image" src="images/logo_deconnexion.png" alt="Se déconnecter" title="Se déconnecter" width="30" height="30">
    </form>
</div>

    
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
    $cours_week_start = strtotime(date('Y-m-d', strtotime($cours['week_start'])));
    $display_week_start = strtotime(date('Y-m-d', $week_start));
    $Crenouvelable = $cours['renouvelable'];
    if (($Crenouvelable == false) && ($cours_week_start != $display_week_start)) {
        continue;
    }
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
    $Cid = $cours['id'];

    // Afficher les informations sur le cours
/*     echo "<p> Type : $Ctype <br> Matière : $Cmatiere <br> Enseignant : $Censeignant <br> Salle : $Csalle <br> Jour : $Cjour <br> Début : $Cdebut <br> Durée : $Cduree <br> Groupe : $Cgroupe </p>";
 */
       for($i=0; $i<$Cduree; $i++){
        $calendrier[$Cjour][$Cgroupe][$CdebutH.':'.$CdebutM] = array($Cmatiere, $Cduree-$i, $Ccouleur, $Ctype, $Censeignant, $Csalle, $Cid);
        $CdebutM += 15;
        if($CdebutM == 60){
            $CdebutM = 0;
            $CdebutH += 1;
        } 
    } 
/*     echo $Cmatiere." ".$Cjour." ".$CdebutH.":".$CdebutM." ".$Cgroupe.'<br>';
 */}  



/********************************************************************************************************** */
/* //si admin
if($_SESSION['role'] == 'admin'){
    echo '<div id="myModal" class="modal">';
    echo '<div class="modal-content">';
        echo '<span class="close">&times;</span>';
        include 'ajoutCours.php';
    echo '</div>';
    echo '</div>';
    //echo '<input type="image" src="images/logo_ajouterCours.png" alt="Ajouter un cours" title="Ajouter un cours" id="myBtn" width="30" height="30">';
    
    echo '<button id="myBtn" style="border: none; background: none; padding: 0;"><img src="images/logo_ajouterCours.png" alt="Ajouter un cours" title="Ajouter un cours" width="30" height="30"></button>';
} */
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

<!-- <script>
// Récupérer la fenêtre modale et le bouton qui l'ouvre
var modal = document.getElementById("myModal2");
var btn = document.getElementById("myBtn");

// Récupérer le bouton Fermer
var span = document.getElementsByClassName("close")[0];

// Ouvrir la fenêtre modale quand l'utilisateur clique sur le bouton
btn.onclick = function() {
  modal.style.display = "block";
}

// Fermer la fenêtre modale quand l'utilisateur climyModalque sur le bouton Fermer ou en dehors de la fenêtre
span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

function showModal() {
  var modal = document.getElementById("myModal");
  modal.style.display = "block";
  modal.style.zIndex = 9999; /* Valeur élevée pour afficher la fenêtre modale au-dessus des autres éléments */
}
</script> -->
<!---------------------------------------------------------------------------------------------------------->
<?php
// Affichage du tableau calendrier
echo '<table>';

// les jours de la semaine
echo '<thead><tr><th></th>';
foreach ($jours_semaine as $jour) {
    echo '<th id=jours colspan=3 #87CEEB >' . $jour . '</th>';
}
echo '</tr></thead>';

// les groupes
echo '<tr><td></td>';
foreach($jours_semaine as $jour) {
    foreach($groupes as $groupe){
        echo '<td id=groupe bgcolor = "#FFA07A">' . $groupe . '</td>';
    }
}
echo '</tr>';

// les horaires
$i = 0;
echo '<tbody>';

$rowspan_remaining = array();

foreach ($horaires as $horaire) {
    $color = "#C0C0C0";
    if ($i % 2 == 0) $color = "#FFFFFF";
    echo '<tr> <td id=creneau bgcolor = "#BBBBBBB"" >' . $horaire . '</td>';
    foreach ($jours_semaine as $jour) {

        for($groupe = 0; $groupe <= 3; $groupe++){
            $slot = $calendrier[$jour][$groupe][$horaire];
            $cellContent = '';
            $bgColor = $color;


            if (isset($rowspan_remaining[$jour][$groupe]) && $rowspan_remaining[$jour][$groupe] > 0) {
                // Ne pas afficher le contenu de la cellule si un rowspan est en cours
                $rowspan_remaining[$jour][$groupe]--;
            } else {
                if (is_array($slot)) {
                    /* $cellContent = '<button class="cours-button" style="background-color:'.$slot[2].'" data-cours=\''.json_encode($slot).'\'>'
                        .$slot[0].'<br>'.$slot[3].'<br>'.$slot[4].'<br>'.$slot[5].'</button>';
                     */
                    $cellContent = '<button class="cours-button" style="background-color:'.$slot[2].'" onclick="showModal()" data-cours=\''.json_encode($slot).'\'>'
                        .$slot[0].'<br>'.$slot[3].'<br>'.$slot[4].'<br>'.$slot[5].'</button>';
                    if($groupe == 0){
                        echo '<td id="creneau" bgcolor="' . $slot[2] . '" rowspan="' . $slot[1] . '" colspan="3">' . $cellContent . '</td>';
                        $rowspan_remaining[$jour][$groupe] = $slot[1] - 1;
                        break;
                    } else {
                        if($_SESSION['role'] == 'admin') echo '<td id="creneau" bgcolor="' . $slot[2] . '" rowspan="' . $slot[1] . '"><a href="modifCours.php?id='.$slot[6].'">' .$cellContent. '</td></a>';
                        else echo '<td id="creneau" bgcolor="' . $slot[2] . '" rowspan="' . $slot[1] . '">' . $cellContent . '</td>';
                        $rowspan_remaining[$jour][$groupe] = $slot[1] - 1;
                    }
                } else {
                    if ($groupe != 0) {
                        echo '<td id=creneau bgcolor="' . $color . '"></td>';
                    }
                }          
            }
            
        }
    }
    echo '</tr>';
    $i = $i + 1;
}


echo '</tbody>';
echo '</table>';
?>

</body>
</html>


<!-- foreach ($jours_semaine as $jour) {
        for($groupe = 0; $groupe <= 3; $groupe++){
            $slot = $calendrier[$jour][$groupe][$horaire];
            $cellContent = '';
            $bgColor = $color;

            if(is_array($slot) && $groupe == 0){
                $cellContent = $slot[0];
                $bgColor = $slot[2];
                echo '<td id=creneau bgcolor="' . $bgColor . '">' . $groupe . $cellContent . '</td>';
                break;
            }else{
                if (is_array($slot)) {
                    $cellContent = $slot[0];
                    $bgColor = $slot[2];
                }
                echo '<td id=creneau bgcolor="' . $bgColor . '">' . $groupe . $cellContent . '</td>';
        }

        } -->
