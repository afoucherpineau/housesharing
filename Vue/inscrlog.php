<?php
    $entete = entete("Mon site / Ajouter un logement");
    $menu = menu("moncompte");
    $sousmenu = sousmenu("meslog");
    $contenu = ajoutlog();
    $pied = pied();

    include 'gabarit.php';
    function ajoutlog(){
        ob_start();
        ?>
        <h1>Inscription d'un nouveau logement :</h1>
        <?php

            //Connexion à la base de donnée
            require ('Modele/connexion.php');

            //Vérifier si le formulaire a été envoyé
            if(empty($_POST)) {
                    echo(formlog());

            } else {
                    // Vérifier qu'il n'y a pas d'erreurs
                    global $error;
                    global $rempli;
                    $error = array();
                    $rempli = array();
                    if (empty($_POST['adresse'])) {
                            $error["adresse"] = "Il n'y a pas d'adresse";
                            //echo ($error['adresse']);
                    } 
                    if (empty($_POST['ville'])) {
                            $error["ville"] = "Il n'y a pas de ville";
                            //echo ($error["ville"]);
                    } 
                    if (empty($_POST['taille'])) {
                            $error["taille"] = "Vous n'avez pas précisé la taille";
                    }
                    if (empty($_POST['desc'])) {
                            $error["desc"] = "Il faut rajouter une description";
                    } 
                    if(count($error)>0) {

                        $rempli["adresse"] = htmlspecialchars($_POST['adresse'],ENT_QUOTES);
                        $rempli["ville"] = strtolower(htmlspecialchars($_POST['ville'],ENT_QUOTES));
                        $rempli["taille"] = htmlspecialchars($_POST['taille'],ENT_QUOTES);
                        $rempli["nbrepiece"] = htmlspecialchars($_POST['nombrepiece'],ENT_QUOTES);
                        $rempli["personne"] = htmlspecialchars($_POST['personne'],ENT_QUOTES);
                        $rempli["desc"] = htmlspecialchars($_POST['desc'],ENT_QUOTES);
                        $rempli["demande"] = htmlspecialchars($_POST['desc'],ENT_QUOTES);
                        echo(formlog());
                    }
                    if(count($error)==0) { // Insertion du formulaire ds la bdd
                        if (isset($_POST['demande'])&&!empty($_POST['demande'])) {
                    $req = $bdd->prepare('INSERT INTO appart(adresse, ville, taille, nombrepiece, nbrepers, description, demande, iduser) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
                    $req->execute(array(
                            htmlspecialchars($_POST['adresse'],ENT_QUOTES), 
                            strtolower(htmlspecialchars($_POST['ville'],ENT_QUOTES)), 
                            htmlspecialchars($_POST['taille'],ENT_QUOTES), 
                            htmlspecialchars($_POST['nombrepiece'],ENT_QUOTES),
                            htmlspecialchars($_POST['personne'],ENT_QUOTES),
                            htmlspecialchars($_POST['desc'],ENT_QUOTES),
                            htmlspecialchars($_POST['demande'],ENT_QUOTES),
                            htmlspecialchars($_SESSION['iduser'],ENT_QUOTES)
                            ));
                    echo 'Votre appartement a bien été enregistré' ;
                        } else {
                            $req = $bdd->prepare('INSERT INTO appart(adresse, ville, taille, nombrepiece, nbrepers, description, iduser) VALUES(?, ?, ?, ?, ?, ?, ?)');
                    $req->execute(array(
                            htmlspecialchars($_POST['adresse'],ENT_QUOTES), 
                            htmlspecialchars($_POST['ville'],ENT_QUOTES), 
                            htmlspecialchars($_POST['taille'],ENT_QUOTES), 
                            htmlspecialchars($_POST['nombrepiece'],ENT_QUOTES),
                            htmlspecialchars($_POST['personne'],ENT_QUOTES),
                            htmlspecialchars($_POST['desc'],ENT_QUOTES),
                            htmlspecialchars($_SESSION['iduser'],ENT_QUOTES)
                            ));
                    echo 'Votre appartement a bien été enregistré.<br/>Pour ajouter une demande ultérieurement, modifiez le logement dans l\'espage Mon compte > Mes logements.' ;
                        }
                    }

                    


            }

        $ajoutlog = ob_get_clean();
        return $ajoutlog;
    }
  

    function formlog(){
        ob_start();
        global $error;
        global $rempli;

        require ('Modele/connexion.php');

        ?>
        <form method="post" action="index.php?cible=inscrlog">
        <!-- Adresse -->
        <!-- Vérification erreur -->
        <fieldset id="infoglog">
            <legend>Informations Générales</legend>
            <?php if (isset($error["adresse"])&&!empty($error["adresse"])) { ?> <!-- si il y a une erreur et que la variable error associée à adresse existe -->
            <div class="error"><?php echo $error["adresse"] ?></div> <!-- affiche l'erreur -->
        <?php } ?>

        <label for="adresse">Adresse :</label><br/>
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["adresse"])){ ?>
            <input type="text" name="adresse" value="<?php echo ($rempli["adresse"]);?>"><br />
        <?php }else{ ?>
            <input type="text" name="adresse"><br />
        <?php } ?>

        <!-- Ville -->
        <!-- Vérification erreur -->
        <?php if (isset($error["ville"])&&!empty($error["ville"])) { ?> <!-- si il y a une erreur et que la variable error associée à ville existe -->   
            <div class="error"><?php echo $error["ville"]; ?></div> <!-- affiche l'erreur -->
        <?php } ?>

        <label for="ville">Ville :</label><br/>
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["ville"])){ ?>
            <input type="text" name="ville" value="<?php echo ($rempli["ville"]);?>"><br />
        <?php }else{ ?>
            <input type="text" name="ville"><br />
        <?php } ?>

        <!-- Taille -->
        <!-- Vérification erreur -->
        <?php if (isset($error["taille"])&&!empty($error["taille"])) { ?> <!-- si il y a une erreur et que la variable error associée à taille existe -->   
            <div class="error"><?php echo $error["taille"]; ?></div> <!-- affiche l'erreur -->
        <?php } ?>

        <label for="taille">Taille du logement (en m²) :</label><br />
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["taille"])){ ?>
            <input type="text" name="taille" value="<?php echo ($rempli["taille"]);?>"><br />
        <?php }else{ ?>
            <input type="text" name="taille"><br />
        <?php } ?>

        <label for="nombrepiece">Combien de pièces y a-t-il dans votre appartement ?</label><br />
            <select name="nombrepiece">
            <?php
            if (isset($rempli["nbrepiece"])&&$rempli["nbrepiece"]!=1){
                echo "<option value='1'>1</option>";
            } else {
                echo "<option value='1' selected='selected'>1</option>";
            }
            for ($i=2; $i<=15; $i++){
                if ($i<15){
                    if (isset($rempli["nbrepiece"])&&$rempli["nbrepiece"]==$i){
                        echo "<option value='".$i."' selected='selected'>".$i."</option>";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>";
                    }
                } else if ($i=15) {
                    if (isset($rempli["nbrepiece"])&&$rempli["nbrepiece"]==15){
                        echo "<option value='15' selected='selected'>15 ou +</option>";
                    } else {
                        echo "<option value='15'>15 ou +</option>";
                    }
                }
            }
            ?>
            </select> <br /><br />
            
            <label for="personne">Combien de personnes pouvez-vous acceuillir ?</label><br />
            <select name="personne">
            <?php
            if (isset($rempli["personne"])&&$rempli["personne"]!=$i){
                echo "<option value='1'>1</option>";
            } else {
                echo "<option value='1' selected='selected'>1</option>";
            }
            for ($i=2; $i<=15; $i++){
                if ($i<15){
                    if (isset($rempli["personne"])&&$rempli["personne"]==$i){
                        echo "<option value='".$i."' selected='selected'>".$i."</option>";
                    } else {
                        echo "<option value='".$i."'>".$i."</option>";
                    }
                } else if ($i=15) {
                    if (isset($rempli["personne"])&&$rempli["personne"]==15){
                        echo "<option value='15' selected='selected'>15 ou +</option>";
                    } else {
                        echo "<option value='15'>15 ou +</option>";
                    }
                }
            }
            ?>
            </select> <br /><br />
        </fieldset>
        
        <fieldset id='infosup'><legend>Informations Supplémentaires</legend>
        <label for="desc">
        <!-- Description -->
        <!-- Vérification erreur -->
        <?php if (isset($error["desc"])&&!empty($error["desc"])) { ?> <!-- si il y a une erreur et que la variable error associée à desc existe -->   
            <div class="error"><?php echo $error["desc"]; ?></div> <!-- affiche l'erreur -->
        <?php } ?>
        
        <label for="desc">Description du logement :</label></br>
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["desc"])){ ?>
            <textarea name="desc" rows="8" cols="45" ><?php echo $rempli["desc"];?></textarea> <br />
        <?php }else{ ?>
            <textarea name="desc" rows="8" cols="45"></textarea> <br />
        <?php } ?>
        
        <label for="demande">Contraintes et demandes :</label></br>
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["demande"])){ ?>
            <textarea name="demande" rows="8" cols="45"><?php echo $rempli["demande"];?></textarea> <br />
        <?php }else{ ?>
            <textarea name="demande" rows="8" cols="45"></textarea> <br />
        <?php } ?>

        <!--
        <p>Autorisez vous dans votre logement :</p>

        Les fumeurs
        <input type="radio" name="fumeurs" value="oui" id="oui" checked="checked" /> <label for="oui">Oui</label>
        <input type="radio" name="fumeurs" value="non" id="non" /> <label for="non">Non</label><br />

        Les animaux
        <input type="radio" name="animaux" value="oui" id="oui" checked="checked" /> <label for="oui">Oui</label>
        <input type="radio" name="animaux" value="non" id="non" /> <label for="non">Non</label><br />

        Les enfants
        <input type="radio" name="enfants" value="oui" id="oui" checked="checked" /> <label for="oui">Oui</label>
        <input type="radio" name="enfants" value="non" id="non" /> <label for="non">Non</label><br /></br>
        <label for="photo1">Ajoutez une photo du logement :</label><br />-->
        <!--<input type="file" name="photo1" id="photo1"><br /><br />-->
        
        <input type="submit" value="Valider" />
        </fieldset>
        </form>

        <?php
        $formlog = ob_get_clean();
        return $formlog;
    }
?>