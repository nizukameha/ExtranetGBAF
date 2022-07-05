<div class="conteneurHeader">
    <a href="index.php"><img class='logo' src="IMG/logoGBAF.jpg" alt="Logo GBAF"></a>
    <div class="user&icon">
        <p class="utilisateur">
            <a href="parametre.php"><img class='parametre' src="IMG/user.png" alt="parametre"></a>
            <?php if(isset($_SESSION['id_user'])) {
                $connected_user = "$_SESSION[nom] $_SESSION[prenom]";
                echo $connected_user;
            } else {
                $disconnected_user = "Vous n'êtes pas connecté";
                echo $disconnected_user;
            }?>
        </p>
        <a href="deconnexion.php"><img class='deconnexion' src="IMG/deconnexion.png" alt="deconnexion"></a>
    </div>
</div>
<hr>