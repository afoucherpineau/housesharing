<?php 
    session_start();
    require("Modele/connexion.php");
    require("Vue/commun.php");
    
        if(isset($_GET['cible'])) { // on regarde la page où il veut aller
            if($_GET['cible'] == 'accueil'){
                include("Vue/accueil.php");
            } else if ($_GET['cible'] == "recherche"){
                include("Vue/recherche.php");
            } else if ($_GET['cible'] == "inscruser"){
                include("Vue/inscruser.php");
            // mon compte
            } else if ($_GET['cible'] == "moncompte"){
                include("Vue/moncompte.php");
            } else if ($_GET['cible'] == "modicoor"){
                include("Vue/modicoor.php"); 
            } else if ($_GET['cible'] == "modimdp"){
                include("Vue/modimdp.php"); 
            // messagerie
            } else if ($_GET['cible'] == "mesmess"){
                include("Vue/mesmess.php");
            } else if ($_GET['cible'] == "mess_envoyer"){
                include("Vue/mess_envoyer.php");
            } else if ($_GET['cible'] == "mess_lire"){
                if (isset($_GET['id_message'])){
                    $_SESSION['idmess']=$_GET['id_message'];
                }
                include("Vue/mess_lire.php");  
            } else if ($_GET['cible'] == "mess_suppr"){
                if (isset($_GET['id_message'])){
                    $_SESSION['idmess']=$_GET['id_message'];
                }
                include("Vue/mess_suppr.php");  
            } else if ($_GET['cible'] == "meslog"){
                include("Vue/meslog.php");
            // Vue logement et commentaires
            } else if ($_GET['cible'] == "vuelog"){
                if (isset($_GET['idlog'])){
                    $_SESSION['idlog']=$_GET['idlog'];
                }
                include("Vue/vuelog.php");
            } else if ($_GET['cible'] == "ajoutphotos"){
                if (isset($_GET['idlog'])){
                    $_SESSION['idlog']=$_GET['idlog'];
                }
                include("Vue/ajoutphotos.php");
            } else if ($_GET['cible'] == "com"){
                if (isset($_GET['idlog'])){
                    if (!empty($_GET["idlog"])) {
                        $_SESSION['idlog']=$_GET['idlog'];
                    }
                }
                include("Vue/com.php");
            } else if ($_GET['cible'] == "inscrlog"){
                include("Vue/inscrlog.php");
            // Gestion
            } else if ($_GET['cible'] == "gestion"){
                include("Vue/gestion.php");
            } else if ($_GET['cible'] == "gestionuser"){
                include("Vue/gestionuser.php");
            } else if ($_GET['cible'] == "gestionlog"){
                include("Vue/gestionlog.php");
            } else if ($_GET['cible'] == "gestionforum"){
                include("Vue/gestionforum.php");
            } else if ($_GET['cible'] == "contact"){
                include("Vue/contact.php");
            // forum
            } else if ($_GET['cible'] == "forum"){
                include("Vue/forum.php");
            } else if ($_GET['cible'] == "forum_poster"){
                if (isset($_GET['action'])&&!empty($_GET["action"])){
                        $_SESSION['action']=$_GET['action'];
                }
                if (isset($_GET["f"])&&!empty($_GET["f"])){
                        $_SESSION['f']=$_GET['f'];
                } else if(isset($_GET["f"])){
                    $_SESSION['f']='0';
                }
                if (isset($_GET["t"])&&!empty($_GET["t"])){
                        $_SESSION['t']=$_GET["t"];
                } else if(isset($_GET["t"])){
                    $_SESSION['t']='0';
                }
                if (isset($_GET["p"])&&!empty($_GET["p"])){
                        $_SESSION['p']=$_GET["p"];
                } else if(isset($_GET["p"])){
                    $_SESSION['p']='0';
                }
                include("Vue/forum_poster.php");
            } else if ($_GET['cible'] == "forum_postok"){
                if (isset($_GET['action'])&&!empty($_GET["action"])){
                        $_SESSION['action']=$_GET['action'];
                }
                if (isset($_GET["f"])&&!empty($_GET["f"])){
                        $_SESSION['f']=$_GET["f"];
                } else if(isset($_GET["f"])){
                    $_SESSION['f']='0';
                }
                include("Vue/forum_postok.php");
            } else if ($_GET['cible'] == "forum_voirtopic"){
                if (isset($_GET["t"])&&!empty($_GET["t"])){
                        $_SESSION['t']=$_GET["t"];
                } else if(isset($_GET["t"])){
                    $_SESSION['t']='0';
                }
                if (isset($_GET["page"])&&!empty($_GET["page"])){
                        $_SESSION['page']=$_GET["page"];
                } else if(isset($_GET["page"])){
                    $_SESSION['page']='0';
                }
                include("Vue/forum_voirtopic.php");
            // connexion et déconnection
            } else if ($_GET['cible'] == "verif"){
                include("Controleur/connexion.php");
            }
            else if ($_GET['cible'] == "deco"){
                session_destroy();
                $_SESSION["iduser"]=NULL;
                include("Vue/accueil.php");
            } else {
                include("Vue/accueil.php");
            }
        } else { // affichage par défaut
                include("Vue/accueil.php");
        }