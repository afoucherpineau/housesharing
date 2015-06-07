<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
<?php
echo (!empty($titre))?'<title>'.$titre.'</title>':'<title> Forum </title>';
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" media="screen" type="text/css" title="Design" href="./css/design.css" />
</head>
<?php
$lvl=(isset($_SESSION['level']))?(int) $_SESSION['level']:1;
$id=(isset($_SESSION['iduser']))?(int) $_SESSION['iduser']:0;
$pseudo=(isset($_SESSION['pseudo']))?$_SESSION['pseudo']:'';

//On inclue les 2 pages restantes
include("Controleur/forum_functions.php");
include("Controleur/forum_constants.php");
?>
