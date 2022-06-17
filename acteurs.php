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
    $requette = $DB->prepare("SELECT * FROM acteur WHERE id_acteur = $id_acteur" );
    $requette ->execute();
    $acteurs = $requette->fetchAll();

// Afficher les commentaires lié a l'utilisateur | INNER JOIN permet de lié plusieurs tables | post p permet d'écrire juste p a la place de post
$requette = $DB->prepare("SELECT p.*, nom, prenom FROM post p INNER JOIN account a ON a.id_user = p.id_user WHERE p.id_post = ?" );
// On veux récupérer les commentaires des utilisateurs qui sont lié a cet acteur. Pour cela on précise l'id de l'acteur
$requette->execute([$id_acteur]);
$req_post = $requette->fetchAll();


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
                    <form action="acteurs.php" method="post">
                    <label for="post">Commentaire :</label>         
                        <input type="text" name="post"><br>
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