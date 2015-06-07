<?php
    if (empty($sousmenucompte)){
        $entete = entete("Mon site / Mes Logements");
        $menu = menu("moncompte");
        if (isset($_SESSION["iduser"])){
            $sousmenu = sousmenu("meslog");
            $idlog=$_SESSION['idlog'];
            $contenu = ajoutphotos($idlog);
        } else {
            $contenu= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
        $pied = pied();
    }
    
    include 'gabarit.php';
    
    // Merci au groupe G1B qui nous a aidé à réaliser cette fonction
function ajoutphotos($idlog){
    ob_start();
        $target= dirname(__FILE__).'/images/'.$idlog.'/';
        //Définition des tailles maximums
        define('MAX_SIZE', 1500000); 
        define('WIDTH_MAX', 1800); 
        define('HEIGHT_MAX', 1800); 
        // Extensions
        $tabExt = array('jpg','png','jpeg'); //que jpg 
        $infosImg = array();
        $extension = '';
        $message = '';
        $nomImage = '';


        /*if(!is_dir($target)){
           mkdir($target);
        }*/

        if (empty($_POST)){
            echo formphotos();
            
        }
        if(!empty($_POST)) {
            if( !empty($_FILES['fichier']['name']) ) {
                $extension = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION); //Vérification de l'extension
                        if(in_array(strtolower($extension),$tabExt)) {
                                $infosImg = getimagesize($_FILES['fichier']['tmp_name']);
                                        if($infosImg[2] >= 1 && $infosImg[2] <= 14) { //Vérification du type de l'image
                                                if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['fichier']['tmp_name']) <= MAX_SIZE)) { //Vérification de la taille de l'image
                                                        if(isset($_FILES['fichier']['error']) && UPLOAD_ERR_OK === $_FILES['fichier']['error']) {
                                                                $nomImage = $target.'1'.'.'. $extension;//Change le nom 
                                                                if(move_uploaded_file($_FILES['fichier']['tmp_name'], $nomImage)) { //Test de la mise en ligne
                                                                        $message = 'Upload réussi !';$n++; }
                                                                else {
                                                                        $message = 'Problème lors de l\'upload !'; }
                                                        }
                                                        else {
                                                                $message = 'Une erreur interne a empêché l\'uplaod de l\'image'; }
                                                }
                                                else {
                                                        $message = 'Erreur dans les dimensions de l\'image !'; }
                                        }
                                        else {	
                                                $message = 'Le fichier à uploader n\'est pas une image !'; }
                        }
                        else {	
                                $message = 'L\'extension du fichier est incorrecte !'; }
            }
            else {
                $message = 'Veuillez remplir le formulaire svp !'; }
        }
    $ajoutphotos = ob_get_clean();
    return $ajoutphotos;
}

function formphotos(){
    ob_start();
        
    if( !empty($message) ){
            echo '<p>',"\n";
            echo "\t\t<strong>", htmlspecialchars($message) ,"</strong>\n";
            echo "\t</p>\n\n";
            }
            ?>
            <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <!--<form enctype="multipart/form-data" method="post">-->
                <fieldset>
                    <legend>Mettez en ligne vos photos !</legend> 
                        <p>
                            <label for="upload_fichier" title="Recherchez le fichier à uploader !">Envoyer le fichier :</label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_SIZE; ?>" />
                            <input name="fichier" type="file" id="upload_fichier" />
                            <input type="submit" name="submit" value="Uploader" />
                        </p>
                </fieldset>
            </form>
    <?php
    $formphotos=ob_get_clean();
    return $formphotos;
}