<?php
    $entete = entete("Mon site / Modifier un utilisateur");
    $menu = menu('moncompte');
    if (isset($_SESSION["iduser"])){
        $sousmenu = sousmenu("mesinfos");
        $contenu = "<br/><br/>";
        $contenu .= ajoutuser();
    } else {
        $contenu = "Vous n'êtes pas administrateur. Vous ne pouvez donc pas accéder à cette page.";
    }
    $pied = pied();

    include 'gabarit.php';
    
    function ajoutuser(){
        ob_start();
            // Connexion à la base de données
            require ('Modele/connexion.php');

            if(empty($_POST)) {
                    echo(formuser());
            }
            else{
                
                // Vérifier qu'il n'y a pas d'erreurs
                    global $error;
                    global $rempli;
                    $error = array();
                    $rempli = array();
                // Erreurs sur les mots de passe
                if (empty($_POST['mdp'])||empty($_POST['mdp2'])) {
                        $error["mdp"] = "Vous n'avez pas écrit le mot de passe.";
                } else if ($_POST['mdp2']!=$_POST['mdp']) {
                            $error["mdp2"] = "Les deux mots de passe ne sont pas égaux.";
                } else {
                    include("Modele/utilisateurs.php");
                    $iduser = $_SESSION["iduser"];
                    $reponse = $bdd->query('SELECT * FROM user WHERE iduser="'.$iduser.'"');
                    $ligne = $reponse->fetch();
                    if(sha1($_POST['mdp'])!=$ligne['mdp']){ 
                        $error["mdp"] = "Mot de passe incorrect";
                    }
                }
                
                    
                // Erreurs sur le téléphone
                if (empty($_POST['tel'])||empty($_POST['tel'])) {
                        $error["tel"] = "Vous n'avez pas renseigné votre numéro de téléphone.";
                } else {
                    if (preg_match('/^\d{10}$/', $_POST['tel'])==0) {
                        $error["tel"] = "Votre numéro de téléphone est invalide.";
                    }
                }
                if (count($error)>0){
                $rempli['tel']=$_POST['tel'];
                
                echo formuser();
                }
                if (count($error)==0){
                    $tel = htmlspecialchars($_POST['tel'],ENT_QUOTES);
                    $iduser = $_SESSION["iduser"];
                    $bdd->exec("UPDATE user SET telephone='".$tel."' WHERE iduser='".$iduser."'");
                    
                    echo 'Vos modifications ont bien étées enregistrées' ;
                }
            }
        $ajoutuser=ob_get_clean();
        return $ajoutuser;
    }

    function formuser(){
        ob_start();
        global $error;
        global $rempli;
        ?>

        <form method="post"  name="forminsc">
          
            <fieldset id='infocon'>
                <legend> Veuillez réécrire votre mot de passe pour changer votre numéro de téléphone</legend>
                
            <div>
            <!-- Mot de passe -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['mdp'])&&!empty($error['mdp'])){
                echo "<div class='error'>".$error['mdp']."</div>";
            } ?>
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <input type="password" name="mdp" id="pass"/>
            </div>
           
            <div>
            <!-- Mot de passe 2 -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['mdp2'])&&!empty($error['mdp2'])){
                echo "<div class='error'>".$error['mdp2']."</div>";
            } ?>
            <label for="mdp2"><strong>Mot de passe :</strong></label>
            <input type="password" name="mdp2" id="pass2"/>
            </div>

            <div>
            <!-- Téléphone -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['tel'])&&!empty($error['tel'])){
                echo "<div class='error'>".$error['tel']."</div>";
            } ?>
            <label for="tel"><strong>N° telephone :</strong></label>
            <?php if(isset($rempli["tel"])){ ?>
                <input type="text" name="tel" value="<?php echo ($rempli["tel"]);?>"><br />
            <?php }else{ ?>
                <input type="text" name="tel"><br />
            <?php } ?>
            </div>
            </tr>
            </fieldset>
            
            <div id='boutonins'>
        <input type="submit" name="register" value="S'inscrire"/>
            </div>
        </form>

        <?php
        $formuser=ob_get_clean();
        return $formuser;
    }

?>