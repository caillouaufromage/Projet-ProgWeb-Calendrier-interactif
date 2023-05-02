<?php
session_start();
if (isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit;
}

$usersFile = 'json/utilisateurs.json';
$usersData = json_decode(file_get_contents($usersFile), true);

// handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $inputId = $_POST['id'];
    $inputMdp = $_POST['mdp'];

    // check if user already exists
    foreach ($usersData as $user) {
        if ($user['id'] === $inputId) {
            $error = "Cet identifiant est déjà pris.";
            break;
        }
    }

    // if user does not exist, add them to the users data array
    if (!isset($error)) {
        $usersData[] = array(
            'id' => $inputId,
            'mdp' => $inputMdp,
            'role' => 'etudiant'
        );

        // save the updated users data to the file
        file_put_contents($usersFile, json_encode($usersData));

        // redirect the user to the login form
        header('Location: log.php');
        exit;
    }
}

// handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $inputId = $_POST['id'];
    $inputMdp = $_POST['mdp'];

    foreach ($usersData as $user) {
        if ($user['id'] === $inputId && $user['mdp'] === $inputMdp) {
            $_SESSION['logged'] = true;
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        }
    }

    $error = "Identifiant ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/stylelog.css">
</head>

<body>
    <div id="login-form-wrap">
        <h2>Inscription</h2>
        <?php if (isset($error)): ?>
            <p class="error">
                <?php echo $error ?>
            </p>
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
                <input type="submit" id="register" name="action" value="Inscription">
            </p>
        </form>
        <div id="create-account-wrap">
            <p>Déjà inscrit?? <a href="log.php">Connectez vous!</a></p>
        </div><!--create-account-wrap-->
    </div><!--login-form-wrap-->
</body>

</html>