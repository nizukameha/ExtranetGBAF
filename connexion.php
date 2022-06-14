<?php
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');

if(isset($_SESSION['id_user'])) {
    header('Location: accueil.php');
    exit;
}

if(!empty($_POST)) {
    extract($_POST);
    $valid = (boolean) true;

    if(isset($_POST['connexion'])) {
        $identifiant = htmlspecialchars(trim($identifiant));
        $mdp = htmlspecialchars(trim($mdp));

        if(empty($identifiant)) {
            $valid = false;
            $err_identifiant = "* Indiquez votre identifiant";
        }

        if(empty($mdp)) { 
            $valid = false;
            $err_mdp = "* Indiquez votre mot de passe";
        }
        // Si l'utilisateur a remplis l'identifiant et le mot de passe on passe a la condition suivante
        // On vérifie dans la bdd que l'identifiant et le mdp correspondent
        if($valid) {
            $requette = $DB->prepare("SELECT mdp
                FROM account
                WHERE identifiant = ?");
            $requette->execute(array($identifiant));
            $requette = $requette->fetch();

            // Si la combinaison identifiant et mdp est n'est pas vrai voici ce qu'il se passe :
            if(isset($requette['mdp'])) {
                // Si l'identifiant est correct mais pas le mdp alors c'est faux
                if(!password_verify($mdp, $requette['mdp'])) {
                    $valid = false;
                    $err_pseudo = "* L'identifiant ou/et le mot de passe sont incorrects";
                }
            // Si l'identifiant et le mot de passe sont incorrect alors c'est faux
            } else {
                $valid = false;
                $err_pseudo = "* L'identifiant ou/et le mot de passe sont incorrects";
            }
        }

        // Une fois que c'est validé on prepare la bdd :
        if($valid) {
            $requette = $DB->prepare("SELECT *
            FROM account
            WHERE identifiant = ?");
            $requette->execute(array($identifiant));
            $requette_user = $requette->fetch();
                
            // On met a jour la bdd
            if(isset($requette_user['id_user'])) {
                $requette = $DB->prepare("UPDATE account WHERE id_user = ?");

                $requette ->execute(array($requette_user['id_user']));
                // On enregistre dans une session les données de l'utilisateur connecté
                $_SESSION['id_user'] = $requette_user['id_user'];
                $_SESSION['nom'] = $requette_user['nom'];
                $_SESSION['prenom'] = $requette_user['prenom'];
                $_SESSION['identifiant'] = $requette_user['identifiant'];
                $_SESSION['question'] = $requette_user['question'];
                $_SESSION['reponse'] = $requette_user['reponse'];
                // On redirige l'utilisateur vers la page d'accueil
                header('Location: accueil.php');
                exit;
            } else {
                $valid = false;
                $err_idmdp = "L'identifiant ou/et le mot de passe sont incorrect";
            }
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
        <link rel="stylesheet" href="CSS/connexion.css">
        <title>GBAF</title>
        <link rel="shortcut icon" type="image/png" href="IMG/faviconGBAF.ico"/>
    </head>
    <header>
    <?php include('headerconnexion.php'); ?>
    </header>
    <body>
        <section class="conteneurImage">
            <img class='logoOpacity' src="IMG/logoGBAF.jpg" alt="Logo GBAF">
            <form action="connexion.php" method="post">
                <h1>Connexion</h1><br>
                <div class="erreur">
                    <?php if(isset($err_pseudo)) { echo $err_pseudo; } ?>
                </div> 
                <label for="identifiant">Identifiant :</label><br>
                    <div class="erreur">
                        <?php if(isset($err_identifiant)) { echo $err_identifiant; } ?>
                    </div>          
                    <input type="text" name="identifiant"><br>
                <label for="mdp">Mot de passe :</label><br>
                    <div class="erreur">
                        <?php if(isset($err_mdp)) { echo $err_mdp; } ?>
                    </div> 
                    <input type="password" name="mdp"><br>
                <div class="button">
                    <button type="submit" name="connexion">Connexion</button>
                    <button><a href="inscription.php">Inscription</a></button>
                </div>
                <a href="forgotpassword.php">Mot de passe oublié ?</a>
            </form>
        </section>
    </body>
    <hr>
    <footer>
    <?php include('footer.php'); ?>
    </footer>
</html>