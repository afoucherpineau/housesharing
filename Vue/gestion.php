<?php
        $entete = entete("Mon site / Gestion");
        $menu = menu("gestion");
        if (isset($_SESSION["iduser"])){
            if (admin($_SESSION["iduser"])=='1'){
                $sousmenu = sousmenu("gestion");
                $contenu = gestionadmin();
            } else {
                $contenu = "Vous n'êtes pas administrateur. Vous ne pouvez donc pas accéder à cette page.";
            }

        } else {
            $contenu .= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
        
        $pied = pied();

    include 'gabarit.php';
    
?>
<script type="text/javascript">
    function visibilite() {
            if (document.getElementById('text').style.display =='none'){
                    (document.getElementById('text').style.display ='')
            } else {
                    (document.getElementById('text').style.display ='none')
            }
    }

    function changtext() {
            if (document.getElementById('button').value ==='Enlever un administrateur'){
                    (document.getElementById('button').value ='Cacher cette option')
            } else {
                    (document.getElementById('button').value ='Enlever un administrateur')
            }
    }
    $(function() {

        $('#user').autocomplete({
            source: '/Housesharing2/Controleur/autocompletion_admin.php',
            
        });
    });
</script>

<?php

function gestionadmin(){
    ob_start();
    require ('Modele/connexion.php');
    echo "<br/>";
    
    // On vérifie si un administrateur n'a pas été enlevé
    if (!empty($_POST)) {
            if (!empty($_POST['admin']) && $_POST['admin']!=$_SESSION["iduser"]) {
                $iduser = htmlspecialchars($_POST["admin"],ENT_QUOTES);
                $q=$bdd->prepare("UPDATE user SET admin='NULL' WHERE iduser='$iduser'");
                $q -> execute();
                echo "L'administrateur a été enlevé.<br/><br/>";
            } else if (!empty($_POST['admin']) && $_POST['admin']==$_SESSION["iduser"]){
                echo "Vous ne pouvez pas vous enlever vous-même des administrateurs.</br><br/>";
            }
    }
    
    // On vérifie si un administrateur a été ajouté
    if (!empty($_POST)&&!empty($_POST['user'])) {
        
        $identifiant = htmlspecialchars($_POST["user"],ENT_QUOTES);
        $q=$bdd->query("UPDATE user SET admin='1' WHERE identifiant='$identifiant'");
        echo "Vous avez ajouté l'utilisateur ".$_POST['user']." en tant qu'administrateur.<br/><br/>";
    }
    
    // Tableau des administrateurs
    $q=$bdd->query("SELECT * FROM user WHERE admin='1'");
    $ligne = $q-> fetch();
    ?>
    <fieldset id='infocon'>
    <legend>Liste des administrateurs</legend>
    <table>
        <tr>
            <td>Prénom</td>
            <td>Nom</td>
        </tr>
    <?php do { ?>
        <tr>
            <td><?php echo $ligne['prenom'];?></td>
            <td><?php echo $ligne['nom'];?></td>
        </tr>
        <?php $ligne = $q-> fetch();
    } while (!empty($ligne)); ?>
    </table>

    <!-- Enlever ou ajouter un administrateur -->
    <br />
    <br/>
    <input type="button" onclick="javascript:visibilite();changtext()" value="Enlever un administrateur" id="button" />
    <div id="text" style="display:none;">
    <?php
    echo(formenlever());
    ?>
    </div>
    </fieldset>

    <?php
    if (empty($_POST)){
        echo formajout();
    }
    if (!empty($_POST)&&empty($_POST['user'])){
        echo "<br/><br/>Vous n'avez rien écrit.";
        echo formajout();
    }
    if (!empty($_POST)&&!empty($_POST['user'])) {
            echo formajout();
    }
    $listeadmin=ob_get_clean();
    return $listeadmin;
}


function formenlever(){
    ob_start();
    require ('Modele/connexion.php');

    $q=$bdd->query("SELECT * FROM user WHERE admin='1'");
    $ligne = $q-> fetch();
    ?>
        <form method="post">
            <div class="middle">
            <div class="centre_colonne">
                <label for="admin">Selectionner l'administrateur à enlever :</label><br />
                <select name="admin">
                <?php do {?>
                <option value=<?php echo $ligne['iduser']; ?>><?php echo($ligne['prenom']." ".$ligne['nom']." ; Mail : ".$ligne['mail']." ; ID : ".$ligne['iduser']." "); ?></option>
                <?php
                $ligne = $q-> fetch();
                } while (!empty($ligne)); ?>
                </select>

                <br/>
            <input type="submit" name="valider" /><br/>
            </div>
            </div>
        </form>
    <?php
    $formenlever = ob_get_clean();
    return $formenlever;
}


function formajout(){
    ob_start(); ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">    
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <br/>
        <fieldset id='infocon'>
        <legend>Ajouter un administrateur</legend>
        <form method="post">
            Identifiant de l'utilisateur à ajouter : <input type="text" id="user" name="user" />
            <input type="submit" name="Valider"/><br/>
        </form>
        </fieldset>
        
    <?php    
    $formajout = ob_get_clean();
    return $formajout;
}

?>