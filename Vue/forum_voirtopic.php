<?php
    $entete = entete("Mon site / Forum");
    $menu = menu("forum");
    $contenu = voirtopic();
    $pied = pied();

    include 'gabarit.php';

function voirtopic(){
    ob_start();
    
$titre="Voir un sujet";
//Connexion à la base de donnée
require ('Modele/connexion.php');
include ("Controleur/forum_debut.php");
include("Controleur/forum_bbcode.php"); 
$t = $_SESSION['t'];
//$topic = (int) $_GET['t'];
$topic = (int) $_SESSION['t'];

//Limitation des messages
$query=$bdd->prepare('SELECT topic_titre, topic_post, forum_id, topic_last_post
FROM forum_topic 
WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
$forum=$data['forum_id'];
$totalDesMessages = $data['topic_post'] + 1;
$nombreDeMessagesParPage = 10;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);

//Fil d'arianne
echo '<p><i>Vous êtes ici</i> : <a href="index.php?cible=forum">Index du forum</a> / 
<a href="index.php?cible=forum_voirtopic&t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
echo '<h1>'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br /><br />';

//Nombre de pages
$page = (isset($_SESSION['page']))?intval($_SESSION['page']):1;
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) 
    {
    echo $i;
    }
    else
    {
    echo '<a href="index.php?cible=forum_voirtopic&t='.$topic.'&page='.$i.'">
    ' . $i . '</a> ';
    }
}
echo'</p>'; 
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

//Bouton répondre
echo'<a href="index.php?cible=forum_poster&action=repondre&amp;t='.$topic.'">
<img src="./images/repondre.gif" alt="Répondre" title="Répondre à ce topic" /></a>';
 
//Bouton nouveau
echo'<a href="index.php?cible=forum_poster&action=nouveautopic&amp;f='.$data['forum_id'].'">
<img src="./images/nouveau.gif" alt="Nouveau topic" title="Poster un nouveau topic" /></a>';
$query->CloseCursor(); 

//Début de la boucle
$query=$bdd->prepare('SELECT post_id , post_createur , post_texte , post_time ,
iduser, prenom
FROM forum_post
LEFT JOIN user ON user.iduser = forum_post.post_createur
WHERE topic_id =:topic
ORDER BY post_id
LIMIT :premier, :nombre');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();
 
//On vérifie que la requête a bien retourné des messages
if ($query->rowCount()<1)
{
        echo'<p>Il n y a aucun post sur ce topic, vérifiez l url et reessayez</p>';
}
else
{
        //Si tout roule on affiche notre tableau puis on remplit avec une boucle
        ?><table>
        <tr>
        <th class="vt_auteur"><strong>Auteurs</strong></th>             
        <th class="vt_mess"><strong>Messages</strong></th>       
        </tr>
        <?php
        while ($data = $query->fetch())
        {
        //Affichage pseudo    
        echo'<tr><td><strong>
         '.stripslashes(htmlspecialchars($data['prenom'])).'</strong></td>';
         if ($id == $data['post_createur'])
         {
         echo'<td id=p_'.$data['post_id'].'>Posté à '.date('H\hi \l\e d M y',$data['post_time']).'';
         }
         else
         {
         echo'<td>
         Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
         </td></tr>';
         }       
         //Détails sur le membre qui a posté
         echo'<tr><td>         
         <br />Messages : <br />';   
         //Message
         echo'<td>'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'';
         } 
         $query->CloseCursor();
         //Fin de la boucle
         ?>
</table>
        <?php
        echo '<p>Page : ';
        for ($i = 1 ; $i <= $nombreDePages ; $i++)
        {
                if ($i == $page) 
                {
                echo $i;
                }
                else
                {
                echo '<a href="index.php?cible=forum_voirtopic&t='.$topic.'&amp;page='.$i.'">
                ' . $i . '</a> ';
                }
        }
        echo'</p>';
       
        //Ajouter 1 au nombre de vues
        $query=$bdd->prepare('UPDATE forum_topic
        SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

} 
?>           
</div>
<?php
    
    $voirtopic = ob_get_clean();
    return $voirtopic;
}