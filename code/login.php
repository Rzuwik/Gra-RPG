<!--
Created by Rzuwik
-->

<div class="panel-body">
	<?php
	// Warunek uniemożliwiający przelogowanie się na aktywnym koncie
	if (!empty($_SESSION['id']))
		header('Location: index.php?a=info');
	if (!empty($_COOKIE['reg'])): ?>
	<div class="alert alert-dismissable alert-success">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<button type="button" class="btn btn-default btn-lg" disabled>
			<span class="glyphicon glyphicon-ok-sign"></span>
		</button>
		<strong>Gratulacje!</strong>Twoje konto zostało utworzone. Możesz się teraz zalogować.</a>.
	</div>
	<?php setcookie("reg", "", time()-3600);
	endif;
	if (!empty($_POST)) {
		if (empty($_POST['login']) || empty($_POST['pass']))
			throwInfo('danger', 'Wypełnij pola poprawnie', true);
		else {
			$login = vtxt($_POST['login']);
			$pass = md5(sha1(vtxt($_POST['pass'])));
			if (!ctype_alnum($login))
				throwInfo('danger', 'Niepoprawna nazwa użytkownika', true);
			else {
				$zapytanie = row("SELECT * FROM users WHERE login = '".$login."' AND password = '".$pass."'");
				if (!$zapytanie)
					throwInfo('danger', 'Taki gracz nie istnieje lub hasło nieprawidłowe', true);
				else {
					$_SESSION = array();
					$_SESSION['id'] = $zapytanie['id'];
					header('Location: index.php?a=table');
				}
			}
		}
	}
	?>
	<div class="well">
		<form class="form-horizontal" action="index.php?a=login" method="POST">
			<fieldset>
				<legend>Logowanie</legend>
				<div class="form-group" align="right">
					<div class="col-lg-3"></div>
					<div class="col-lg-6">
						<input type="text" class="form-control" name="login" text-align="center" placeholder="Login">
					</div>
				</div>
				<div class="form-group" align="right">
					<div class="col-lg-3"></div>
					<div class="col-lg-6">
						<input type="password" class="form-control" name="pass" placeholder="Hasło">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12 col-lg-offset-5">
						<button type="submit" class="btn btn-primary">Zatwierdź</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>