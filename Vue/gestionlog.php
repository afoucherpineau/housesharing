<?php
$entete = entete("Mon site / Gestion");
$menu = menu("gestion");
if (isset($_SESSION["iduser"])){
    if (admin($_SESSION["iduser"])=='1'){
        $sousmenu = sousmenu("gestionlog");
        $contenu = "<br/><br/>";
        $contenu .= supprlog();
        $contenu .= afficherlog();
        $contenu .= "<br/>";
    } else {
        $contenu = "Vous n'êtes pas administrateur. Vous ne pouvez donc pas accéder à cette page.";
    }

} else {
    $contenu .= "Vous n'êtes pas connectés ! Connectez vous avant.";
}

$pied = pied();

include 'gabarit.php';


// Afficher l'ensemble des utilisateurs sous forme de tableau
function afficherlog(){
    ob_start();
    ?>

    <?php
    require ('Modele/connexion.php');
    $q=$bdd->query("SELECT idappart, adresse, ville, identifiant FROM appart INNER JOIN user ON appart.iduser = user.iduser");
    $ligne = $q-> fetch(); ?>

    <br/>
    <fieldset id='infocon'>
    <legend>Liste des Logements</legend>
    <table>
        <tr>
            <td>ID du logement</td>
            <td>Adresse</td>
            <td>Ville</td>
            <td>Propriétaire</td>
        </tr>
    <?php do { ?>
        <tr>
            <td><?php echo $ligne['idappart'];?></td>
            <td><?php echo $ligne['adresse'];?></td>
            <td><?php echo $ligne['ville'];?></td>
            <td><?php echo $ligne['identifiant'];?></td>
        </tr>
        <?php $ligne = $q-> fetch();
    } while (!empty($ligne)); ?>
    </table>
    </fieldset>
    <?php
    $afficherlog=ob_get_clean();
    return$afficherlog;
}

// supprimer un utilisateur
function supprlog(){
    ob_start();
    require ('Modele/connexion.php');
    if (empty($_POST)){
        echo formloge();
    } else {
        $idappart = htmlspecialchars($_POST["suppr"],ENT_QUOTES);
        $q=$bdd->prepare("DELETE FROM appart WHERE idappart='$idappart'");
        $q -> execute();
        $r=$bdd->prepare("DELETE FROM com WHERE idappart='$idappart'");
        $r -> execute();
        echo formloge();
        echo "Le logement a été supprimé.<br/><br/>";
    }
    $suppruser=ob_get_clean();
    return $suppruser;
}

// Formulaire de suppression
function formloge(){
    ob_start();
    require ('Modele/connexion.php');
    $q=$bdd->query("SELECT idappart, adresse, ville, identifiant FROM appart INNER JOIN user ON appart.iduser = user.iduser");
    $ligne = $q-> fetch(); ?>
    <fieldset id='infocon'>
    <legend>Supprimer un logement</legend>
        <form method="post">
            <div class="middle">
            <div class="centre_colonne">
                <label for="suppr">Selectionner le logement à supprimer :</label><br />
                <select name="suppr">
                <?php do {?>
                <option value=<?php echo $ligne['idappart']; ?>><?php echo($ligne['idappart']." ; ".$ligne['adresse']." à ".$ligne['ville']." dont le propriétaire est ".$ligne['identifiant']." "); ?></option>
                <?php
                $ligne = $q-> fetch();
                } while (!empty($ligne)); ?>
                </select>

                <br/>
            <input type="submit" name="Valider" value="Supprimer"/><br/>
            </div>
            </div>
        </form>
    </fieldset>
    <?php
    $formuser=ob_get_clean();
    return$formuser;
}