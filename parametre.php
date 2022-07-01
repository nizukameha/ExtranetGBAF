<?php 
include_once('DB/connexionDB.php');
// Si l'utilisateur n'est pas connecté il est redirigé sur la page de connexion
if(!isset($_SESSION['id_user'])) {
    header('Location: connexion.php');
    exit;
}
// Requette a la bdd pour récupérer les données de l'utilisateur en fonction de son id
$requette = $DB->prepare("SELECT *
    FROM account 
    WHERE id_user = ?");
// récupere l'id_user de l'utilisateur
$requette->execute([$_SESSION['id_user']]);

$req = $requette->fetch();

// Condition qui s'applique lorsque que les input du formulaire sont vides
if(!empty($_POST)) {
    extract($_POST);
    $valid = (boolean) true;
    // Condition qui s'applique lorsque l'utilisateur clique sur le boutton d'envoie du formulaire
    if(isset($_POST['valider'])) {
        // Le nom sera celui envoyé par l'utilisateur sans espace ni injection de code
        $nom = htmlspecialchars(trim($nom));
        $prenom = htmlspecialchars(trim($prenom));
        $identifiant = htmlspecialchars(trim($identifiant));
        $mdp = htmlspecialchars(trim($mdp));
        $question = trim($question);
        $reponse = htmlspecialchars(trim($reponse));
        // Si l'utilisateur ne rentre aucunes données la modification n'aura pas lieu
        if(empty($nom)) {
            $valid = false;
        }
        if(empty($prenom)) {
            $valid = false;
        }
        if(empty($identifiant)) {
            $valid = false;
        }
        if(empty($mdp)) {
            $valid = false;
        }
        if(empty($question)) {
            $valid = false;
        }
        if(empty($reponse)) {
            $valid = false;
        }
        
        // Si les modifications sont valides on met a jour la bdd et l'utilisateur est redirigé sur la page d'acceuil
        if($valid) {
            $crypt_mdp = password_hash($mdp, PASSWORD_BCRYPT);
            $requette = $DB->prepare("UPDATE account SET nom = ?, prenom = ?, identifiant = ?, mdp = ?, question = ?, reponse = ?
            WHERE id_user = ?");
            $requette->execute(array($nom, $prenom, $identifiant, $crypt_mdp, $question, $reponse, $_SESSION['id_user']));

            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['identifiant'] = $identifiant;
            $_SESSION['question'] = $question;
            $_SESSION['reponse'] = $reponse;

            header('Location: accueil.php');
            exit;
        }
    
    }
}

// Si le nom n'est pas définis par l'utilisateur alors il est = a celui de la bdd
if(!isset($nom)) {
    $nom = $req['nom'];
    $prenom = $req['prenom'];
    $identifiant = $req['identifiant'];
    $reponse = $req['reponse'];
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
            <form method="post">
                <h1>Paramètres du compte</h1><br>
                <label for="nom">Nom :</label><br>             
                    <input type="text" name="nom" value ="<?= $nom ?>" required><br>
                <label for="prenom">Prénom :</label><br>             
                    <input type="text" name="prenom" value ="<?= $prenom ?>" required><br>
                <label for="identifiant">Identifiant :</label><br>             
                    <input type="text" name="identifiant" value ="<?= $identifiant ?>" required><br>
                <label for="password">Mot de passe :</label><br>
                    <input type="password" name="mdp" required><br>
                <label for="question">Question de sécurité :</label><br>
                <select name="question">
                    <option value="choisir">- Choisir une question -</option>
                    <option value="villeParent">Dans quelle ville vos parents se sont-ils rencontrés ?</option>
                    <option value="concert">Quel est le premier concert auquel vous avez assisté ?</option>
                    <option value="villeNaissance">Dans quelle ville êtes-vous né(e) ?</option>
                    <option value="2emePrenom">Quel est le deuxième prénom de votre mère ?</option>
                </select><br>
                <label for="reponse">Réponse :</label><br>             
                <input type="text" name="reponse" value="<?= $reponse ?>" required><br>
                <div class="button">
                    <button type="submit" name="valider">Valider</button> 
                </div>
            </form>
        </section>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>