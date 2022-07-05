<?php 
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');
// Si l'utilisateur n'est pas connecté il est redirigé sur la page de connexion
if(!isset($_SESSION['id_user'])) {
    header('Location: connexion.php');
    exit;
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
            <?php
            // Afficher les acteurs
            $id_acteur = $_GET['id'];
            // On récupere toutes les données de la table acteur en fonction de l'id
            $requete = $DB->prepare("SELECT * 
            FROM acteur 
            WHERE id_acteur = $id_acteur" );
            $requete ->execute();
            $acteurs = $requete->fetchAll();
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
            <div class="conteneurNewComment">
                <?php
                $requete = $DB->prepare("SELECT post FROM post WHERE id_acteur = ?");
                $requete->execute(array($id_acteur));
                $post = $requete->rowCount();
                ?>
                <span class="titreMobile>"><h2><?= $post; ?> commentaires</h2></span>
                <div class="divNewComment">
                    <p><a href="#newcomment" class="newComment">Nouveau commentaire</a></p>
                </div>
                <?php
                // Quand l'utilisateur clique sur "Like"
                if(isset($_POST['like'])) {
                    $requete = $DB->prepare("SELECT * FROM vote WHERE id_user = ? AND id_acteur = ?");
                    $requete->execute(array($_SESSION['id_user'], $id_acteur));
                    $requete = $requete->fetch();
                    // Si il y a un "Like" dans la bdd
                    if($requete && $requete['vote'] == 1) {
                        // L'utilisateur ne peut pas liké plusieurs fois
                        $errVote = "Vous avez déjà voté pour cet acteur !";
                    // Si il y a un "Dislike" dans la bdd alors on met a jour    
                    } elseif($requete && $requete['vote'] == 0) {
                        $id_user = $_SESSION['id_user'];
                        $like = 1;
                        $requete = $DB->prepare("UPDATE vote SET vote = ? WHERE id_user = ? AND id_acteur = ?");
                        $requete ->execute(array($like, $_SESSION['id_user'], $id_acteur));
                    // Si il n'y a ni "Like" ni "Dislike" dans la bdd alors on ajoute son "Like"
                    } else {
                        extract($_POST);
                        $id_user = $_SESSION['id_user'];
                        $vote = 1;
                        $requete = $DB->prepare("INSERT INTO vote(id_user, id_acteur, vote) 
                        VALUES (?, ?, ?)");
                        $requete ->execute(array($id_user, $id_acteur, $vote));
                    }
                // Meme code ici quand l'utilisateur cliaue sur "Dislike"
                } elseif(isset($_POST['dislike'])) {
                    $requete = $DB->prepare("SELECT * FROM vote WHERE id_user = ? AND id_acteur = ?");
                    $requete->execute(array($_SESSION['id_user'], $id_acteur));
                    $requete = $requete->fetch();
                    if($requete && $requete['vote'] == 0) {
                        $errVote = "Vous avez déjà voté pour cet acteur !";
                    } elseif($requete && $requete['vote'] == 1) {
                        $id_user = $_SESSION['id_user'];
                        $dislike = 0;
                        $requete = $DB->prepare("UPDATE vote SET vote = ? WHERE id_user = ? AND id_acteur = ?");
                        $requete ->execute(array($dislike, $_SESSION['id_user'], $id_acteur));
                    } else {
                        extract($_POST);
                        $id_user = $_SESSION['id_user'];
                        $vote = 0;
                        $requete = $DB->prepare("INSERT INTO vote(id_user, id_acteur, vote) 
                        VALUES (?, ?, ?)");
                        $requete ->execute(array($id_user, $id_acteur, $vote));
                    }
                }
                ?>
                <div class="conteneurLike">
                    <form action="acteurs.php?id=<?= $_GET['id'] ?>" method="post">
                        <!-- On remplace le style du bouton "Envoyer" par une image -->
                        <button type="submit" class="likeButton" name="like"><img class="likeImage" src="IMG/like.png">
                            <?php
                                $like = 1;
                                // Afficher le nombre de "Like" pour chaque acteur
                                $requete = $DB->prepare("SELECT vote FROM vote WHERE vote = ? AND id_acteur = ?");
                                $requete->execute(array($like, $id_acteur));
                                $votes = $requete->rowCount();
                                echo $votes;
                            ?>
                        </button>
                        <button type="submit" class="dislikeButton" name="dislike"><img class="likeImage" src="IMG/dislike.png">
                            <?php
                                $dislike = 0;
                                $requete = $DB->prepare("SELECT vote FROM vote WHERE vote = ? AND id_acteur = ?");
                                $requete->execute(array($dislike, $id_acteur));
                                $votes = $requete->rowCount();
                                echo $votes;
                            ?>
                        </button>
                    </form>
                </div>
                <br>
            </div>
            <!-- Si l'utilisateur a déjà liké ou disliké on affiche un message d'erreur -->
            <div class="erreur">
                <?php if (isset($errVote)) { echo $errVote; } ?>
            </div>
        <div id="newcomment">
            <article class="acteur">
                <div class="conteneurDescriptionBoutton">
                    <?php
                    // Lorsque l'utilisateur valide le formulaire
                    if(isset($_POST['valider'])) {
                        // Vérification que le commentaire n'est pas vide
                        if(empty($_POST['post'])) {
                            $errVide = "Votre commentaire est vide !";
                        // Vérification que l'utilisateur n'a pas déjà commenté
                        } else {
                            // Selectionne l'id_user correspondant a l'id_user de la session pour cet acteur
                            $requete = $DB->prepare("SELECT id_user, id_acteur FROM post WHERE id_user = ? AND id_acteur = ?");
                            $requete->execute(array($_SESSION['id_user'], $id_acteur));
                            // fetch et non pas fetchAll car on veut une valeur
                            $requete = $requete->fetch();
                            // Si la requete est vrai c'est que l'utilisateur a déjà commenté
                            if($requete) {
                            $errUser = "Vous avez déjà commenté pour cet acteur !";
                            // Si il n'a jamais commenté, on ajoute son commentaire a la bdd
                            } else {
                                // Récupere les données
                                extract($_POST);
                                $id_user = $_SESSION['id_user'];
                                $date_add = date("y.m.d");
                                $post = htmlspecialchars($post);
                                // Ajout a la base de donnée
                                $requete = $DB->prepare("INSERT INTO post(id_user, id_acteur, date_add, post) 
                                VALUES (?, ?, ?, ?)");
                                $requete ->execute(array($id_user, $id_acteur, $date_add, $post));
                            }
                        }
                    }
                    ?>
                    <div class="erreur">
                        <?php if (isset($errVide)) { echo $errVide; } ?>
                        <?php if (isset($errUser)) { echo $errUser; } ?>
                    </div><br>
                    <form action="acteurs.php?id=<?= $_GET['id'] ?>" method="post">
                        <label for="post">Commentaire :</label>
                            <textarea name="post"></textarea><br>
                        <div class="button">
                            <button type="submit" name="valider">Valider</button>
                        </div>
                    </form>
                </div>
        </div>
            </article>
        </section>
        <section class="conteneurActeurs">
                    
                    <?php
                    // Afficher les commentaires lié aux utilisateurs et aux acteurs | INNER JOIN permet de lié plusieurs tables |
                    $requete = $DB->prepare("SELECT post.*, prenom, id_acteur FROM post  
                    INNER JOIN account  
                    ON account.id_user = post.id_user
                    WHERE id_acteur = ?");
                    // On veux récupérer les commentaires des utilisateurs qui sont lié a cet acteur. Pour cela on précise l'id de l'acteur
                    $requete->execute([$id_acteur]);
                    $req_post = $requete->fetchAll();
                        foreach($req_post as $commentaire) {
                    ?>
                    <article class="acteur">
                        <div class="conteneurDescriptionBoutton">
                            <span class="gras">
                                <?= $commentaire['prenom']; ?>
                            </span>
                            <br>
                            <span class="gras">   
                                <?= $commentaire['date_add']; ?>
                            </span>
                            <br>
                            <br>
                            <?= $commentaire['post']; ?>
                        </div>
                    </article>
                    <br>
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