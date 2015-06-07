<?php
    $entete = entete("Mon site / Recherche");
    $menu = menu("recherche");

    $contenu = "<h1>Recherche</h1>";
    $contenu .= affichage();
    
    $pied = pied();

    include 'gabarit.php';
    
?>
<script type="text/javascript">
    $(function() {

        $('#ville').autocomplete({
            source: '/Housesharing2/Controleur/autocompletion_ville.php',
            
        });
    });
</script>
<?php

   function affichage(){
       ob_start();
        //Connexion à la base de donnée
            require ('Modele/connexion.php');
        
        if (empty($_POST)) {
            echo(formrecherche());
        } else {
            global $rempli;
            $rempli = array();
            $rempli["ville"] = strtolower(htmlspecialchars($_POST['ville'],ENT_QUOTES));
            if (!empty($_POST['taille'])){
                $rempli["taille"] = (int) htmlspecialchars($_POST['taille'],ENT_QUOTES);
            }
            $rempli["nbrepiece"] = htmlspecialchars($_POST['nombrepiece'],ENT_QUOTES);
            $rempli["personne"] = htmlspecialchars($_POST['personne'],ENT_QUOTES);
            echo(formrecherche());
            
            // On définit la requête
            global $req;
            $req="SELECT * FROM appart WHERE ";
            global $i;
            $i=0;
            if (!empty($rempli["ville"])){
                recherche('ville',$rempli["ville"]);
                $i++;
            }
            if (!empty($rempli["taille"])){
                recherche('taille',$rempli["taille"]);
                $i++;
            }
            if (!empty($rempli["nbrepiece"])){
                recherche('nombrepiece',$rempli["nbrepiece"]);
                $i++;
            }
            if (!empty($rempli["personne"])){
                recherche('nbrepers',$rempli["personne"]);
                $i++;
            }
            $q=$bdd->query($req);
            $ligne = $q-> fetch();
            $idappart=$ligne['idappart'];
            if (empty($idappart)){
                echo "Il n'y a aucun appartement correspondant à votre recherche.";
            }
            while (!empty($idappart)){
                echo contenucarousel($idappart);
                $ligne = $q-> fetch();
                $idappart=$ligne['idappart'];
            }
        }
        $affichage=ob_get_clean();
        return $affichage;
    }
   
   
   
    function formrecherche(){
        ob_start();
        global $rempli;

        require ('Modele/connexion.php');

        ?>
        <form method="post">
        <fieldset id="infoglog">
            <legend> Informations Générales</legend>

        <!-- Ville -->
        <label for="ville">Ville :</label><br/>
        <!-- Ajout du champ prérempli -->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">    
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        
        <?php if(isset($rempli["ville"])){ ?>
            <input type="text" id="ville" name="ville" <?php echo ($rempli["ville"]);?>><br />
        <?php }else{ ?>
            <input type="text" id="ville" name="ville" /><br />
        <?php } ?>

        <!-- Taille -->
        <label for="taille">Taille minimum du logement (en m²) :</label><br />
        <!-- Ajout du champ prérempli -->
        <?php if(isset($rempli["taille"])){ ?>
            <input type="text" name="taille" value="<?php echo ($rempli["taille"]);?>"><br />
        <?php }else{ ?>
            <input type="text" name="taille"><br />
        <?php } ?>

        <label for="nombrepiece">Nombre de pièces minimum ?</label><br />
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
            
            <label for="personne">Nombre de personnes minimum ?</label><br />
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
            
            <input type="submit" value="Rechercher" />
        </fieldset>
        </form>

        <?php

        $formrecherche = ob_get_clean();
        return $formrecherche;
    }
    
    function recherche($nomcritere,$critere){
        ob_start();
        global $req;
        global $i;
        // Connexion à la bd
        require ('Modele/connexion.php');
        
        // recherche par critère
        if ($nomcritere=='ville'){
        if ($i==0){
            $req .= $nomcritere."='".$critere."'";
        } else {
            $req .= " AND ".$nomcritere."='".$critere."'";
        }
        } else {
            if ($i==0){
            $req .= "(".$nomcritere.">=".$critere.")";
        } else {
            $req .= " AND (".$nomcritere.">=".$critere.")";
        }
        }
     
        $recherche=ob_get_clean();
        return $recherche;
    }
    
    function contenucarousel($idappart){
        ob_start();
        //Connexion à la base de donnée
        require ('Modele/connexion.php');


            $q=$bdd->query("SELECT * FROM appart WHERE idappart='$idappart'");
            $ligne = $q-> fetch();
            $ville = $ligne['ville'];
            $nombrepiece = $ligne['nombrepiece'];
            $nbrepers = $ligne['nbrepers'];
            $taille = $ligne['taille'];
            $adresse = $ligne['adresse'];


        ?>
        <div class="container">
            <div id="ca-container" class="ca-container">
                <div class="ca-wrapper">
                    <div class="ca-item2 ca-item-1">
                        <div class="ca-item-main">
                                <div class="ca-icon"></div>
                                <h3><?php echo($ville); ?></h3>
                                <h4>
                                        <span><?php echo($taille." m²<br/>".$nombrepiece." pièces<br/>Peut accueillir ".$nbrepers." personnes."); ?></span>
                                </h4>
                        </div>
                        <div class="ca-content-wrapper2">
                                <div class="ca-content">
                                    <!-- Ajouter la description s'il y en a une -->
                                    <?php
                                    if (!empty($ligne['adresse'])){
                                        $adresse=$ligne['adresse']; ?>
                                        <div class="ca-content-text"><p><?php echo "<p>Le logement se situe : ".$adresse." à ".$ligne['ville'].".</p>";?></p></div>
                                    <?php
                                    }
                                    if (!empty($ligne['description'])){ ?>
                                        <h6>Description</h6>
                                        <?php
                                    } else { ?>
                                        <div class="ca-content-text">
                                        <p>Il n'y a pas de description</p>
                                        </div>
                                    <?php } ?>
                                        <div class="ca-content-text">
                                                <?php
                                                if (!empty($ligne['description'])){
                                                    $desc=$ligne['description']; ?>
                                                    <p><?php echo ($desc);?></p>
                                                    <?php
                                                } ?>

                                        </div>

                                    <!-- Ajouter les demandes s'il y en a -->
                                    <?php
                                    if (!empty($ligne['demande'])){ ?>
                                        <h6>Demandes et contraintes</h6>
                                        <?php
                                    } ?>
                                        <div class="ca-content-text">
                                                <?php
                                                if (!empty($ligne['demande'])){
                                                    $demande=$ligne['demande']; ?>
                                                    <p><?php echo ($demande);?></p>
                                                    <?php
                                                } ?>

                                        </div>
                                        <?php
                                        ?>
                                        <ul>
                                                <li><a href="index.php?cible=vuelog&idlog=<?php echo($idappart); ?>">Afficher la page du logement</a></li>
                                        </ul>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $contenucarousel = ob_get_clean();
        return $contenucarousel;
    }
?>
