<?php
    $entete = entete("Mon site / Forum");
    $menu = menu("forum");
    $contenu = postok();
    $pied = pied();

    include 'gabarit.php';

function postok(){
    ob_start();
    
$titre="Poster";
//Connexion à la base de donnée
require ('Modele/connexion.php');
include ("Controleur/forum_debut.php");

$action = $_SESSION['action'];
if (isset($_SESSION['f'])){
    $f = $_SESSION['f'];
}
//On récupère la valeur d'action
$action = (isset($_SESSION['action']))?htmlspecialchars($_SESSION['action']):'';

// Erreur si le membre n'est pas co
if ($id==0) {
    echo erreur(ERR_IS_CO);
} else {
?>
<?php
switch($action)
{
    //Premier cas : nouveau topic
    case "nouveautopic":
    if (!empty($_POST)){
    $message = $_POST['message'];
    $mess = $_POST['mess'];
    $titre = $_POST['titre'];
    $forum = (int) $_SESSION['f'];
    $temps = time();}
    else {
    $message = "";
    $mess = "";
    $titre = "";
    $forum = "";
    $temps = "";
    }

    if (empty($message) || empty($titre))
    {
        echo'<p>Votre message ou votre titre est vide, 
        cliquez <a href="index.php?cible=forum_poster&action=nouveautopic&amp;f='.$forum.'">ici</a> pour recommencer</p>';
    }
    else //Si jamais le message n'est pas vide
    {
        //On entre le topic dans la base de donnée
        $query=$bdd->prepare('INSERT INTO forum_topic
        (forum_id, topic_titre, topic_createur, topic_vu, topic_time, topic_genre)
        VALUES(:forum, :titre, :id, 1, :temps, :mess)');
        $query->bindValue(':forum', $forum, PDO::PARAM_INT);
        $query->bindValue(':titre', $titre, PDO::PARAM_STR);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':temps', $temps, PDO::PARAM_INT);
        $query->bindValue(':mess', $mess, PDO::PARAM_STR);
        $query->execute();
        $nouveautopic = $bdd->lastInsertId(); 
        $query->CloseCursor(); 

        //Puis on entre le message
        $query=$bdd->prepare('INSERT INTO forum_post
        (post_createur, post_texte, post_time, topic_id, post_forum_id)
        VALUES (:id, :mess, :temps, :nouveautopic, :forum)');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':mess', $message, PDO::PARAM_STR);
        $query->bindValue(':temps', $temps,PDO::PARAM_INT);
        $query->bindValue(':nouveautopic', (int) $nouveautopic, PDO::PARAM_INT);
        $query->bindValue(':forum', $forum, PDO::PARAM_INT);
        $query->execute();
        $nouveaupost = $bdd->lastInsertId(); 
        $query->CloseCursor(); 


        //On update la valeur de topic_last_post et de topic_first_post
        $query=$bdd->prepare('UPDATE forum_topic
        SET topic_last_post = :nouveaupost,
        topic_first_post = :nouveaupost
        WHERE topic_id = :nouveautopic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);    
        $query->bindValue(':nouveautopic', (int) $nouveautopic, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
        
        //Un
        echo'<p>Votre message a bien été ajouté!<br /><br />Cliquez <a href="index.php?cible=forum">ici</a> pour revenir à l index du forum<br />
        Cliquez <a href="index.php?cible=forum_voirtopic&t='.$nouveautopic.'">ici</a> pour le voir</p>';
    }
    break; 
    
    //Deuxième cas : répondre
    case "repondre":
    $message = $_POST['message'];
    $topic = (int) $_SESSION['t'];
    $temps = time();

    if (empty($message))
    {
        echo'<p>Votre message est vide, cliquez <a href="index.php?cible=forum_poster&action=repondre&amp;t='.$topic.'">ici</a> pour recommencer</p>';
    }
    else //Sinon, si le message n'est pas vide
    {

        //On récupère l'id du forum
        $query=$bdd->prepare('SELECT forum_id, topic_post FROM forum_topic WHERE topic_id = :topic');
        $query->bindValue(':topic', $topic, PDO::PARAM_INT);    
        $query->execute();
        $data=$query->fetch();
        $forum = $data['forum_id'];

        //On entre le message
        $query=$bdd->prepare('INSERT INTO forum_post
        (post_createur, post_texte, post_time, topic_id, post_forum_id)
        VALUES(:id,:mess,:temps,:topic,:forum)');
        $query->bindValue(':id', $id, PDO::PARAM_INT);   
        $query->bindValue(':mess', $message, PDO::PARAM_STR);  
        $query->bindValue(':temps', $temps, PDO::PARAM_INT);  
        $query->bindValue(':topic', $topic, PDO::PARAM_INT);   
        $query->bindValue(':forum', $forum, PDO::PARAM_INT); 
        $query->execute();
        $nouveaupost = $bdd->lastInsertId();
        $query->CloseCursor(); 

        //On change la table forum_topic
        $query=$bdd->prepare('UPDATE forum_topic SET topic_post = topic_post + 1, topic_last_post = :nouveaupost WHERE topic_id =:topic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);   
        $query->bindValue(':topic', (int) $topic, PDO::PARAM_INT); 
        $query->execute();
        $query->CloseCursor(); 

        //Un message
        $nombreDeMessagesParPage = 15;
        $nbr_post = $data['topic_post']+1;
        $page = ceil($nbr_post / $nombreDeMessagesParPage);
        echo'<p>Votre message a bien été ajouté!<br /><br />
        Cliquez <a href="index.php?cible=forum">ici</a> pour revenir à l index du forum<br />
        Cliquez <a href="index.php?cible=forum_voirtopic&t='.$topic.'&amp;page='.$page.'#p_'.$nouveaupost.'">ici</a> pour le voir</p>';
    }
    break;

    default;
    echo'<p>Cette action est impossible</p>';
} 
?>
</div>
<?php
}
    $postok = ob_get_clean();
    return $postok;
}