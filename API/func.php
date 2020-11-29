<!--
Created by Rzuwik
-->
<?php
	$host = 'localhost'; // Nazwa hosta (serwera) bazy danych
	$user = 'root'; // Nazwa użytkownika bazy danych
	$pass = ''; // Hasło użytkownika bazy danych
	$db = 'rpg'; // Nazwa naszej bazy danych
	
	$con = mysqli_connect($host, $user, $pass, $db);
	$con->set_charset("utf8");

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	function call($sql) { // Wywołanie zapytania do bazy
		global $con;
		$call = mysqli_query($con, $sql);
		
		if ($con->errno != 0)
			setcookie("last_err", $con->errno);
		
		return $call;
	}
	
	function row($sql) { // Funkcja wybierająca cały szereg danych jako tablica asocjacyjna
		global $con;
		return mysqli_fetch_assoc(mysqli_query($con, $sql));
	}
	
	function arr($sql) { // Funkcja wybierająca cały szereg danych jako zwykła tablica
		global $con;
		return mysqli_fetch_array(mysqli_query($con, $sql));
	}
	
	function vtxt($var) { // Funkcja zabezpieczająca dane wysyłane do bazy
		global $con;
		return trim(mysqli_real_escape_string($con, strip_tags($var)));
	}
	
	function avg($arr) { // Funkcja licząca średnią
		if (!is_array($arr))
			return false;
		return array_sum($arr) / count($arr);
	}
	
	function getUser($id) { // Funkcja wybierająca szereg danych o graczu z podanym ID
		return row("SELECT * FROM users WHERE id = ".(int)$id);
	}
	
	function getLocData($x, $y) {
		return row("SELECT * FROM locations WHERE x = ".(int)$x." AND y = ".(int)$y);
	}
	
	function getPlayerDamage($id) {
		if (!isset($id))
			return;
		$data = getUser($id);
		if (!$data)
			return;
		$weapon = row("SELECT items.min_dmg, items.max_dmg FROM items INNER JOIN inventory ON items.id = inventory.obj WHERE inventory.uid = ".$data['id']." AND inventory.used = 1 AND items.type = 'weapon' LIMIT 1");
		if ($weapon) {
			$weapon['min_dmg'] += ($data['str'] * 2);
			$weapon['max_dmg'] += ($data['str'] * 2);
			return array($weapon['min_dmg'], $weapon['max_dmg']);
		} else
			return $data['str'] * 2;
	}
	
	function getPlayerDodge($id) {
		if (!isset($id))
			return;
		$data = getUser($id);
		if (!$data)
			return;
		if ($data['dex'] >= 50)
			return 100;
		else
			return $data['dex'] * 2;
	}
	
	function getPlayerCritical($id) {
		if (!isset($id))
			return;
		$data = getUser($id);
		if (!$data)
			return;
		if ($data['intell'] >= 100)
			return 100;
		else
			return $data['intell'] * 1;
	}
	
	function getPlayerWeapon($id) {
		if (!isset($id))
			return;
		$data = getUser($id);
		if (!$data)
			return;
		$weapon = row("SELECT inventory.id FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE inventory.uid = ".$data['id']." AND inventory.used = 1 AND items.type = 'weapon' LIMIT 1");
		if ($weapon)
			return $weapon['id'];
		else
			return false;
	}
	
	function checkUser($sid) { // Funkcja weryfikująca stan gracza (czy zalogowany)
		if (empty($sid))
			header("Location: index.php?a=login"); // Jeżeli puste ID sesji, przejście do strony logowania
		else
			return $sid = (int)$sid; // Gdy ID sesji jest poprawne, zmiana lub utrzymanie stanu ID jako integer (postać numeryczna)
	}
	
	function checkInventoryStamina() {
		$data = call("SELECT id FROM inventory WHERE used = 1 AND stamina < 1");
		while ($item = mysqli_fetch_array($data)) {
			call("UPDATE inventory SET used = 0 WHERE id = ".$item['id']);
		}
	}
	
	function throwInfo($type, $msg, $dis = false) {
		$class = 'alert ';
		if ($dis)
			$class .= 'alert-dismissable ';
		if ($type == 'warning' || $type == 'danger' || $type == 'success' || $type == 'info')
			$class .= 'alert-'.$type;
		echo '
			<div class="'.$class.'">
		';
		if ($dis)
			echo '<button type="button" class="close" data-dismiss="alert">×</button>';
		echo '
				'.$msg.'
			</div>
		';
	}
	
	function cookie_info() {
		if (empty($_COOKIE['cookies_enabled'])) {
			if (!empty($_GET['a'])) $link = '<a href="cookie.php?a='.$_GET['a'].'">'; else $link = '<a href="cookie.php">';
			echo '
			<div class="alert alert-dismissable alert-warning">
				<button type="button" class="close" data-dismiss="alert">X</button>
				<h4>Uwaga!</h4>
				<p>Ta strona korzysta z "ciasteczek", czyli danych przechowywanych w Twojej przeglądarce.
				Jeżeli nie jesteś pewien co to oznacza, przeczytaj <a href="http://wszystkoociasteczkach.pl/polityka-cookies/"><b>Politykę prywatności Cookies</b></a>.<br/>
				Nie chcesz widzieć tego komunikatu? Kliknij '.$link.'<b>tutaj</b></a>.</p>
			</div>
			';
		}
	}
	
	function menubar($user) {
		if (!empty($user['id'])) {
			echo '
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Postać</a>
				<ul class="dropdown-menu">
					<li><a href="index.php?a=stats">Statystyki</a></li>
					<li><a href="index.php?a=inv">Ekwipunek</a></li>
					<li><a href="index.php?a=mail">Poczta</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Miasto</a>
				<ul class="dropdown-menu">
					<li><a href="index.php?a=table">Okolica</a></li>
					<li><a href="index.php?a=work">Praca</a></li>
					<li><a href="index.php?a=arena">Arena</a></li>
					<li><a href="index.php?a=guild">Gildia</a></li>
					<li><a href="index.php?a=shop">Sklep</a></li>
					<li><a href="index.php?a=tavern">Karczma</a></li>
					<li><a href="index.php?a=blacksmith">Kowal</a></li>
					<li><a href="index.php?a=med">Punkt p. pomocy</a></li>
				</ul>
			</li>
			<li><a href="index.php?a=map">Mapa</a></li>
			<li><a href="index.php?a=rank">Ranking</a></li>
			<li><a href="index.php?a=settings">Ustawienia</a></li>
			<li><a href="index.php?a=changelog">Lista zmian</a></li>
			<li><a href="index.php?a=log_out">Wyloguj się</a></li>
			';
		} else {
			echo '
			<li><a href="index.php?a=register">Zarejestruj się</a></li>
			<li><a href="index.php?a=login">Zaloguj się</a></li>
			<li><a href="index.php?a=changelog">Lista zmian</a></li>
			';
		}
	}
	
	function sideColumn($user) {
		if (!empty($user['id'])) {
			// Aktualizacja zmiennej zawierającej dane o użytkowniku aktualnie zalogowanym
			$user = getUser($user['id']); 
			// Żadamy pliku z zmiennymi
			require_once('API/var.php');
			echo '
			<div class="panel panel-default" style="width: 28%; float: right;">
				<div class="panel-heading">
					<h3 class="panel-title"><b>Witaj:</b><span style="float: right;"><u>'.$user['login'].'</u></span></h3>
				</div>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Gracz<span style="float: right;"><u>Poziom '.$user['lvl'].'</u></span></h3>
						</div>
						<div class="panel-body">
							<div class="progress">
								<div class="progress-bar progress-bar-danger" style="width: '.$pbhp.'%;"><span>Zdrowie '.$user['hp'].' / '.$user['max_hp'].'</span></div>
							</div>
							<div class="progress">
								<div class="progress-bar progress-bar-warning" style="width: '.$pbpa.'%;"><span>PA '.$user['ap'].' / '.$user['max_ap'].'</span></div>
							</div>
							<div class="progress">
								';
								if ($user['lvl'] < 100)
									echo '<div class="progress-bar progress-bar-success" style="width: '.$pbpd.'%;"><span>PD '.$user['xp'].' / '.$user['max_xp'].'</span></div>';
								else
									echo '<div class="progress-bar progress-bar-success" style="width: 100%;"><span>PD '.$user['xp'].'</span></div>';
								echo '
							</div>
							<div class="form-group">
								<div class="input-group">
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['cash'].'" disabled>
									<span class="input-group-addon">$</span>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Umiejętności<span style="float: right;"><u>'.$user['sp'].' pkt.</u></span></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 50px;">SI</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['str'].'" disabled>
									'.$sibutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 50px;">ZR</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['dex'].'" disabled>
									'.$zrbutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 50px;">WY</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['sta'].'" disabled>
									'.$wybutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 50px;">IN</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['intell'].'" disabled>
									'.$inbutton.'
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br clear="both"/>
			';
		} else echo '<br clear="both"/>';
	}
	
	function statusbar($user) {
		if (!empty($user['id'])) {
			echo '<div class="well well-sm" style="text-align: center">';
			$status = row("SELECT status, pos_x, pos_y FROM users WHERE id = ".$user['id']);
			
			if ($status['status'] == 0) {
				$txt = 'przebywa';
				$location = getLocData($status['pos_x'], $status['pos_y']);
			} elseif ($status['status'] == 1) {
				$txt = 'wykonuje pracę';
				$location = getLocData($status['pos_x'], $status['pos_y']);
			} elseif ($status['status'] == 2) {
				$txt = 'odbywa wyprawę';
				$trip = row("SELECT x, y FROM trips WHERE uid = ".$user['id']);
				$dest = getLocData($trip['x'], $trip['y']);
			} elseif ($status['status'] == 3) {
				$txt = '<b>przesiaduje w karczmie</b>';
			}
			
			echo 'Aktualnie Twój bohater <b>'.$txt.'</b>';
			if (isset($location))
				echo ' w <b>'.$location['name'].'</b>.';
			elseif (isset($dest))
				echo ' do <b>'.$dest['name'].'</b>';
			else
				echo '.';
			echo '</div>';
		}
	}
	
	function loc_name($type) {
		if ($type == 'village')
			return 'Wioska';
		elseif ($type == 'city')
			return 'Miasteczko';
		elseif ($type == 'polis')
			return 'Metropolia';
	}
	
	function avatar($id) {
		if (isset($id))
			$zapytanie = getUser($id);
		else
			$zapytanie = getUser($_SESSION['id']);
		
		if (isset($zapytanie['avatar']) && $zapytanie['avatar'] == 1)
			return '<center><img width="200px" height="200px" src="avatar/'.$zapytanie['id'].'.png" alt=""/></center>';
		else
			return '<center><img width="200px" height="200px" src="avatar/avatar.png" alt=""/></center>';
	}
	
	function guild_avatar($id) {
		if (isset($id)) {
			$zapytanie = row("SELECT id, avatar FROM guilds WHERE id = ".$id);
			if (isset($zapytanie['avatar']) && $zapytanie['avatar'] == 1)
				return '<center><img width="200px" height="200px" src="guild_avatar/'.$zapytanie['id'].'.png" alt=""/></center>';
			else
				return '<center><img width="200px" height="200px" src="guild_avatar/avatar.png" alt=""/></center>';
		}
	}
	
	function arena_template($type, $content) {
		$legend = ($type) ? 'Broniący się' : 'Atakujący';
		return '
		<div class="panel-body" style="width: 45%; float: left;">
			<div class="well">
				<legend>'.$legend.'</legend>
				<table width="100%">
					<tr>
						<td style="border-bottom:dashed 1px #000">Nick:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['login'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['max_hp'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['dam'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Unik:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['dodge'].'%</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['critical'].'%</span></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel-body" style="width: 45%; float: right;">
			<div class="well">
				<legend>Raport z walki</legend>
				<table width="100%">
					<tr>
						<td style="border-bottom:dashed 1px #000">Liczba rund:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['rounds'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Wynik:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['score'].' pkt.</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['xp'].' pkt.</span></td>
					</tr>
					'.$content['rep'].'
				</table>
			</div>
		</div>
		<br clear="both"/>
		';
	}
	
	function sysMail($to, $title, $content, $type = false) {
		if (isset($to) && isset($title) && isset($content)) {
			if (!$type) {
				$to = (int)$to;
				$title = vtxt($title);
				//$content = vtxt($content);
				call("INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES (0, ".$to.", 1, '".$title."', '".$content."', now())");
			} elseif ($type == 'arena') {
				$mail = arena_template($content[0], $content[1]);
				call("INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES (0, ".$to.", 1, '".$title."', '".$mail."', now())");
			}
		}
	}
	
	function restorePoints() {
		call("UPDATE users SET ap = max_ap, hp = max_hp");
	}
	
	function addSkill($skill) {
		if ($skill == 'str' or $skill == 'dex' or $skill == 'sta' or $skill == 'intell') {
			$zapytanie = getUser($_SESSION['id']);
			if ($zapytanie['sp'] > 0) {
				$skill = vtxt($skill);
				if ($skill == 'sta') {
					$hp = 10;
					$pa = 1;
					call("UPDATE users SET ".$skill." = ".$skill." + 1, sp = sp - 1, max_hp = max_hp + ".$hp.", max_ap = max_ap + ".$pa." WHERE id = ".$zapytanie['id']);
				} else {
					call("UPDATE users SET ".$skill." = ".$skill." + 1, sp = sp - 1 WHERE id = ".$zapytanie['id']);
				}
			}
		}
	}
	
	function addLevel() {
		$zapytanie = getUser($_SESSION['id']);
		if ($zapytanie['xp'] >= $zapytanie['max_xp'] && $zapytanie['lvl'] < 100) {
			$last = $zapytanie['xp'] - $zapytanie['max_xp'];
			$percentage = 1.2;
			$sp = 3;
			call("UPDATE users SET xp = ".$last.", max_xp = max_xp * ".$percentage.", lvl = lvl + 1, sp = sp + ".(int)$sp.", hp = max_hp WHERE id = ".$zapytanie['id']);
		}
	}
	
	function distance($x, $y, $ux, $uy) {
		return round(sqrt(pow(($ux - $x), 2) + pow(($uy - $y), 2)) * 100, 1);
	}
	
	function checkWork($time) {
		$zapytanie = getUser($_SESSION['id']);
		$work = row("SELECT * FROM work WHERE uid = ".$zapytanie['id']);
		if ($work && $work['start'] != 0 && $time >= $work['end']) {
			$location = getLocData($zapytanie['pos_x'], $zapytanie['pos_y']);
			call("UPDATE users SET status = 0, xp = xp + ".$work['hours']." * ".$location['re_xp'].", allxp = allxp + ".$work['hours']." * ".$location['re_xp'].", ap = ap - ".$work['hours'].", cash = cash + ".$work['hours']." * ".$location['re_cash']." WHERE id = ".$zapytanie['id']);
			call("DELETE FROM work WHERE uid = ".$zapytanie['id']);
		}
	}
	
	function checkTrip($time) {
		$zapytanie = row("SELECT * FROM trips WHERE uid = ".$_SESSION['id']);
		if ($zapytanie && $time >= $zapytanie['end']) {
			call("UPDATE users SET status = 0, pos_x = ".$zapytanie['x'].", pos_y = ".$zapytanie['y']." WHERE id = ".$_SESSION['id']);
			call("DELETE FROM trips WHERE uid = ".$_SESSION['id']);
		}
	}
	
	function checkTavern($time) {
		$zapytanie = row("SELECT * FROM tavern WHERE uid = ".$_SESSION['id']);
		if ($zapytanie && $time >= $zapytanie['end']) {
			if ($zapytanie['is_int'] == 0) {
				call("UPDATE users SET ap = ap + (".$zapytanie['hours']." * 2) + 1, cash = cash - (10 * ".$zapytanie['hours'].") + 5, status = 0 WHERE id = ".$user['id']);
				call("DELETE FROM tavern WHERE uid = ".$user['id']);
			} else {
				call("UPDATE users SET ap = ap + (".$zapytanie['hours']." * 2), cash = cash - (10 * ".$zapytanie['hours']."), status = 0 WHERE id = ".$user['id']);
				call("DELETE FROM tavern WHERE uid = ".$user['id']);
			}
		}
	}
?>