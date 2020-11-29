<?php
ob_start();
session_start(); // Rozpoczynamy lub przedłużamy pracę sesji
require_once('API/func.php'); // Pobranie pliku z funkcjami
require_once('cron/cron.php'); // Pobranie pliku pseudoCRON

if (!empty($_SESSION['id'])) {
	checkUser($_SESSION['id']); // Sprawdzenie, czy gracz jest zapisany w sesji (zalogowany)
	$user = getUser($_SESSION['id']); // Wybierany danych z bazy o graczu aktualnie zalogowanym
	checkWork(time()); // Sprawdzenie stanu pracy
	checkTrip(time()); // Sprawdzenie stanu wyprawy
	checkInventoryStamina();
	if (!empty($_GET['skill'])) addSkill($_GET['skill']); // Dodanie punktu do umiejętności gdy pojawi się wartość w GET
	addLevel(); // Sprawdzenie poziomu gracza i ew. dodanie nowego
} else {
	$user = array(); // Czyszczenie zmiennej gracza
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" /> <!-- Ustawienie kodowania na UTF-8 -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"> <!-- adres do pliku arkusza stylu kaskadowego -->
		<link rel="stylesheet" href="style/style.css"> <!-- adres do pliku z dodatkowymi klasami stylów -->
		<script src="API/jquery.min.js"></script> <!-- adres do pliku jQuery -->
		<title>Gra RPG</title> <!-- Tytuł strony -->
	</head>
	<body style="background-color: gray;"> <!-- Kolor tła -->
		<!--
		Created by Rzuwik
		-->
		<div class="container"> <!-- Blok centrujący -->
			<br/>
			<nav class="navbar navbar-default" role="navigation"> <!-- Navbar -->
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Gra RPG</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php menubar($user) ?> <!-- Pasek menu -->
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="https://www.github.com/Rzuwik/">Created by Rzuwik</a></li>
					</ul>
				</div>
			</nav>
			<?php statusbar($user); ?>
			<div class="panel panel-default" style="width: 70%; float: left;"> <!-- Blok główny -->
				<?php
				if (empty($_GET)) $_GET['a'] = 'home';
				
				if (!empty($user['id'])) {
					switch($_GET['a']){ // Funkcja wybierania pliku do załadowania
						case 'home': require_once('code/home.php'); break; // Strona główna
						case 'table': require_once('code/table.php'); break; // Strona główna rozgrywki
						case 'settings': require_once('code/settings.php'); break; // Strona z ustawieniami konta
						case 'stats': require_once('code/stats.php'); break; // Strona z statystykami gracza
						case 'med': require_once('code/med.php'); break; // Strona punktu pierwszej pomocy
						case 'tavern': require_once('code/tavern.php'); break; // Strona karczmy
						case 'work': require_once('code/work.php'); break; // Strona pracy
						case 'mail': require_once('code/mail.php'); break; // Strona poczty
						case 'map': require_once('code/map.php'); break; // Strona z mapą
						case 'loc': require_once('code/loc.php'); break; // Strona z info o lokacji
						case 'trip': require_once('code/trip.php'); break; // Strona wyprawy
						case 'rank': require_once('code/rank.php'); break; // Strona z rankingiem
						case 'inv': require_once('code/inv.php'); break; // Strona ekwipunku
						case 'shop': require_once('code/shop.php'); break; // Strona sklepu
						case 'arena': require_once('code/arena.php'); break; // Arena
						case 'guild': require_once('code/guild.php'); break; // Gildia
						case 'blacksmith': require_once('code/blacksmith.php'); break; // Kowal
						case 'changelog': require_once('code/changelog.php'); break; // Pełna lista zmian (changelog)
						case 'version': require_once('code/version.php'); break; // Strona o wersji (changelog)
						case 'log_out': // Wyloguj
							$_SESSION = array(); // Czyszczenie sesji (nadpisanie czystą tablicą)
							session_destroy(); // Usuwanie aktywnej sesji
							header("Location: index.php"); // Przeniesienie na stronę główną
						break;
						default:
							require_once('code/table.php'); 
							$_GET['a'] = 'table';
						break; // Strona ładowana domyślnie
					}
				} else {
					switch($_GET['a']){ // Funkcja wybierania pliku do załadowania
						case 'home': require_once('code/home.php'); break; // Strona główna
						case 'login': require_once('code/login.php'); break; // Strona logowania
						case 'register': require_once('code/register.php'); break; // Strona rejestracji
						case 'changelog': require_once('code/changelog.php'); break; // Pełna lista zmian (changelog)
						case 'version': require_once('code/version.php'); break; // Strona o wersji (changelog)
						default: require_once('code/home.php'); break; // Strona ładowana domyślnie
					}
				}
				?>
			</div>
			<!-- Kolumna poboczna -->
			<?php
			sideColumn($user);
			cookie_info();
			?>
		</div>
		<script src="API/bootstrap.min.js"></script>
	</body>
</html>
<?php
mysqli_close($con); // Zamknięcie połączenia z bazą danych
ob_end_flush();
?>