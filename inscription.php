<?php
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');
// Si l'utilisateur est déja connecté on le redirige sur la page d'accueil
if(isset($_SESSION['id_user'])) {
    header('Location: accueil.php');
    exit;
}

// Condition qui s'applique lorsque que les input du formulaire sont vides
if(!empty($_POST)) {
    // extract permet d'utiliser les 'name' des input
    extract($_POST);
    // Cette variable indique que tous les input sont remplis
    $valid = (boolean) true;

    if(isset($_POST['valider'])) {
        // trim supprime les espaces en début et fin de chaîne 
        // htmlspecialchars empeche d'envoyer du code (en remplaçant certains caracteres) | & = &amp | " = &quot | < = &lt | > = &gt
        $nom = htmlspecialchars(trim($nom));
        $prenom = htmlspecialchars(trim($prenom));
        $identifiant = htmlspecialchars(trim($identifiant));
        $mdp = htmlspecialchars(trim($mdp));
        $question = trim($question);
        $reponse = htmlspecialchars(trim($reponse));

        // Vérification des valeurs entrées par l'utilisateur par rapport a la bdd
        if(empty($nom)) { 
            $valid = false;
        } else {
            // Cherche dans la base de donnée si le nom donnée n'existe pas
            $requette = $DB->prepare("SELECT nom
            FROM account
            WHERE nom = ?");
            // On indique que c'est le nom qui sera envoyer dans la bdd
            $requette ->execute(array($nom));
            // fetch donne le résultat
            $requette = $requette->fetch();
            // Si une colonne de la table 'account' contient la meme donnée alors l'inscription n'est pas validé
            if(isset($requette['nom'])) {
                $valid = false;
                $err_nom = "Ce nom existe déjà";
            }
        }

        if(empty($prenom)) { 
            $valid = false;
        } else {
            $requette = $DB->prepare("SELECT prenom
            FROM account
            WHERE prenom = ?");
            $requette ->execute(array($prenom));
            $requette = $requette->fetch();
            if(isset($requette['prenom'])) {
                $valid = false;
                $err_prenom = "Ce prenom existe déjà";
            }
        }

        if(empty($identifiant)) { 
            $valid = false;
        } else {
            $requette = $DB->prepare("SELECT identifiant
            FROM account
            WHERE identifiant = ?");
            $requette ->execute(array($identifiant));
            $requette = $requette->fetch();
            if(isset($requette['identifiant'])) {
                $valid = false;
                $err_identifiant = "Cet identifiant existe déjà";
            }
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
        
        if($valid) {
            // Fonction pour générer une clé de hash pour le mot de passe
            $crypt_mdp = password_hash($mdp, PASSWORD_BCRYPT);
            // On prépare la requette d'insertion dans la bdd
            $requette = $DB->prepare("INSERT INTO account(nom, prenom, identifiant, mdp, question, reponse) VALUES (?, ?, ?, ?, ?, ?)");
            // On exécute les valeurs présente dans notre tableau
            $requette ->execute(array($nom, $prenom, $identifiant, $crypt_mdp, $question, $reponse));

            // Rediriger le nouvel utilisateur sur la page de connexion
            header('Location: connexion.php');
            exit;
        } else {
            echo "Erreur lors de l'inscription";
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
        <section class="conteneurImage">
            <img class='logoOpacity' src="IMG/logoGBAF.jpg" alt="Logo GBAF">
            <form method="post">
                <h1>Inscription</h1><br>
                <label for="nom">Nom :</label><br>
                <!-- Affiche un message d'erreur si la donnée existe deja dans la bdd -->
                <div class="erreur">
                    <?php if(isset($err_nom)) { echo $err_nom; } ?>
                </div>
                    <input type="text" name="nom" required><br>
                <label for="prenom">Prénom :</label><br>
                <div class="erreur">
                    <?php if(isset($err_prenom)) { echo $err_prenom; } ?>
                </div>            
                    <input type="text" name="prenom" required><br>
                <label for="identifiant">Identifiant :</label><br>
                <div class="erreur">
                    <?php if(isset($err_identifiant)) { echo $err_identifiant; } ?>
                </div>            
                    <input type="text" name="identifiant" required><br>
                <label for="mdp">Mot de passe :</label><br>
                    <input type="password" name="mdp" required><br>
                <label for="question">Question de sécurité :</label><br>
                <select name="question" name="question" required>
                    <option value="choisir">- Choisir une question -</option>
                    <option value="villeParent">Dans quelle ville vos parents se sont-ils rencontrés ?</option>
                    <option value="concert">Quel est le premier concert auquel vous avez assisté ?</option>
                    <option value="villeNaissance">Dans quelle ville êtes-vous né(e) ?</option>
                    <option value="2emePrenom">Quel est le deuxième prénom de votre mère ?</option>
                </select><br>
                <label for="reponse">Réponse :</label><br>             
                    <input type="text" id="reponse" name="reponse" required><br>
                <div class="button">
                    <button type="submit" name="valider">Valider</button> 
                </div>
            </form>
        </section>
    </body>
    <hr>
    <footer>
    <?php include('footer.php'); ?>
    </footer>
</html>