<?php 
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');

if(isset($_POST['valider'])) {
// Si les champs "email" et "post" sont bien remplis
    if(!empty($_POST['email']) && !empty($_POST['post'])) {
        extract($_POST);
        $post = htmlspecialchars($post);
        $emailUtilisateur = htmlspecialchars($email);
        // Verification que l'email existe bien
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $requete = $DB->prepare("INSERT INTO contact(email, post) VALUES (?, ?)");
            $requete->execute(array($emailUtilisateur, $post));
            $emailSend = "Votre demande de contact a été envoyé";
        } else {
            $badEmail = "Cet email n'est pas valide !";
        }

    } else {
            $err_com = "Votre commentaire est vide !";
            $err_email = "Veuillez indiquer votre email !";
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
            <?php include('header.php'); ?>
        </header>
        <section class="conteneurImage">
            <img class='logoOpacity' src="IMG/logoGBAF.jpg" alt="Logo GBAF">
            <form action="contact.php" method="post">
                <h1>Contact</h1><br>
                <label for="email">E-mail :</label><br>
                    <div class="erreur">
                        <?php if(isset($badEmail)) { echo $badEmail; } ?>
                        <?php if(isset($err_email)) { echo $err_email; } ?>
                    </div> 
                    <input type="email" name="email"><br>
                <label for="post">Commentaire :</label><br>
                    <div class="erreur">
                        <?php if(isset($err_com)) { echo $err_com; } ?>
                    </div> 
                    <textarea name="post"></textarea><br>
                <div class="button">
                    <button type="submit" name="valider">Valider</button>
                </div>
                <?php if(isset($emailSend)) { echo $emailSend; } ?>
            </form>
        </section>
        <hr>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>