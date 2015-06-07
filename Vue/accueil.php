<?php
    $entete = entete("Mon site / Accueil");
    $menu = menu("accueil");
    $contenu = carousel();
    $pied = pied();

    include 'gabarit.php';
    
    // Base du carousel
    function carousel(){
        ob_start();

        require ('Modele/connexion.php');

        $q=$bdd->query("SELECT idappart FROM appart ORDER BY idappart desc");
        $ligne = $q-> fetch();

        ?>
            <div class="container">
                            <div id="ca-container" class="ca-container">
                                    <div class="ca-wrapper">
                                            <div class="ca-item ca-item-1">
                                                    <?php
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-2">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-3">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-4">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-5">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-6">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-7">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                            <div class="ca-item ca-item-8">
                                                    <?php
                                                    $ligne = $q-> fetch();
                                                    $idappart=$ligne['idappart'];
                                                    echo contenucarousel($idappart); ?>
                                            </div>
                                    </div>
                            </div>
                    </div>
                    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>-->
                    <script type="text/javascript" src="js/jquery.min.js"></script>
                    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
                    <!-- the jScrollPane script -->
                    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
                    <script type="text/javascript" src="js/jquery.contentcarousel.js"></script>
                    <script type="text/javascript">
                            $('#ca-container').contentcarousel();
                    </script>
        <?php
        $carousel = ob_get_clean();
        return $carousel;
    }

        // Contenu du carousel lié à la base de donnée
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
        <div class="ca-item-main">
                <div class="ca-icon"></div>
                <h3><?php echo($ville); ?></h3>
                <h4>
                        <span><?php echo($taille." m²<br/>".$nombrepiece." pièces<br/>Peut accueillir ".$nbrepers." personnes."); ?></span>
                </h4>
                        <a href="#" class="ca-more">more...</a>
        </div>
        <div class="ca-content-wrapper">
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
                        <a href="#" class="ca-close">close</a>
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
                        <a href="#" class="ca-close">close</a>
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
        <?php
        $contenucarousel = ob_get_clean();
        return $contenucarousel;
    }

?>
