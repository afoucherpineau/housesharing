<?php
$entete = entete("Mon site / Gestion");
$menu = menu("gestion");
if (isset($_SESSION["iduser"])){
    if (admin($_SESSION["iduser"])=='1'){
        $sousmenu = sousmenu("gestionforum");
        $contenu = "<br/><br/>";
        $contenu .= afftopic();
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
function afftopic(){
    ob_start();
    require ('Modele/connexion.php');
    
    if (empty($_POST)){
        
            require ('Modele/connexion.php');
            $q=$bdd->query("SELECT topic_id, topic_titre, topic_time, identifiant FROM forum_topic INNER JOIN user ON forum_topic.topic_createur = user.iduser");
            $ligne = $q-> fetch(); ?>

            <br/>
            <fieldset id='infocon'>
            <legend>Liste des Topics</legend>
            <table>
                <tr>
                    <td>ID du topic</td>
                    <td>Titre</td>
                    <td>Créateur</td>
                    <td>Crée le</td>
                </tr>
            <?php do { ?>
                <tr>
                    <td><?php echo $ligne['topic_id'];?></td>
                    <td><?php echo $ligne['topic_titre'];?></td>
                    <td><?php echo $ligne['identifiant'];?></td>
                    <td><?php echo $ligne['topic_time'];?></td>
                </tr>
                <?php $ligne = $q-> fetch();
            } while (!empty($ligne)); ?>
            </table>
            </fieldset>
            <?php
    
        echo formtopic();
    }
    if (!empty($_POST)){
        if ($_POST['action']=='supprtopic'){
            $topic_id = htmlspecialchars($_POST["topic"],ENT_QUOTES);
            $q=$bdd->prepare("DELETE FROM forum_topic WHERE topic_id='$topic_id'");
            $q -> execute();
            $s=$bdd->prepare("DELETE FROM forum_post WHERE topic_id='$topic_id'");
            $s -> execute();
            echo "Le topic a été supprimé.<br/><br/>";
            
                require ('Modele/connexion.php');
                $q=$bdd->query("SELECT topic_id, topic_titre, topic_time, identifiant FROM forum_topic INNER JOIN user ON forum_topic.topic_createur = user.iduser");
                $ligne = $q-> fetch(); ?>

                <br/>
                <fieldset id='infocon'>
                <legend>Liste des Topics</legend>
                <table>
                    <tr>
                        <td>ID du topic</td>
                        <td>Titre</td>
                        <td>Créateur</td>
                        <td>Crée le</td>
                    </tr>
                <?php do { ?>
                    <tr>
                        <td><?php echo $ligne['topic_id'];?></td>
                        <td><?php echo $ligne['topic_titre'];?></td>
                        <td><?php echo $ligne['identifiant'];?></td>
                        <td><?php echo $ligne['topic_time'];?></td>
                    </tr>
                    <?php $ligne = $q-> fetch();
                } while (!empty($ligne)); ?>
                </table>
                </fieldset>
    <?php
    
            echo formtopic();
        } else if ($_POST['action']=='affpost'){
            if (isset($_POST['post'])&&!empty($_POST['post'])){
                $post_id = htmlspecialchars($_POST["post"],ENT_QUOTES);
                $q=$bdd->prepare("DELETE FROM forum_post WHERE post_id='$post_id'");
                $q -> execute();
                echo "Le post a été supprimé.<br/><br/>";
            }
                echo affpost($_POST['topic']);
            
        }
    }
    
    $affpost=ob_get_clean();
    return$affpost;
}

// Formulaire de suppression
function formtopic(){
    ob_start();
    require ('Modele/connexion.php');
    
    $q=$bdd->query("SELECT topic_id, topic_titre FROM forum_topic");
    $ligne = $q-> fetch(); ?>
    <fieldset id='infocon'>
    <legend>Gérer les Topics</legend>
        <form method="post">
            <div class="middle">
            <div class="centre_colonne">
                <label for="topic">Selectionner un topic :</label><br />
                <select name="topic">
                <?php do {?>
                <option value=<?php echo $ligne['topic_id']; ?>><?php echo($ligne['topic_id']." ; ".$ligne['topic_titre']); ?></option>
                <?php
                $ligne = $q-> fetch();
                } while (!empty($ligne)); ?>
                </select>
                
                <br/>Vous souhaitez :<br/>
                <input type="radio" name="action" value="affpost" checked="checked" /> <label for="affpost">Afficher les posts du topic</label><br/>
                <input type="radio" name="action" value="supprtopic" /> <label for="supprtopic">Supprimer le topic</label><br />


                <br/>
                <input type="submit" name="Valider" value="Valider*"/><br/>
                * Attention, si vous avez selectionné supprimé, le topic sera supprimé
            </div>
            </div>
        </form>
    </fieldset>
    <?php
    $formuser=ob_get_clean();
    return$formuser;
}

function affpost($topic_id){
    ob_start();
    require ('Modele/connexion.php');
    
    $q=$bdd->query("SELECT topic_id, topic_titre FROM forum_topic WHERE topic_id='$topic_id'");
    $ligne = $q-> fetch(); ?>
    
    <?php
        $r=$bdd->query("SELECT post_id, post_texte, post_time, identifiant FROM forum_post INNER JOIN user ON forum_post.post_createur = user.iduser WHERE topic_id='$topic_id'");
        $post = $r-> fetch(); ?>

        <br/>
        <fieldset id='infocon'>
        <legend>Liste des posts du topic "<?php echo $ligne['topic_titre']; ?>" :</legend>
        <table>
            <tr>
                <td>ID du post</td>
                <td>Texte</td>
                <td>Auteur</td>
                <td>Crée le</td>
            </tr>
        <?php do { ?>
            <tr>
                <td><?php echo $post['post_id'];?></td>
                <td><?php echo $post['post_texte'];?></td>
                <td><?php echo $post['identifiant'];?></td>
                <td><?php echo $post['post_time'];?></td>
            </tr>
            <?php $post = $r-> fetch();
        } while (!empty($post)); ?>
        </table>
        </fieldset>

    
    <fieldset id='infocon'>
    <legend>Gérer les Topics</legend>
        <form method="post">
            <div class="middle">
            <div class="centre_colonne">
                <label for="topic">Topic selectionné :</label><br />
                <select name="topic">
                    <option value=<?php echo $topic_id; ?>><?php echo($topic_id." ; ".$ligne['topic_titre']); ?></option>
                </select>
                <br/><label for="action">Action choisie :</label><br />
                <select name="action">
                    <option value='affpost'>Afficher les posts du topic</option>
                </select>
                
                <br/><a href="index.php?cible=gestionforum">Revenir à la page de selection de topic</a><br/><br
                
                <label for="post">Supprimer le post :</label><br />
                <?php
                $r=$bdd->query("SELECT post_id, post_texte, post_time, identifiant FROM forum_post INNER JOIN user ON forum_post.post_createur = user.iduser WHERE topic_id='$topic_id'");
                $post = $r-> fetch(); ?>
                <select name="post">
                <?php do { ?>
                <option value=<?php echo $post['post_id']; ?>>Post de <?php echo($post['identifiant']." ; ID du post : ".$post['post_id']); ?></option>
                <?php $post = $r-> fetch();
                } while (!empty($post)); ?>
                </select>

                <br/>
                <input type="submit" name="Valider" value="Valider*"/><br/>
                * Attention, si vous validez, le post sera supprimé definitivement.
            </div>
            </div>
        </form>
    </fieldset>
    <?php
    $formuser=ob_get_clean();
    return$formuser;
}