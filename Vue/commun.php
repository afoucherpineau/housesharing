<?php

// Génère le code HTML du formulaire de connexion
function formulaire(){
    ob_start();
    ?>
        <fieldset id='formulaire'>
            <div >
                <form method="POST" action="index.php?cible=verif">
                    <div> 
                    <!--<br/>-->
                    <input type="text" name="identifiant" placeholder="Identifiant"/></div>
                    
                    <div>
                    <input type="password" name="mdp" placeholder="Password"/></div>
                    <div><input type="submit" name="register" value="Se Connecter"/>
                    <a id="redinscri" href="index.php?cible=inscruser">Pas encore inscrit ?</a>
                    </div>
                </form>
            </div>
        </fieldset>
    <?php
    $formulaire = ob_get_clean();
    return $formulaire;
}

// Génère le code HTML de l'entête
function entete($titre){
    ob_start();
    ?>
        <h1>
            <?php echo($titre);?>
        </h1>
            <?php echo('url(../images/banniere_haut.png)') ?>
    <?php
    $entete = ob_get_clean();
    return $entete;
}

// Génère le code HTML du menu
// le lien associé à l'étape courante est mis en gras
function menu($etape){
    ob_start();
    ?>
        <ul class="menu">
            <fieldset id='stylemenu'>
            <?php 
                if($etape=="accueil"){
                    echo('<li><a href="index.php?cible=accueil"><span class="selection">Accueil</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=accueil">Accueil</a></li>');
                }
                
                if($etape=="recherche"){
                    echo('<li><a href="index.php?cible=recherche"><span class="selection">Recherche</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=recherche">Recherche</a></li>');
                }
                
                if($etape=="moncompte"){
                    echo('<li><a href="index.php?cible=moncompte"><span class="selection">Mon Compte</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=moncompte">Mon Compte</a></li>');
                }
                
                if($etape=="forum"){
                    echo('<li><a href="index.php?cible=forum"><span class="selection">Forum</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=forum">Forum</a></li>');
                }
                
                if (isset($_SESSION["iduser"])){
                    if (admin($_SESSION["iduser"])=='1'){
                        if($etape=="gestion"){
                            echo('<li><a href="index.php?cible=gestion"><span class="selection">Gestion</span></a></li>');
                        } else {
                            echo('<li><a href="index.php?cible=gestion">Gestion</a></li>');
                        }
                    }
                }
            ?>
            </fieldset>    
        </ul>
    <?php
    $menu = ob_get_clean();
    return $menu;
}

// Génère le code HTML du pied de page
// même code pour toutes les pages
function pied(){
    ob_start();
    ?>
<div >
    Site entièrement gratuit !
</div>
    <?php
    $pied = ob_get_clean();
    return $pied;
}


function sousmenu($sousetape){
    ob_start();
    ?>
        <ul id='stylesmenu'>
            <?php 
            if ($sousetape=="mesinfos"||$sousetape=="meslog"||$sousetape=="mesmess"){
                if($sousetape=="mesinfos"){
                    echo('<li><a href="index.php?cible=moncompte"><span class="selection">Mes informations personnelles</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=moncompte">Mes informations personnelles</a></li>');
                }
                
                if($sousetape=="meslog"){
                    echo('<li><a href="index.php?cible=meslog"><span class="selection">Mes logements</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=meslog">Mes logements</a></li>');
                }
                
                if($sousetape=="mesmess"){
                    echo('<li><a href="index.php?cible=mesmess"><span class="selection">Ma messagerie</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=mesmess">Ma messagerie</a></li>');
                }
            }
            if ($sousetape=="gestion"||$sousetape=="gestionuser"||$sousetape=="gestionlog"||$sousetape=="gestionforum"){
                if($sousetape=="gestion"){
                    echo('<li><a href="index.php?cible=gestion"><span class="selection">Gestion</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=gestion">Gestion</a></li>');
                }
                
                if($sousetape=="gestionuser"){
                    echo('<li><a href="index.php?cible=gestionuser"><span class="selection">Gestion des utilisateurs</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=gestionuser">Gestion des utilisateurs</a></li>');
                }
                
                if($sousetape=="gestionlog"){
                    echo('<li><a href="index.php?cible=gestionlog"><span class="selection">Gestion des logements</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=gestionlog">Gestion des logements</a></li>');
                }
                if($sousetape=="gestionforum"){
                    echo('<li><a href="index.php?cible=gestionforum"><span class="selection">Gestion du forum</span></a></li>');
                } else {
                    echo('<li><a href="index.php?cible=gestionforum">Gestion du forum</a></li>');
                }
            }
            ?>
        </ul>
    <?php
    $menu = ob_get_clean();
    return $menu;
}

function admin($iduser){
    ob_start();
    require ('Modele/connexion.php');
    $q=$bdd->query("SELECT * FROM user WHERE iduser='$iduser'");
    $ligne = $q-> fetch();
    if ($ligne['admin']=='1'){
        echo '1';
    }
    
    $admin = ob_get_clean();
    return $admin;
}
