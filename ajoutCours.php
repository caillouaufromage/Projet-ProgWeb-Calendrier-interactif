<?php
// Vérifier que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Charger le fichier JSON en chaîne de caractères
    $jsonString = file_get_contents('json/cours.json');

    // Décoder la chaîne JSON en tableau associatif PHP
    $coursData = json_decode($jsonString, true);

    // Ajouter le nouveau cours
    $nouveauCours = array(
        "type" => $_POST['type'],
        "matiere" => $_POST['matiere'],
        "enseignant" => $_POST['enseignant'],
        "salle" => $_POST['salle'],
        "jour" => $_POST['jour'],
        "debutH" => intval($_POST['debutH']),
        "debutM" => intval($_POST['debutM']),
        "duree" => intval($_POST['duree']),
        "groupe" => intval($_POST['groupe']),
        "couleur" => $_POST['couleur']
    );

    $coursData[] = $nouveauCours;

    // Encoder le tableau associatif PHP en chaîne JSON
    $nouveauJsonString = json_encode($coursData, JSON_PRETTY_PRINT);

    // Écrire la chaîne JSON dans le fichier cours.json
    file_put_contents('json/cours.json', $nouveauJsonString);

    // Rediriger vers la page d'accueil
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un cours - ProgWeb</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Ajouter un cours</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="type">Type de cours:</label><br>
        <input type="text" id="type" name="type" required><br>

        <label for="matiere">Matière:</label><br>
        <input type="text" id="matiere" name="matiere" required><br>

        <label for="enseignant">Enseignant:</label><br>
        <input type="text" id="enseignant" name="enseignant" required><br>

        <label for="salle">Salle:</label><br>
        <input type="text" id="salle" name="salle" required><br>

        <label for="jour">Jour:</label><br>
<!--         <input type="text" id="jour" name="jour" required><br>
 -->        <select id="jour" name="jour">
            <option value="LUNDI">Lundi</option>
            <option value="MARDI">Mardi</option>
            <option value="MERCREDI">Mercredi</option>
            <option value="JEUDI">Jeudi</option>
            <option value="VENDREDI">Vendredi</option>

        </select><br>

        <label for="debutH">Heure de début (heure):</label><br>
        <input type="number" id="debutH" name="debutH" min="8" max="19" required><br>

        <label for="debutM">Heure de début (minutes):</label><br>
        <input type="number" id="debutM" name="debutM" min="0" max="45" step="15" required><br>

        <label for="duree">Durée (en quart d'heure):</label><br>
        <input type="number" id="duree" name="duree" min="1" max="16" required><br>

        <label for="groupe">Groupe :</label><br>
        <select id="groupe" name="groupe">
            <option value="1">Groupe 1</option>
            <option value="2">Groupe 2</option>
            <option value="3">Groupe 3</option>
            <option value="-1">Tous les groupes</option>

        </select><br>

        <label for="couleur">Couleur :</label><br>
        <select id="couleur" name="couleur">
            <option value="#F1948A">Rouge</option>
            <option value="#7DCEA0">Vert</option>
            <option value="#85C1E9">Bleu</option>
            <option value="#F7DC6F">Jaune</option>
            <option value="#D2B4DE">Violet</option>
        </select><br>

        <input type="submit" value="Ajouter">
    </form>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

