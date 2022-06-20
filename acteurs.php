<?php 
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');
// Si l'utilisateur n'est pas connecté il est redirigé sur la page de connexion
if(!isset($_SESSION['id_user'])) {
    header('Location: connexion.php');
    exit;
}

// Afficher les acteurs
$id_acteur = $_GET['id'];
    // On récupere toutes les données de la table acteur en fonction de l'id
    $requette = $DB->prepare("SELECT * FROM acteur WHERE id_acteur = ?" );
    $requette ->execute(array($_GET['id']));
    $acteurs = $requette->fetchAll();

// Afficher les commentaires lié a l'utilisateur | INNER JOIN permet de lié plusieurs tables | post p permet d'écrire juste p a la place de post
$requette = $DB->prepare("SELECT p.*, nom, prenom FROM post p INNER JOIN account a ON a.id_user = p.id_user WHERE p.id_post = ?" );
// On veux récupérer les commentaires des utilisateurs qui sont lié a cet acteur. Pour cela on précise l'id de l'acteur
$requette->execute(array($_GET['id']));
$req_post = $requette->fetchAll();

// Condition qui s'applique lorsque que les input du formulaire sont vides
if(!empty($_POST)) {
    var_dump($_POST);
    // extract permet d'utiliser les 'name' des input
    extract($_POST);
    // Cette variable indique que tous les input sont remplis
    $valid = (boolean) true;

    if(isset($_POST['valider'])) {
        $post = htmlspecialchars($post);
        $prenom = $_SESSION['prenom'];
        $id_user = $_SESSION['id_user'];
        $date_add = date("d/m/Y");
        

        if(empty($post)) {
            $valid = false;
        }
        
        if($valid) {
            $requette = $DB->prepare("INSERT INTO post (id_user, id_acteur, date_add, post,) VALUES (?, ?, ?, ?)");
            // On exécute les valeurs présente dans notre tableau
            $requette ->execute(array($id_user, $id_acteur, $date_add, $post));
        }
        //header("location: acteurs.php?id=$$acteur_id");
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/acteurs.css">
        <title>GBAF</title>
        <link rel="shortcut icon" type="image/png" href="IMG/faviconGBAF.ico"/>
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>
        <!-- Bloc qui contient tous les acteurs -->
        <section class="conteneurActeurs">
            <h1>Acteurs et partenaires</h1>
            <?php
            // On affiche les acteurs dans une boucle
            foreach ($acteurs as $acteur) {
            ?>
            <article class="acteur">
                <img src="<?php echo $acteur['logo']; ?>" class="logoActeurs" alt="logo">
                <div class="conteneurDescriptionBoutton">
                    <p class="descriptionActeur">
                        <span class="gras">
                            <?= $acteur['acteur']; ?>
                        </span>
                        <?= $acteur['description']; ?>
                    </p>
                </div>
            </article>
            <br>
            <?php
            }
            ?>
            </section>
        <section class="conteneurActeurs">
            <h2>Ajouter un commentaire</h2>
            <article class="acteur">
                <div class="conteneurDescriptionBoutton">
                    <form action="acteurs.php?id=<?= $_GET['id'] ?>" method="post">
                    <label for="post">Commentaire :</label>         
                        <textarea name="post"></textarea><br>
                        <div class="button">
                            <button type="submit" name="valider">Valider</button>
                        </div>
                    </form>
                </div>
            </article>
        </section>
        </section>
        <section class="conteneurActeurs">
            <h2>Commentaires</h2>
            <?php
                foreach($req_post as $commentaire) {
            ?>
            <article class="acteur">
                <div class="conteneurDescriptionBoutton">
                    <span class="gras">
                        <?= $commentaire['prenom']; ?><br>
                        <?= $commentaire['date_add']; ?><br>
                    </span>
                    <?= $commentaire['post']; ?>
                </div>
            </article>
            <?php
                }
            ?>
        </section>
        <hr>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>