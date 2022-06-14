<?php 
include_once('DB/connexionDB.php');

if(!empty($_POST)) {
    extract($_POST);
    $valid = (boolean) true;

    if(isset($_POST['valider'])) {
        $identifiant = htmlspecialchars(trim($identifiant));
        $question = htmlspecialchars($question);
        $reponse = htmlspecialchars($reponse);

        if(empty($identifiant)) {
            $valid = false;
            $err_identifiant = "* Indiquez votre identifiant";
        }
        if(empty($reponse)) {
            $valid = false;
            $err_reponse = "* Indiquez votre réponse";
        }
        // Si l'utilisateur a remplis l'identifiant et la reponse a la question, on vérifie la correpsondance
        if($valid) {
            $requette = $DB->prepare("SELECT reponse
                FROM account
                WHERE identifiant = ?");
            $requette->execute(array($identifiant));
            $requette = $requette->fetch();

            // Si la combinaison identifiant et reponse est n'est pas vrai voici ce qu'il se passe :
            if(isset($requette['reponse'])) {
                // Si l'identifiant est correct mais pas la reponse alors c'est faux
                if($reponse != $requette['reponse']) {
                    $valid = false;
                    $err_pseudo = "* L'identifiant ou/et la réponse sont incorrects";
                }
            // On vérifie la combinaison question/réponse
            } else {
                $valid = false;
                $err_pseudo = "* L'identifiant ou/et la réponse sont incorrects";
            }

            $requette = $DB->prepare("SELECT identifiant
                FROM account
                WHERE reponse = ?");
            $requette->execute(array($reponse));
            $requette = $requette->fetch();
            // Si la combinaison question/réponse est vrai mais pas l'identifiant :
            if($identifiant != $requette['identifiant']) {
                $valid = false;
                $err_pseudo = "* L'identifiant ou/et la réponse sont incorrects";
            } else {
                $valid = false;
            }
        }
        if($valid) {
            header('Location: newpassword.php');
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
            <form action="forgotpassword.php" method="post">
                <h1>Mot de passe oublié</h1><br>          
                <label for="identifiant">Identifiant :</label><br>
                <div class="erreur">
                    <?php if(isset($err_identifiant)) { echo $err_identifiant; } ?>
                </div>
                    <input type="text" id="identifiant" name="identifiant"><br>
                <label for="question">Question de sécurité :</label><br>
                <select name="question" id="question">
                    <option value="choisir">- Choisir une question -</option>
                    <option value="villeParent">Dans quelle ville vos parents se sont-ils rencontrés ?</option>
                    <option value="concert">Quel est le premier concert auquel vous avez assisté ?</option>
                    <option value="villeNaissance">Dans quelle ville êtes-vous né(e) ?</option>
                    <option value="2emePrenom">Quel est le deuxième prénom de votre mère ?</option>
                </select><br>
                <label for="reponse">Réponse :</label><br>             
                    <input type="text" id="reponse" name="reponse"><br>
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