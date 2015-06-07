<?php
    $entete = entete("Mon site / Modifier mon mot de passe");
    $menu = menu('moncompte');
    if (isset($_SESSION["iduser"])){
        $sousmenu = sousmenu("mesinfos");
        $contenu = "<br/><br/>";
        $contenu .= modimdp($_SESSION["iduser"]);
    } else {
        $contenu = "Vous n'êtes pas administrateur. Vous ne pouvez donc pas accéder à cette page.";
    }
    $pied = pied();

    include 'gabarit.php';
    
function modimdp($iduser){
    ob_start();
    // Connexion à la base de données
    require ('Modele/connexion.php');

    if(empty($_POST)) {
            echo(formmdp());
    }
    else{

        // Vérifier qu'il n'y a pas d'erreurs
            global $error;
            $error = array();
        // Erreurs sur les mots de passe
        if (empty($_POST['mdp'])) {
                $error["mdp"] = "Vous n'avez pas écrit l'ancien mot de passe.";
        } else {
        include("Modele/utilisateurs.php");

        $reponse = mdp($bdd,$_SESSION['identifiant']);
        $ligne = $reponse->fetch();
        $mdp = sha1($_POST['mdp']);
        if($mdp!=$ligne['mdp']){ 
             $error["mdp"] = "Ce mot de passe n'est pas valide.";
        }
        }
        if (empty($_POST['nmdp'])||empty($_POST['nmdp2'])) {
                $error["nmdp"] = "Vous n'avez pas écrit le nouveau mot de passe.";
        } else {
            if ($_POST['nmdp2']!=$_POST['nmdp']) {
                    $error["nmdp2"] = "Les deux mots de passe ne sont pas égaux.";
            } }
            
            
        if (count($error)>0){
            echo formmdp();
            }
            if (count($error)==0){
                $nmdp = sha1($_POST['nmdp']);
                $iduser = $_SESSION["iduser"];
                $bdd->exec("UPDATE user SET mdp='".$nmdp."' WHERE iduser='".$iduser."'");
                echo 'Vos modifications ont bien étées enregistrées' ;
            }
    }
    $modimdp = ob_get_clean();
    return $modimdp;
}

function formmdp(){
    ob_start();
        global $error;
        ?>
<fieldset id='infocon'>
    <legend> Modifier le mot de passe</legend>
        <form method="post">
            <div>
            <!-- Ancien mot de passe -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['mdp'])&&!empty($error['mdp'])){
                echo "<div class='error'>".$error['mdp']."</div>";
            } ?>
            <label for="mdp"><strong>Mot de passe actuel :</strong></label>
            <input type="password" name="mdp" id="pass"/>
            </div>
            
            <div>
            <!-- Nouveau mot de passe -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['nmdp'])&&!empty($error['nmdp'])){
                echo "<div class='error'>".$error['nmdp']."</div>";
            } ?>
            <label for="nmdp"><strong>Nouveau mot de passe :</strong></label>
            <input type="password" name="nmdp" id="pass"/>
            </div>
           
            <div>
            <!-- Nouveau mot de passe 2 -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['nmdp2'])&&!empty($error['nmdp2'])){
                echo "<div class='error'>".$error['nmdp2']."</div>";
            } ?>
            <label for="nmdp2"><strong>Réecrire le nouveau mot de passe :</strong></label>
            <input type="password" name="nmdp2" id="pass2"/>
            </div>
            
            <div id='boutonins'>
        <br/><input type="submit" name="register" value="S'inscrire"/>
            </div>
        </form>
</fieldset>
    <?php
    $formmdp = ob_get_clean();
    return $formmdp;
}