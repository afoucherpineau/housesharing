<?php

    $entete = entete("Mon site / Commentaire");
    $menu = menu("recherche");
    if (isset($_SESSION["iduser"])){
            $idlog = $_SESSION['idlog'];
            $contenu = fichelog($idlog);
            $contenu .= com($_SESSION["idlog"]);
        } else {
            $contenu= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }

    $pied = pied();

    include 'gabarit.php';


    
    function fichelog($idlog){
        ob_start();
        
        require ('Modele/connexion.php');
        
        $q=$bdd->query("SELECT adresse, ville, taille, nombrepiece, nbrepers, description, demande, identifiant FROM appart INNER JOIN user ON appart.iduser=user.iduser WHERE idappart='$idlog'");
        $ligne = $q-> fetch();

        ?>
        <br/><br/>
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
            <?php } ?>
        </fieldset>
        <br/>
        <?php
        
        $fichelog=ob_get_clean();
        return $fichelog;
    }    
    

function com($idlog){
    ob_start();
    
    require ('Modele/connexion.php');
    
        if (empty($_POST)){
            global $error;
            $error = array();
            // Vérifions qu'un utilisateur n'essaye pas de se noter lui-même
            $r=$bdd->query("SELECT iduser FROM appart WHERE idappart='$idlog'");
            $log = $r-> fetch();
            if ($log['iduser']==$_SESSION["iduser"]) {
                    $error["user"] = "Vous ne pouvez pas vous noter vous-même.";
            }
            
            echo formcom();
        } else {
            
            // Vérifier qu'il n'y a pas d'erreurs
            global $error;
            global $rempli;
            $error = array();
            $rempli = array();
            if (empty($_POST['note'])) {
                    $error["note"] = "Il n'y a pas de note.";
            } 
            if (empty($_POST['texte'])) {
                    $error["texte"] = "Il faut rajouter un commentaire.";
            }
            // Vérifions qu'un utilisateur n'essaye pas de se noter lui-même
            $r=$bdd->query("SELECT iduser FROM appart WHERE idappart='$idlog'");
            $log = $r-> fetch();
            if ($log['iduser']==$_SESSION["iduser"]) {
                    $error["user"] = "Vous ne pouvez pas vous noter vous-même.";
            }
            
            if(count($error)>0) {
                $rempli["texte"] = htmlspecialchars($_POST['texte'],ENT_QUOTES);
                $rempli["note"] = strtoupper(htmlspecialchars($_POST['note'],ENT_QUOTES));
                echo(formcom());
            }
            
            if (count($error==0)) {
                $iduser=$_SESSION["iduser"];
                $req = $bdd->prepare('INSERT INTO com(note, texte, idappart, iduser) VALUES(?, ?, ?, ?)');
                $req->execute(array(
                        htmlspecialchars($_POST['note'],ENT_QUOTES), 
                        htmlspecialchars($_POST['texte'],ENT_QUOTES), 
                        htmlspecialchars($idlog,ENT_QUOTES),
                        htmlspecialchars($iduser,ENT_QUOTES),
                        ));
                
                echo "<fieldset id='infoglog'><legend> Vous avez bien envoyé le commentaire</legend>";
                echo "Votre note : ".$_POST['note'].".<br/>";
                echo "Votre commentaire :<br/>".$_POST['texte']."<br/><br/>";
                echo "Pour retourner sur la fiche du logement, cliquer <a href='index.php?cible=vuelog&idlog=".$idlog."'>ici.<br/></fieldset>";
            }
        }
    $com=ob_get_clean();
    return $com;
}

function formcom(){
    ob_start();
    global $error;
    global $rempli;

    require ('Modele/connexion.php');
    
    if (isset($error["user"])&&!empty($error["user"])) {
        echo $error["user"];
    } else {
    
    ?>
    <fieldset id="infoglog">
        <legend> Ajouter un commentaire</legend>
        
        <form method="post">
        
        <!-- Notation -->
        <!-- Vérification erreur -->
        <?php if (isset($error["note"])&&!empty($error["note"])) { ?> <!-- si il y a une erreur et que la variable error associée à nomE existe -->
            <div class="error"><?php echo $error["note"] ?></div> <!-- affiche l'erreur -->
        <?php } ?>
            
        <label for="note">Quelle note souhaitez-vous mettre ? (/5)</label><br />
            <select name="note">
                <option value="">Noter</option>
            <?php
            for ($i=0; $i<=5; $i++){
                    if (isset($rempli["note"])&&$rempli["note"]==$i){
                        echo "<option value='".$i."' selected='selected'>".$i."</option>";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>";
                    }
            }
            ?>
            </select> <br /><br />
        
        <!-- Commentaire -->
        <!-- Vérification erreur -->
        <?php if (isset($error["texte"])&&!empty($error["texte"])) { ?> <!-- si il y a une erreur et que la variable error associée à nomE existe -->
            <div class="error"><?php echo $error["texte"] ?></div> <!-- affiche l'erreur -->
        <?php } ?>

        <label for="texte">Commentaire :</label><br/>
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["texte"])){ ?>
            <textarea name="texte" rows="8" cols="45"><?php echo $ligne["texte"];?></textarea> <br />
        <?php }else{ ?>
            <textarea name="texte" rows="8" cols="45"></textarea> <br />
        <?php } ?>
        <input type="submit" value="Valider*" />
        <br/>* Attention ! Vous ne pourrez pas changer votre commentaire ultérieurement.
        </form>
    </fieldset>
        <?php 
    }
            
    $formcom = ob_get_clean();
    return $formcom;
}