<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: log.php');
    exit;
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

$month_offset = isset($_GET['month_offset']) ? intval($_GET['month_offset']) : 0;

$moisActuel = date('F Y', strtotime("{$month_offset} month"));

$moisPrecedent = date('Y-m', strtotime("{$month_offset} month -1 month"));
$moisSuivant = date('Y-m', strtotime("{$month_offset} month +1 month"));
?>

<!DOCTYPE html>
<html>

<head>
    <title>Calendrier - ProgWeb</title>
    <!-- La police d'ecriture -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!---------------------------------------------------------------------------------------------------------->
    <!--                                    BANDEAU EN TETE DE PAGE                                           -->
    <!---------------------------------------------------------------------------------------------------------->
    <div class="header-container" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Informations utilisateur (id + role)-->
        <?php
        echo '<img src="images/logo_utilisateur.png" alt="Utilisateur" width="30" height="30">';
        echo '&nbsp <strong>' . $id . '</strong> (' . strtolower($role) . ')';
        ?>

        <!-- Circulation des dates -->
        <div class="circulation_dates">
            <?php

            echo "<a href='?month_offset=" . ($month_offset - 1) . "'><img src='images/Bflechedroite.png' alt='<--' width='30' height='30'></a> ";
            echo "&nbsp;&nbsp;" . $moisActuel . "&nbsp;&nbsp;";
            echo "<a href='?month_offset=" . ($month_offset + 1) . "'><img src='images/Bflechegauche.png' alt='-->' width='30' height='30'></a> ";
            ?>
        </div>

        <!-- Bouton Ajouter un cours (si admin) -->
        <?php
        if ($_SESSION['role'] == 'admin') {
            echo '<div id="myModal" class="modal">';
            echo '<div class="modal-content">';
            echo '<span class="close">&times;</span>';
            include 'ajoutCours.php';
            echo '</div>';
            echo '</div>';

            echo '<button id="myBtn" style="border: none; background: none; padding: 0; margin-right: 5px; cursor: pointer;"><img src="images/logo_ajouterCours.png" alt="Ajouter un cours" title="Ajouter un cours" width="30" height="30"></button>';
        }
        ?>

        <!-- Bouton de changement de vue -->
        <form action="index.php" method="post" style="margin: 0;">
            <input type="hidden" name="vueCalendrier" value="vueMois">
            <button type="submit"
                style="border: none; background: none; padding: 0; margin-right:15px; cursor: pointer;">
                <img src="images/logo_vue.png" alt="Changer de vue" title="Changer de vue" width="40" height="30">
            </button>
        </form>


        <!-- Bouton de déconnexion -->
        <form action="log.php" method="post" style="margin: 0;">
            <input type="hidden" name="logout" value="true">
            <input type="image" src="images/logo_deconnexion.png" alt="Se déconnecter" title="Se déconnecter" width="30"
                height="30">
        </form>
    </div>

    <!---------------------------------------------------------------------------------------------------------->
    <!--                                            VARIABLES                                                 -->
    <!---------------------------------------------------------------------------------------------------------->
    <?php
    // Tableau des jours de la semaine
    $jours_semaine = array('LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI');

    // Tableau des groupes
    $groupes = array('G1', 'G2', 'G3');

    // Tableau des horaires par quart d'heure
    $horaires = array();
    for ($i = 8; $i < 20; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $horaires[] = $i . ':' . ($j * 15);
        }
    }
    
    ?>

    <!---------------------------------------------------------------------------------------------------------->
    <!--                                    LECTURE DU FICHIER JSON                                           -->
    <!---------------------------------------------------------------------------------------------------------->
    <?php
    // Charger le fichier JSON en chaîne de caractères
    $jsonString = file_get_contents('json/cours.json');

    // Décoder la chaîne JSON en tableau associatif PHP
    $coursData = json_decode($jsonString, true);

    // Parcourir et afficher les informations sur le cours
        foreach ($coursData as $cours) {
        $cours_mois_start = strtotime(date('Y-m-d', strtotime($cours['week_start'])));
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
        // echo "<p> Type : $Ctype <br> Matière : $Cmatiere <br> Enseignant : $Censeignant <br> Salle : $Csalle <br> Jour : $Cjour <br> Début : $Cdebut <br> Durée : $Cduree <br> Groupe : $Cgroupe </p>";
    
        for ($i = 0; $i < $Cduree; $i++) {
            $calendrier[$Cjour][$Cgroupe][$CdebutH . ':' . $CdebutM] = array($Cmatiere, $Cduree - $i, $Ccouleur, $Ctype, $Censeignant, $Csalle, $Cid);
            $CdebutM += 15;
            if ($CdebutM == 60) {
                $CdebutM = 0;
                $CdebutH += 1;
            }
        }
    }


    ?>

    <!----------------------------------------------------------------------------------------------------------->
    <!--                                              JAVASCRIPT                                               -->
    <!----------------------------------------------------------------------------------------------------------->
    <script>
        // Récupérer la fenêtre modale et le bouton qui l'ouvre
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");

        // Récupérer le bouton Fermer
        var span = document.getElementsByClassName("close")[0];

        // Ouvrir la fenêtre modale quand l'utilisateur clique sur le bouton
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // Fermer la fenêtre modale quand l'utilisateur clique sur le bouton Fermer ou en dehors de la fenêtre
        span.onclick = function () {
            modal.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <!---------------------------------------------------------------------------------------------------------->
    <!--                                      AFFICHAGE CALENDRIER                                            -->
    <!---------------------------------------------------------------------------------------------------------->
    <?php

    if (isset($_GET['vueCalendrier'])) {
        $vueCalendrier = $_GET['vueCalendrier'];
    } else {
        //$vueCalendrier = 'vueSemaine';
        $vueCalendrier = 'vueMois';
    }
    switch ($vueCalendrier) {
        case 'vueSemaine':
            include 'vues/vueSemaine.php';
            break;
        case 'vueMois':
            include 'vues/vueMois.php';
            break;
        default:
            print('Erreur : vueCalendrier invalide');
            // include 'vues/vueSemaine.php';
            break;
    }
    ?>

</body>

</html>