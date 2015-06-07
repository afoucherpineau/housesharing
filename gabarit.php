<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="styles/base.css" />
	<link rel="stylesheet" type="text/css" href="styles/structure.css" />
        <link rel="stylesheet" type="text/css" href="styles/demo.css" />
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
        <link rel="stylesheet" type="text/css" href="styles/jquery.jscrollpane.css" media="all" />
        <title>
            <?php echo($titre); ?>
        </title>
    </head>
        <div id="global">
            <div id="tete">
                <div id="banniere">
                    <div id="barreconnection">
                        <?php
                        if(!isset($_SESSION["iduser"])){
                            echo formulaire();
                            global $erreur;
                            if(isset($erreur)){
                                echo $erreur;
                            }?>
                            
                            <?php
                        }
                        else {
                        echo($_SESSION['nom']." ".$_SESSION['prenom']);?>
                        <a href="index.php?cible=deco">Se déconnecter</a><?php }?>
                    </div>
                    <div id="menu"><?php
                        
                        echo($menu);
                        if (isset($sousmenu)){
                            echo ($sousmenu);
                        } ?>
                    </div> 
                </div>
            </div> 
            
            <div id="corps">
                <div id="contenu">
                    <?php echo($contenu); ?>
                </div>
            </div>

            <div id="pied">
                <?php echo($pied); ?>
            </div>
        </div>
</html>