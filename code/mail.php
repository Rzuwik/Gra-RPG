<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Poczta</h3>
</div>
<div class="panel-body">
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=mail&s=new" class="btn btn-default">Napisz</a>
		<a href="index.php?a=mail" class="btn btn-default">Odebrane</a>
		<a href="index.php?a=mail&s=sent" class="btn btn-default">Wysłane</a>
	</div>
	<br/>
	<div class="panel panel-default">
		<div class="panel-body">
			<?php
			if (isset($_GET['s']) && $_GET['s'] == 'new'):
				if (isset($_POST['to_id']) && isset($_POST['title']) && isset($_POST['content'])) {
					$to = vtxt($_POST['to_id']);
					$title = vtxt($_POST['title']);
					$content = vtxt($_POST['content']);
					
					if ($to == '0')
						throwInfo('danger', 'Nie można odpowiadać na maile systemowe', false);
					else {
						$id = row("SELECT id FROM users WHERE login = '".$to."'");
						if (empty($id))
							throwInfo('danger', 'Nie ma takiego gracza', false);
						else {
							call("INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES 
							(".$user['id'].", ".$id['id'].", 1, '".$title."', '".$content."', now()),
							(".$user['id'].", ".$id['id'].", 2, '".$title."', '".$content."', now())");
							throwInfo('success', 'Wysłano wiadomość', false);
						}
					}
				} ?>
			<form class="form-horizontal" action="index.php?a=mail&s=new" method="POST">
				<fieldset>
					<legend>Nowa wiadomość</legend>
					<div class="form-group" align="right">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="to_id" placeholder="Login" value="<?=(isset($_GET['to_id'])) ? vtxt($_GET['to_id']) : '';?>">
						</div>
					</div>
					<div class="form-group" align="right">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="title" placeholder="Tytuł">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-2"></div>
						<div class="col-lg-10">
							<textarea class="form-control" rows="3" placeholder="Treść" id="textArea" name="content" style="margin: 0px -5.84375px 0px 0px; width: 480px; max-width: 480px; height: 74px;"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12 col-lg-offset-5">
							<button type="submit" class="btn btn-primary">Wyślij</button>
						</div>
					</div>
				</fieldset>
			</form>
			<?php elseif (isset($_GET['s']) && $_GET['s'] == 'sent'):
				if (!empty($_GET['del'])) {
					$_GET['del'] = (int)$_GET['del'];
					$mid = vtxt($_GET['del']);
					$del = call("DELETE FROM mail WHERE id = ".$mid." AND from_id = ".$user['id']." AND type = 2");
					if ($del == true)
						throwInfo('success', 'Wiadomość usunięta', true);
					else
						throwInfo('danger', 'Nie ma takiej wiadomości', true);
				}
				
				$mail = "SELECT * FROM mail WHERE from_id = ".$user['id']." AND type = 2";
				$present = call($mail);
				
				if ($present->num_rows == 0) {
					throwInfo('info', 'Brak wysłanych wiadomości', false);
				} else {
					while ($msg = mysqli_fetch_array($present)):
						$name = row("SELECT login FROM users WHERE id = ".$msg['from_id']); ?>
			<div class="panel panel-default">
				<div class="panel-heading"><b><i><?=$name['login'];?></i>: <?=$msg['title'];?></b><span style="float: right;"><?=$msg['date'];?></span></div>
				<div class="panel-body">
					<?=$msg['content'];?>
				</div>
				<div class="panel-footer">
					<a href="index.php?a=mail&s=sent&del=<?=$msg['id'];?>" class="btn btn-primary btn-sm">Usuń</a>
					<br/>
				</div>
			</div>
					<?php endwhile;
				}
			else:
				if (isset($_GET['del']) && !empty($_GET['del'])) {
					$_GET['del'] = (int)$_GET['del'];
					$mid = vtxt($_GET['del']);
					$del = call("DELETE FROM mail WHERE id = ".$mid." AND to_id = ".$user['id']." AND type = 1");
					if ($del == true)
						throwInfo('success', 'Wiadomość usunięta', true);
					else
						throwInfo('danger', 'Nie ma takiej wiadomości', true);
				}
				
				$mail = "SELECT * FROM mail WHERE to_id = ".$user['id']." AND type = 1";
				$present = call($mail);
				
				if ($present->num_rows == 0) {
					throwInfo('info', 'Brak odebranych wiadomości', false);
				} else {
					while ($msg = mysqli_fetch_array($present)):
						if ($msg['from_id'] == 0)
							$name['login'] = "[SYSTEM]";
						else
							$name = row("SELECT login FROM users WHERE id = ".$msg['from_id']); ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<b><i><?=$name['login'];?></i>: <?=$msg['title'];?><?=($msg['status'] == 0) ? '&nbsp;<span class="badge">Nowa!</span>' : '';?></b><span style="float: right;"><?=$msg['date'];?></span>
				</div>
				<div class="panel-body">
					<?=$msg['content'];?>
				</div>
				<div class="panel-footer">
					<?php if ($msg['from_id'] != 0): ?>
					<a href="index.php?a=mail&s=new&to_id=<?=$name['login'];?>" class="btn btn-primary btn-sm">Odpisz</a>
					<?php endif; ?>
					<a href="index.php?a=mail&del=<?=$msg['id'];?>" class="btn btn-primary btn-sm">Usuń</a>
					<br/>
				</div>
			</div>
					<?php endwhile;
					call("UPDATE mail SET status = 1 WHERE to_id = ".$user['id']." AND type = 1 AND status = 0");
				}
			endif;
			?>
		</div>
	</div>
</div>