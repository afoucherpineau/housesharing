<?php
    	try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=housesharing', 'admin', 'admin');
        }

        catch(Exception $e)
        {
        die('Erreur : '.$e->getMessage());
        }
?>