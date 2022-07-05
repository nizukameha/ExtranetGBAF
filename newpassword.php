<?php 
include_once('DB/connexionDB.php');
// Si l'utilisateur est connecté il est redirigé sur la page d'accueil
if(!isset($_SESSION['id_user'])) {
    header('Location: connexion.php');
    exit;
}
// requete a la bdd pour récupérer les données de l'utilisateur en fonction de son id
$requete = $DB->prepare("SELECT *
    FROM account 
    WHERE id_user = ?");
// récupere l'id_user de l'utilisateur
$requete->execute([$_SESSION['id_user']]);

$req = $requete->fetch();

// Condition qui s'applique lorsque que les input du formulaire sont vides
if(!empty($_POST)) {
    extract($_POST);
    $valid = (boolean) true;
    // Condition qui s'applique lorsque l'utilisateur clique sur le boutton d'envoie du formulaire
    if(isset($_POST['valider'])) {
        // Le nom sera celui envoyé par l'utilisateur sans espace ni injection de code
        $mdp = htmlspecialchars(trim($mdp));
        // Si l'utilisateur ne rentre aucunes données la modification n'aura pas lieu
        if(empty($mdp)) {
            $valid = false;
        }
        // Si les modifications sont valides on met a jour la bdd et l'utilisateur est redirigé sur la page d'acceuil
        if($valid) {
            $crypt_mdp = password_hash($mdp, PASSWORD_BCRYPT);
            $requete = $DB->prepare("UPDATE account SET mdp = ?
            WHERE id_user = ?");
            $requete->execute(array($crypt_mdp, $_SESSION['id_user']));

            header('Location: connexion.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/inscription.css">
        <title>GBAF</title>
        <link rel="shortcut icon" type="image/png" href="IMG/faviconGBAF.ico"/>
    </head>
    <body>
        <header>
            <?php include('headerconnexion.php'); ?>
        </header>
        <section class="conteneurImage">
            <img class='logoOpacity' src="IMG/logoGBAF.jpg" alt="Logo GBAF">   
            <form action="newpassword.php" method="post">
                <h1>Mot de passe oublié</h1><br>          
                <label for="mdp">Nouveau mot de passe :</label><br>
                    <input type="password" name="mdp"><br>
                <div class="button">
                <button type="submit" name="valider">Valider</button> 
                </div>
            </form>
        </section>
        <hr>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>