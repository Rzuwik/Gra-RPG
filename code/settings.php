<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Ustawienia</h3>
</div>
<div class="panel-body">
	<?php
	if (!empty($_POST)) {
		if (isset($_GET['b']) && $_GET['b'] == 'password') {
			if (!isset($_POST['old']) || !isset($_POST['new1']) || !isset($_POST['new2']))
				throwInfo('danger', 'Wypełnij wszystkie pola poprawnie!', true);
			elseif ($_POST['new1'] != $_POST['new2'])
				throwInfo('danger', 'Hasła nie pasują do siebie!', true);
			else {
				$old = vtxt($_POST['old']);
				$new = vtxt($_POST['new1']);
				
				if (md5(sha1($old)) != $user['password'])
					throwInfo('danger', 'Podane aktualne hasło jest nieprawidłowe', true);
				elseif (strlen($new) < 6 || strlen($new) > 20)
					throwInfo('danger', 'Hasło nie mieści się w danym zakresie', true);
				elseif ($user['login'] == $new)
					throwInfo('danger', 'Login nie może być taki sam jak hasło', true);
				else {
					$encoded = md5(sha1($new));
					$query = call("UPDATE users SET password = '".$encoded."' WHERE id = ".$user['id']);
					if (!$query)
						throwInfo('danger', 'Wystąpił błąd podczas zmiany hasła', true);
					else
						throwInfo('success', 'Zmieniono hasło', true);
				}
			}
		} elseif (isset($_GET['b']) && $_GET['b'] == 'avatar') {
			if (empty($_FILES) || !isset($_FILES['avatar']))
				throwInfo('danger', 'Nie wprowadzono pliku!', true);
			else {
				$av = $_FILES['avatar'];
				$tmp = $av['tmp_name'];
				$type = $av['type'];
				$size = $av['size'];
				
				if ($type != 'image/png')
					throwInfo('danger', 'Wrzucany avatar musi mieć rozszerzenie .png', true);
				elseif ($size > 30000)
					throwInfo('danger', 'Wrzucany avatar musi ważyć mniej niż 30kB', true);
				elseif (!is_uploaded_file($tmp))
					throwInfo('danger', 'Wystąpił błąd podczas wysyłania pliku', true);
				elseif (!move_uploaded_file($tmp, 'avatar/'.$user['id'].'.png'))
					throwInfo('danger', 'Wystąpił błąd podczas przenoszenia pliku', true);
				else {
					call("UPDATE users SET avatar = 1 WHERE id = ".$user['id']);
					throwInfo('success', 'Zmieniono avatar', true);
				}
			}
		} elseif (isset($_GET['b']) && $_GET['b'] == 'email') {
			if (!isset($_POST['email']))
				throwInfo('danger', 'Wypełnij wszystkie pola poprawnie!', true);
			else {
				$mail = vtxt($_POST['email']);
				
				if (filter_var($mail, FILTER_VALIDATE_EMAIL) == false)
					throwInfo('danger', 'Podany ciąg nie jest adresem email', true);
				elseif (strlen($mail) < 8 || strlen($mail) > 50)
					throwInfo('danger', 'Adres email nie mieści się w danym zakresie', true);
				else {
					$query = call("UPDATE users SET email = '".$mail."' WHERE id = ".$user['id']);
					if (!$query)
						throwInfo('danger', 'Wystąpił błąd podczas zmiany adresu', true);
					else
						throwInfo('success', 'Zmieniono adres email', true);
				}
			}
		}
	}
	?>
	<div class="panel panel-default" style="width: 48%; float: left;">
		<div class="panel-heading">Zmiana hasła</div>
		<div class="panel-body">
			<form class="form-horizontal" action="index.php?a=settings&b=password" method="POST">
				<fieldset>
					<div class="form-group">
						<div class="col-lg-12">
							<input type="password" class="form-control" name="old" placeholder="Podaj obecne hasło"/>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12">
							<input type="password" class="form-control" name="new1" placeholder="Podaj nowe hasło"/>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12">
							<input type="password" class="form-control" name="new2" placeholder="Powtórz nowe hasło"/>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-default">Wyczyść</button>
							<button type="submit" class="btn btn-primary">Zmień hasło</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<div class="panel panel-default" style="width: 48%; float: right;">
		<div class="panel-heading">Zmiana avatara</div>
		<div class="panel-body">
			<form class="form-horizontal" action="index.php?a=settings&b=avatar" enctype="multipart/form-data" method="POST">
				<fieldset>
					<div class="form-group">
						<div class="col-lg-12">
							<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
							<div class="well well-sm">
								<input type="file" name="avatar"/>
							</div>
						</div>
						<div class="col-lg-8 col-lg-offset-4">
							<button type="submit" class="btn btn-primary">Wyślij</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<div class="panel panel-default" style="width: 48%; float: right;">
		<div class="panel-heading">Zmiana adresu e-mail</div>
		<div class="panel-body">
			<form class="form-horizontal" action="index.php?a=settings&b=email" method="POST">
				<fieldset>
					<div class="form-group">
						<div class="col-lg-12">
						<input type="text" class="form-control" name="email" placeholder="Podaj nowy adres"/>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-10 col-lg-offset-2">
							<button type="reset" class="btn btn-default">Wyczyść</button>
							<button type="submit" class="btn btn-primary">Zmień adres</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<br clear="both"/>
</div>