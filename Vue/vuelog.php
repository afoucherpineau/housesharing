<?php
   
    $entete = entete("Mon site / Mes Logements");
    $menu = menu("recherche");
    $idlog=$_SESSION['idlog'];
    $contenu = modif($idlog);
    $contenu .= fichelog($idlog);
    $pied = pied();

    include 'gabarit.php';
    
    function modif($idlog){
        ob_start();
        
        require ('Modele/connexion.php');
        global $error;
        $error = array();
        $rempli = array();
        
        if (!empty($_POST)){
                if (empty($_POST['adresse'])) {
                        $error["ville"] = "1";
                        echo "Vous avez supprimé l'adresse. Vos modifications n'ont pas pûes être enregistrées.";
                    } 
                    if (empty($_POST['ville'])) {
                            $error["ville"] = "1";
                            echo "Vous avez supprimé la ville. Vos modifications n'ont pas pûes être enregistrées.";
                    } 
                    if (empty($_POST['taille'])) {
                            $error["taille"] = "1";
                            echo "Vous avez supprimé la taille. Vos modifications n'ont pas pûes être enregistrées.";
                    }
                    if (empty($_POST['desc'])) {
                        $error["desc"] = "1";
                        echo "Vous avez supprimé la description. Vos modifications n'ont pas pûes être enregistrées.";
                    } 
                    if(count($error)>0) {
                        
                    }
                    if(count($error)==0) { // Insertion du formulaire ds la bdd
                        if (isset($_POST['demande'])) {
                        $adresse = htmlspecialchars($_POST['adresse'],ENT_QUOTES);
                        $ville = strtolower(htmlspecialchars($_POST['ville'],ENT_QUOTES));
                        $taille = htmlspecialchars($_POST['taille'],ENT_QUOTES);
                        $nombrepiece = htmlspecialchars($_POST['nombrepiece'],ENT_QUOTES);
                        $nbrepers = htmlspecialchars($_POST['nbrepers'],ENT_QUOTES);
                        $desc = htmlspecialchars($_POST['desc'],ENT_QUOTES);
                        $demande = htmlspecialchars($_POST['demande'],ENT_QUOTES);
                    $req = $bdd->prepare("UPDATE appart SET adresse='$adresse', ville='$ville', taille='$taille', nombrepiece='$nombrepiece', nbrepers='$nbrepers', description='$desc', demande='$demande' WHERE idappart='$idlog'");
                    $req -> execute();
                    echo 'Vos modifications ont bien étées enregistrées' ;
                        }
                    }
            }
        $modif=ob_get_clean();
        return $modif;
    }
    
    function fichelog($idlog){
        ob_start();
        
        require ('Modele/connexion.php');
        
        $q=$bdd->query("SELECT adresse, ville, taille, nombrepiece, nbrepers, description, demande, identifiant FROM appart INNER JOIN user ON appart.iduser=user.iduser WHERE idappart='$idlog'");
        $ligne = $q-> fetch();

        ?>
        <fieldset id='infocon'>
        <legend>Logement de <?php echo($ligne['identifiant']); ?></legend>
            <h2>Ville : </h2><?php echo($ligne['ville']); ?> <br/>
            <h2>Adresse : </h2><?php echo($ligne['adresse']); ?> <br/>
            <h2>Taille : </h2><?php echo($ligne['taille']); ?> m²<br/>
            <h2>Nombre de pièces : </h2><?php echo($ligne['nombrepiece']); ?> <br/>
            <h2>Nombre de personnes pouvant être accueillies : </h2><?php echo($ligne['nbrepers']); ?> <br/>
            <h2>Description : </h2><?php echo($ligne['description']); ?> <br/>
            <?php
            if (!empty($ligne['demande'])){ ?>
                <h2>Demandes et contraintes : </h2><?php echo($ligne['demande']); ?> <br/>
            <?php }
            $s=$bdd->query("SELECT note FROM com WHERE idappart='$idlog'");
            $moyenne = $s-> fetch();
            $i=0;
            $somme=0;
            while (!empty($moyenne)){
                $i++;
                $somme = $somme + $moyenne['note'];
                $moyenne = $s-> fetch();
            }
            
            if ($i!=0){
                $note = $somme/$i;
                echo "<h2>Moyenne des notes : </h2>".$note."/5<br/>";
            } else {
                echo "<br/>Ce logement n'a jamais été noté.";
            }
         ?>
        </fieldset> <br/>
        
        <fieldset id='infocon'>
        <legend>Commentaires</legend>
        <?php
                // Ajout d'un commentaire
        $identifiant = $ligne['identifiant'];
        $r=$bdd->query("SELECT iduser FROM user WHERE identifiant='$identifiant'");
        $user = $r-> fetch();
        if (isset($_SESSION['iduser'])&&$_SESSION['iduser']!=$user['iduser']){
            echo "Cliquez <a href='index.php?cible=com&idlog=".$idlog."'>ici</a> pour noter ce logement.<br/>";
        }
        
        $req=$bdd->query("SELECT identifiant, texte, note FROM com INNER JOIN user ON com.iduser=user.iduser WHERE idappart='$idlog'");
        $com = $req-> fetch();
        while (!empty($com)){
        ?>
        <br/>
            <h2>Commentaire de <?php echo($com['identifiant']); ?></h2>
            <?php
            $note = $com['note'];
            for ($i=0; $i<5; $i++){
                if ($note<5){
                    echo '<img src="./images/smileys/etoilenoire.gif" />';
                    $note++;
                } else {
                    echo '<img src="./images/smileys/etoile.gif" />';
                    $note++;
                }
            }
            ?>
            <br/><?php echo($com['texte']); ?><br/>
            
        <?php
            $com = $req-> fetch();
        }
        ?>
        </fieldset><br/> 
        <?php
        $fichelog=ob_get_clean();
        return $fichelog;
    }
?>