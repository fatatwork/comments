<?php
require_once '../funcLib.php';
session_start();
header('Content-type: text/html; charset=utf-8');
$articles=getArticles();

if($_REQUEST){
	if(!isset($_REQUEST['article']) && isset($_SESSION['article'])) $_REQUEST['article']=$_SESSION['article'];

	if(isset($_REQUEST['user_id']) && isset($_REQUEST['ban'])) banUser($_REQUEST['user_id'], $_REQUEST['ban']);
	if(isset($_REQUEST['unban_user'])) banUser($_REQUEST['unban_user'], NULL);
	if(isset($_REQUEST['delete'])) deleteComment($_REQUEST['delete']);

	if($_REQUEST['article']){
				$_SESSION['article']=$_REQUEST['article'];
				$article=searchArticleById($_REQUEST['article']);
				$comments=getComments($article['link']);
				$comments=array_reverse($comments, true);
				foreach ($comments as $comment) {
					echo $comment['first_name']." ".$comment['last_name']." ".$comment['add_time']."<br />".
					$comment['comment'];
					if(!$comment['ban_time']){
						echo "<div class='ban_form' id='form_".$comment['id']."'>
								<div class='ban_buttons'>
									<input type='radio'  name='ban' value='day'/>День
									<input type='radio'  name='ban' value='week'/>Неделя
									<input type='radio'  name='ban' value='month'/>Месяц
									<input type='radio'  name='ban' value='year'/>Год
									<input type='radio'  name='ban' value='forever'/>Навсегда<br />
									<button type='submit' name='user_id' 
									onclick=\"banPressed('".$comment['id']."','".$comment['user_id']."','ban')\" />забанить</button>
									<button type='submit' name='comment_id'
									onclick=\"banPressed('".$comment['id']."','".$comment['user_id']."','delete')\" />удалить</button>
								</div>
				 			 </div>";
				} else {
						echo 
						"<div class='ban_form' id='form_".$comment['id']."'>
							 <button type='submit' name='unban_user' onclick=\"banPressed('".$comment['id']."','".$comment['user_id']."','unban_user')\"/>разбанить</button>
						</div>";
						}
					echo "<br />";
				}
		}
}

?>

