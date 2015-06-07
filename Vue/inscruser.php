<?php
    $entete = entete("Mon site / Ajouter un Utilisateur");
    $menu = menu('inscription');
    $contenu = ajoutuser();
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
                } else {
                    if ($_POST['mdp2']!=$_POST['mdp']) {
                            $error["mdp2"] = "Les deux mots de passe ne sont pas égaux.";
                    } }
                // Erreurs sur l'identifiant
                if (empty($_POST['identifiant'])) {
                        $error["identifiant"] = "Nous n'avez pas écrit d'identifiant";
                } else {
                    $identifiant= htmlspecialchars($_POST['identifiant'],ENT_QUOTES);
                    $req = $bdd -> query ("SELECT iduser FROM user WHERE identifiant='$identifiant'");
                    $uti = $req -> fetch();
                    if (!empty($uti['iduser'])) {
                        $error['identifiant'] = "L'identifiant est déjà pris";
                    }
                }
                // Erreurs sur le mail
                if (empty($_POST['mail'])) {
                        $error["mail"] = "Nous n'avez pas donné votre adresse mail.";
                } else {
                    if(!empty(VerifierAdresseMail($_POST['mail']))){
                        $error['mail'] = VerifierAdresseMail($_POST['mail']);
                    }
                    $mail=$_POST['mail'];
                    $req = $bdd -> query ("SELECT iduser FROM user WHERE mail='$mail'");
                    $uti = $req -> fetch();
                    if (!empty($uti['iduser'])){
                        $error['mail'] = "Il existe déjà un compte associé à cette adresse.";
                    }
                }
                if (empty($_POST['nom'])||empty($_POST['nom'])) {
                        $error["nom"] = "Vous n'avez pas renseigné votre nom.";
                }
                if (empty($_POST['prenom'])||empty($_POST['prenom'])) {
                        $error["prenom"] = "Vous n'avez pas renseigné votre prénom.";
                }
                if (empty($_POST['tel'])||empty($_POST['tel'])) {
                        $error["tel"] = "Vous n'avez pas renseigné votre numéro de téléphone.";
                } else {
                    if (preg_match('/^\d{10}$/', $_POST['tel'])==0) {
                        $error["tel"] = "Votre numéro de téléphone est invalide.";
                    }
                }
                if (count($error)>0){
                $rempli['identifiant']=$_POST['identifiant'];
                $rempli['mail']=$_POST['mail'];
                $rempli['nom']=$_POST['nom'];
                $rempli['prenom']=$_POST['prenom'];
                $rempli['tel']=$_POST['tel'];
                
                echo formuser();
                }
                if (count($error)==0){
                    $mdp = sha1($_POST['mdp']);
                    $req = $bdd->prepare('INSERT INTO user(identifiant, mdp, mail, telephone, nom, prenom) VALUES(?,?,?,?,?,?)');
                        $req->execute(array(
                        htmlspecialchars($_POST['identifiant'],ENT_QUOTES),
                        $mdp,
                        htmlspecialchars($_POST['mail'],ENT_QUOTES),
                        htmlspecialchars($_POST['tel'],ENT_QUOTES),
                        htmlspecialchars($_POST['nom'],ENT_QUOTES),
                        htmlspecialchars($_POST['prenom'],ENT_QUOTES)
                        ));
                    echo 'Vous êtes désormais inscrits.';
                }

            /*
            ini_set('SMTP','smtp.SFR.fr');
            ini_set('sendmail_from' , 'benjypunky@hotmail.fr');
                // Récupération des variables nécessaires au mail de confirmation	
            $Mail = $_POST['Mail'];
            $pseudo = $_POST['identifiant'];

            // Génération aléatoire d'une clé
            $cle = md5(microtime(TRUE)*100000);*/

            // Insertion de la clé dans la base de données (à adapter en INSERT si besoin)
            //$stmt = $bdd->prepare("UPDATE user SET cle='$cle' WHERE identifiant='$pseudo'");
            //$stmt = $bdd->prepare("UPDATE user SET cle=:cle WHERE identifiant like :pseudo");
            /*$stmt->bindParam(':cle', $cle);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();*/


            /*// Préparation du mail contenant le lien d'activation
            $destinataire = $Mail;
            $sujet = "Activer votre compte" ;
            $entete = "From: benjypunky@hotmail.fr" ;

            // Le lien d'activation est composé du login(log) et de la clé(cle)
            $message = "Bienvenue sur VotreSite,

            Pour activer votre compte, veuillez cliquer sur le lien ci dessous
            ou copier/coller dans votre navigateur internet.

            http://localhost:8000/validation.phplog='.urlencode($pseudo).'&cle='.urlencode($cle).'


            ---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.";


            mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail*/
            }

        $ajoutuser=ob_get_clean();
        return $ajoutuser;
    }

    function formuser(){
        ob_start();
        global $error;
        global $rempli;
        ?>

        <form method="post" action="index.php?cible=inscruser"  name="forminsc">
          
            <fieldset id='infocon'>
                <legend> Informations de connexions</legend>
                
            <div>
            <!-- Identifiant -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['identifiant'])&&!empty($error['identifiant'])){
                echo "<div class='error'>".$error['identifiant']."</div>";
            } ?>
            <label for="identifiant"><strong>Identifiant :</strong></label>
            <!-- Ajout du champ prérempli -->
            <?php if(isset($rempli["identifiant"])){ ?>
                <input type="text" name="identifiant" value="<?php echo ($rempli["identifiant"]);?>"><br />
            <?php }else{ ?>
                <input type="text" name="identifiant"><br />
            <?php } ?>
            </div>
                
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
            <!-- Mail -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['mail'])&&!empty($error['mail'])){
                echo "<div class='error'>".$error['mail']."</div>";
            } ?>
            <td><label for="mail"><strong>Mail :*</strong></label></td>
            <?php if(isset($rempli["mail"])){ ?>
                <input type="text" name="mail" value="<?php echo ($rempli["mail"]);?>"><br />
            <?php }else{ ?>
                <input type="text" name="mail"><br />
            <?php } ?>
            </div>
            </fieldset>

            
            <fieldset id='infopers'>
                <legend>Information personnelle</legend>
           
                <div>
            <!-- Nom -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['nom'])&&!empty($error['nom'])){
                echo "<div class='error'>".$error['nom']."</div>";
            } ?>
            <label for="nom"><strong>Nom :</strong></label>
            <?php if(isset($rempli["nom"])){ ?>
                <input type="text" name="nom" value="<?php echo ($rempli["nom"]);?>"><br />
            <?php }else{ ?>
                <input type="text" name="nom"><br />
            <?php } ?>
            </div>

            <div>
            <!-- Prénom -->
            <!-- Affichage des erreurs -->
            <?php
            if (isset($error['prenom'])&&!empty($error['prenom'])){
                echo "<div class='error'>".$error['prenom']."</div>";
            } ?>
            <label for="prenom"><strong>Prenom :</strong></label>
            <?php if(isset($rempli["prenom"])){ ?>
                <input type="text" name="prenom" value="<?php echo ($rempli["prenom"]);?>"><br />
            <?php }else{ ?>
                <input type="text" name="prenom"><br />
            <?php } ?>
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
    
    
function VerifierAdresseMail($adresse)
{
  //Adresse mail trop longue (254 octets max)
  if(strlen($adresse)>254)
  {
    return 'Votre adresse est trop longue.';
  }


  //Caractères non-ASCII autorisés dans un nom de domaine .eu :

  $nonASCII='ďđēĕėęěĝğġģĥħĩīĭįıĵķĺļľŀłńņňŉŋōŏőoeŕŗřśŝsťŧ';
  $nonASCII.='ďđēĕėęěĝğġģĥħĩīĭįıĵķĺļľŀłńņňŉŋōŏőoeŕŗřśŝsťŧ';
  $nonASCII.='ũūŭůűųŵŷźżztșțΐάέήίΰαβγδεζηθικλμνξοπρςστυφ';
  $nonASCII.='χψωϊϋόύώабвгдежзийклмнопрстуфхцчшщъыьэюяt';
  $nonASCII.='ἀἁἂἃἄἅἆἇἐἑἒἓἔἕἠἡἢἣἤἥἦἧἰἱἲἳἴἵἶἷὀὁὂὃὄὅὐὑὒὓὔ';
  $nonASCII.='ὕὖὗὠὡὢὣὤὥὦὧὰάὲέὴήὶίὸόὺύὼώᾀᾁᾂᾃᾄᾅᾆᾇᾐᾑᾒᾓᾔᾕᾖᾗ';
  $nonASCII.='ᾠᾡᾢᾣᾤᾥᾦᾧᾰᾱᾲᾳᾴᾶᾷῂῃῄῆῇῐῑῒΐῖῗῠῡῢΰῤῥῦῧῲῳῴῶῷ';
  // note : 1 caractète non-ASCII vos 2 octets en UTF-8


  $syntaxe="#^[[:alnum:][:punct:]]{1,64}@[[:alnum:]-.$nonASCII]{2,253}\.[[:alpha:].]{2,6}$#";

  if(!preg_match($syntaxe,$adresse))
  {
    return 'Votre adresse e-mail n\'est pas valide.';
  }
}

?>