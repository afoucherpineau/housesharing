<?php
    if (empty($sousmenucompte)){
        $entete = entete("Mon site / Mes Logements");
        $menu = menu("moncompte");
        if (isset($_SESSION["iduser"])){
            $sousmenu = sousmenu("meslog");
            $contenu = "<h1>Mes logements</h1><br/><a href='index.php?cible=inscrlog'>Ajouter un nouveau logement</a><br/><br/>";
            $contenu .= afficherlog($_SESSION["iduser"]);
        } else {
            $contenu= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
        $pied = pied();
    }
    
    include 'gabarit.php';
    
    function afficherlog($iduser){
        ob_start();
            // Connexion à la bd
            require ('Modele/connexion.php');

                    if(empty($_POST)) {
                        echo(selectmeslog($iduser));
                    }
                    if (!empty($_POST)) {
                            echo(selectmeslog($iduser));
                            $idappart = htmlspecialchars($_POST['appart'],ENT_QUOTES);
                            $q=$bdd->query("SELECT * FROM appart WHERE idappart='$idappart'");
                            $ligne = $q-> fetch();

            ?>
            <fieldset id='infocon'>
                <form method="post" action="index.php?cible=vuelog&idlog=<?php echo($idappart); ?>"> <!--<form method="post" action="reception.php" enctype="multipart/form-data">-->
        <!-- Ville -->
        <label for="ville">Ville : </label><br/>
        <!-- Ajout du champ prérempli -->
            <input type="text" name="ville" value="<?php echo ($ligne["ville"]);?>"><br />

        <!-- Adresse -->
        <label for="adresse">Adresse : </label><br/>
        <!-- Ajout du champ prérempli -->
            <input type="text" name="adresse" value="<?php echo ($ligne["adresse"]);?>"><br />

        <!-- Taille -->
        <label for="taille">Taille (en m²) : </label><br />
        <!-- Ajout du champ prérempli -->
            <input type="text" name="taille" value="<?php echo ($ligne["taille"]);?>"><br />

        <label for="nombrepiece">Nombre de pièces : </label><br />
            <select name="nombrepiece">
            <?php
            if ($ligne["nombrepiece"]!=$i){
                echo "<option value='1'>1</option>";
            } else {
                echo "<option value='1' selected='selected'>1</option>";
            }
            for ($i=2; $i<=15; $i++){
                if ($i<15){
                    if (isset($ligne["nombrepiece"])&&$ligne["nombrepiece"]==$i){
                        echo "<option value='".$i."' selected='selected'>".$i."</option>";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>";
                    }
                } else if ($i=15) {
                    if (isset($ligne["nombrepiece"])&&$ligne["nombrepiece"]==15){
                        echo "<option value='15' selected='selected'>15 ou +</option>";
                    } else {
                        echo "<option value='15'>15 ou +</option>";
                    }
                }
            }
            ?>
            </select> <br /><br />
            
            <label for="nbrepers">Nombre de personnes pouvant être accueillies : </label><br />
            <select name="nbrepers">
            <?php
            if (isset($ligne["nbrepers"])&&$ligne["nbrepers"]!=$i){
                echo "<option value='1'>1</option>";
            } else {
                echo "<option value='1' selected='selected'>1</option>";
            }
            for ($i=2; $i<=15; $i++){
                if ($i<15){
                    if (isset($ligne["nbrepers"])&&$ligne["nbrepers"]==$i){
                        echo "<option value='".$i."' selected='selected'>".$i."</option>";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>";
                    }
                } else if ($i=15) {
                    if (isset($ligne["nbrepers"])&&$ligne["nbrepers"]==15){
                        echo "<option value='15' selected='selected'>15 ou +</option>";
                    } else {
                        echo "<option value='15'>15 ou +</option>";
                    }
                }
            }
            ?>
            </select> <br /><br />
        
        <label for="desc">
        <!-- Description -->
        <label for="desc">Description : </label></br>
        <!-- Ajout du champ prérempli -->
            <textarea name="desc" rows="8" cols="45" ><?php echo $ligne["description"];?></textarea> <br />
        
        <label for="demande">Demandes et contraintes : </label></br>
        <!-- Ajout du champ prérempli -->
        <?php if(isset($ligne["demande"])){ ?>
            <textarea name="demande" rows="8" cols="45"><?php echo $ligne["demande"];?></textarea> <br />
        <?php }else{ ?>
            <textarea name="demande" rows="8" cols="45"></textarea> <br />
        <?php } ?>

        <input type="submit" value="Valider*" />
        <br/>* en cliquant sur ce bouton, vous allez être redirigé vers la page de votre logement.
        </fieldset>
            <?php
                    }

        $afficherlog=ob_get_clean();
        return $afficherlog;
    }


    function selectmeslog($iduser){
        ob_start();
            require ('Modele/connexion.php');

            //$iduser=$_SESSION["iduser"];
            $qLog=$bdd->query("SELECT * FROM appart WHERE iduser='$iduser'");
            $ligneLog = $qLog-> fetch();
            ?>

            <form method="post">
            <div class="middle">
            <div class="centre_colonne">
            <?php
            if (isset($ligneLog['idappart']) && !empty($ligneLog['idappart'])){
            ?>
                    <label for="appart">Selectionner votre appartement pour le voir :</label><br />
                    <select name="appart">
                    <!-- On crée une boucle permettant d'afficher tous les lieux -->
                    <?php
                    do {?>
                    <option value=<?php echo($ligneLog['idappart']); ?>><?php echo($ligneLog['ville']." > ".$ligneLog['adresse']); ?></option>
                    <?php
                    $ligneLog = $qLog-> fetch();
                    } while (!empty($ligneLog)); ?>
                    </select>

                    <br/>

                    <input type="submit" name="valider" value="valider" /><br/>
            <?php } ?>
            </div>
            </div>
            </form>

            <?php


        $selectmeslog =  ob_get_clean();
        return $selectmeslog;
    }


?>