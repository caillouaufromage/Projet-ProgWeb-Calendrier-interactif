<?php
session_start(); // added this line
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: log.php');
  exit;
}

if (isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit;
}

//on lit le fichier 'utilisateurs.json'
$usersFile = file_get_contents('json/utilisateurs.json');
$usersData = json_decode($usersFile, true);

/* on vérifie si le formulaire a été soumis
  si oui, on vérifie si les identifiants sont corrects
  si oui, on redirige vers la page d'accueil
  sinon, on affiche un message d'erreur */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputId = $_POST['id'];
    $inputMdp = $_POST['mdp'];
    
    foreach ($usersData as $user) {
        // les identifiants et le mot de passe sont corrects
        if ($user['id'] === $inputId && $user['mdp'] === $inputMdp) {
            $_SESSION['logged'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['id'] = $user['id'];
            header('Location: index.php');
            exit;
        }
        // identifiants incorrects
        else if($user['id'] != $inputId ) {
            $error = "Identifiant incorrect.";
        }
        // mot de passe incorrect
        else if($user['mdp'] != $inputMdp ) {
          $error = "Identifiant incorrect.";
      }
    }
    
    $error = "Autre erreur.";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/stylelog.css">
</head>
<body>
    <div id="login-form-wrap">
        <h2>Connexion</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error ?></p>
        <?php endif ?>
        <form id="login-form" method="post">
            <p>
                <input type="text" id="id" name="id" placeholder="identifiant" required>
                <i class="validation"><span></span><span></span></i>
            </p>
            <p>
                <input type="password" id="mdp" name="mdp" placeholder="mot de passe" required>
                <i class="validation"><span></span><span></span></i>
            </p>
            <p>
                <input type="submit" id="login" value="Login">
            </p>
        </form>
        <div id="create-account-wrap">
        <p>Pas encore inscrit? <a href="sub.php">Créez un compte!</a></p>
      </div><!--create-account-wrap-->
    </div><!--login-form-wrap-->
</body>
</html>
