<!-- ICI ON AFFICHE LE COMMENTAIRE D'UN COURS -->
<!-- ACCESSIBLE SEULEMENT PAR UN ETUDIANT -->
<?php

$id_cours = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_cours) {
    $jsonString = file_get_contents('json/cours.json');
    $coursData = json_decode($jsonString, true);

    $cours = null;
    foreach ($coursData as $c) {
        if ($c['id'] == $id_cours) {
            $cours = $c;
            break;
        }
    }

    if ($cours) {
        // On affiche les commentaires du cours
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Commentaire du cours - ProgWeb</title>
            <link rel="stylesheet" href="css/styleModifCours.css">
        </head>

        <body>
            <div>
                <form action="commentaire.php" method="post">
                    <h1>Commentaire du cours</h1><br><br>
                    <label><?php echo $cours['type'].' '. $cours['matiere'].' par '. $cours['enseignant']; ?></label>
                    <br><br>

                    <?php //affichage commentaire
                    if($cours['commentaire'] != null) echo $cours['commentaire']; 
                    else echo "Aucun commentaire pour ce cours :(";
                    ?>

                    <br><br>
                    <!-- bouton de retour au calendrier -->
                    <a href="index.php" class="btn" style="display:inline-block; text-decoration:none; background-color: #007bff; color: white; padding: 8px 16px; border-radius: 4px; font-size: 16px;"><label>Retour au calendrier</label></a>
                </form>
            <div>
        </body>
        </html>
        <?php
    } else {
        echo "Erreur : Le cours avec l'ID " . $id_cours . " n'a pas été trouvé.";
    }
} else {
    echo "Erreur : Aucun ID de cours fourni.";
}
?>
