<?php
    $entete = entete("Mon site / Forum");
    $menu = menu("forum");
    $contenu = "<h1>Contenu du forum</h1>";
    $contenu .= forum();
    $pied = pied();

    include 'gabarit.php';

    
function forum(){
    ob_start();
    

$titre="Voir un forum";
//Connexion à la base de donnée
require ('Modele/connexion.php');
include ("Controleur/forum_debut.php");
$totaldesmessages = 0;
$categorie = NULL;
$forum = 0;

//Limitation du nombre de messages
$query=$bdd->prepare('SELECT forum_name, forum_topic, auth_view, auth_topic FROM forum_forum WHERE forum_id = :forum');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
$totalDesMessages = $data['forum_topic'] + 1;
$nombreDeMessagesParPage = 4; //Valeur à modifier pour le nombre de post
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

//Nombre de pages/ Fonction trouvée sur le net. Ne fonctionne pas ....
$page = (isset($_GET['page']))?intval($_GET['page']):1;

//Normalement affiche les pages 1-2-3 ...
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page)
    {
    echo $i;
    }
    else
    {
    echo '
    <a href="index.php;page='.$i.'">'.$i.'</a>';
    }
}
echo '</p>';
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;
echo '<h1>'.stripslashes(htmlspecialchars($data['forum_name'])).'</h1><br />';

//Bouton pour poster
echo'<a href="index.php?cible=forum_poster&action=nouveautopic&amp;f=0">
<img src="./images/nouveau.gif" alt="Nouveau topic" title="Poster un nouveau topic" /></a>';
$query->CloseCursor();

//Messages
$query=$bdd->prepare('SELECT forum_topic.topic_id, topic_titre, topic_createur, topic_vu, topic_post, topic_time, topic_last_post,
Mb.prenom AS prenom_createur, post_id, post_createur, post_time, Ma.prenom AS prenom_last_posteur FROM forum_topic
LEFT JOIN user Mb ON Mb.iduser = forum_topic.topic_createur
LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
LEFT JOIN user Ma ON Ma.iduser = forum_post.post_createur   
WHERE topic_genre <> "Annonce" AND forum_topic.forum_id = :forum
ORDER BY topic_last_post DESC
LIMIT :premier ,:nombre');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();

if ($query->rowCount()>0)
{
?>
        <table>
        <tr>
        <th><img src="./images/message.gif" alt="Message" /></th>
        <th class="titre"><strong>Titre</strong></th>             
        <th class="nombremessages"><strong>Réponses</strong></th>
        <th class="nombrevu"><strong>Vus</strong></th>
        <th class="auteur"><strong>Auteur</strong></th>                       
        <th class="derniermessage"><strong>Dernier message  </strong></th>
        </tr>
        <?php
        
       
        while ($data = $query->fetch())
        {
                // Sans le lien vers la page utilisateur
                echo'<tr><td><img src="./images/message.gif" alt="Message" /></td>
                <td class="titre">
                <strong><a href="index.php?cible=forum_voirtopic&t='.$data['topic_id'].'"                 
                title="Topic commencé à
                '.date('H\hi \l\e d M,y',$data['topic_time']).'">
                '.stripslashes(htmlspecialchars($data['topic_titre'])).'</a></strong></td>
                <td class="nombremessages">'.$data['topic_post'].'</td>
                <td class="nombrevu">'.$data['topic_vu'].'</td>
                <td>'.stripslashes(htmlspecialchars($data['prenom_createur'])).'</td>';

               	//Selection dernier message
		$nombreDeMessagesParPage = 15;
		$nbr_post = $data['topic_post'] +1;
		$page = ceil($nbr_post / $nombreDeMessagesParPage);
                echo '<td class="derniermessage">Par
                '.stripslashes(htmlspecialchars($data['prenom_last_posteur'])).'</a><br />
                A <a href="index.php?cible=voirtopic&t='.$data['topic_id'].'&amp;page='.$page.'#p_'.$data['post_id'].'">'.date('H\hi \l\e d M y',$data['post_time']).'</a></td></tr>';

        }
        ?>
        </table>
        <?php
}
else
{
        echo'<p>Ce forum ne contient aucun sujet actuellement</p>';
}
$query->CloseCursor();
    
    
    $functionforum = ob_get_clean();
    return $functionforum;
}

?>