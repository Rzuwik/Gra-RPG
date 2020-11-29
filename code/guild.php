<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Gildia</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['guild'] > 0):
		$guild = row("SELECT * FROM guilds WHERE id = ".$user['guild']); ?>
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=guild" class="btn btn-default"><b><?=$guild['name'];?></b></a>
		<a href="index.php?a=guild&s=members" class="btn btn-default">Członkowie</a>
		<a href="index.php?a=guild&s=applications" class="btn btn-default">Podania</a>
		<a href="index.php?a=guild&s=config" class="btn btn-default">Ustawienia</a>
	</div>
	<br/>
	<div class="panel panel-default">
		<div class="panel-body">
		<?php if (isset($_GET['s']) && $_GET['s'] == 'members'): ?>
			<table class="table table-striped table-hover ">
			<?php
			$sql = "SELECT * FROM users WHERE guild = ".$guild['id'];
			if (empty($_GET['p']))
				$players = call($sql." ORDER BY guild_priv");
			elseif (!empty($_GET['o'])) {
				if ($_GET['o'] == 1)
					$players = call($sql." ORDER BY ".vtxt($_GET['p']));
				if ($_GET['o'] == 2)
					$players = call($sql." ORDER BY ".vtxt($_GET['p'])." DESC");
			} else $players = call($sql." ORDER BY ".vtxt($_GET['p']));
			
			if (empty($_GET['o']))
				$_GET['o'] = 0;
			if ($_GET['o'] == 2)
				$s = 1;
			else
				$s = 2;
			?>
				<thead>
					<tr>
						<th>#</th>
						<th><a href="index.php?a=guild&s=members&p=login&o=<?=$s;?>">Nick</a></th>
						<th><a href="index.php?a=guild&s=members&p=lvl&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=guild&s=members&p=allxp&o=<?=$s;?>">Doświadczenie</a></th>
						<th><a href="index.php?a=guild&s=members&p=guild_priv&o=<?=$s;?>">Przywileje</a></th>
					</tr>
				</thead>
				<tbody>
			<?php
			$i = 1;
			while ($row = mysqli_fetch_array($players)): ?>
				<tr>
					<td><?=$i++;?></td>
					<td><a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
					<td><?=$row['lvl'];?></td>
					<td><?=$row['allxp'];?></td>
					<td><?=$row['guild_priv'];?></td>
				<?php if ($row['id'] != $user['id'] && $user['guild_priv'] == 100): ?>
					<td><a href="index.php?a=guild&s=discard&p=<?=$row['id'];?>">Wyrzuć</a></td>
				<?php endif; ?>
				</tr>
			<?php endwhile; ?>
				</tbody>
			</table>
		<?php elseif (isset($_GET['s']) && $_GET['s'] == 'config'):
			if (isset($_GET['del_guild']) && $user['guild'] > 0) {
				if ($user['id'] != $guild['ownerid'])
					throwInfo('danger', 'Nie jesteś właścicielem tej gildii', true);
				else {
					$abc = call("SELECT id FROM users WHERE guild = ".$guild['id']);
					while ($row = mysqli_fetch_array($abc)) {
						$kick = call("UPDATE users SET guild = 0, guild_priv = 0 WHERE id = ".$row['id']);
						if ($kick != true) {
							$err = true;
							break;
						}
					}
					if ($err)
						throwInfo('danger', 'Wystąpił błąd podczas usuwania członków gildii', true);
					else {
						$del = call("DELETE FROM guilds WHERE id = ".$guild['id']);
						if (!$del)
							throwInfo('danger', 'Wystąpił błąd podczas usuwania gildii', true);
						else
							header("Location: index.php?a=table");
					}
				}
			} elseif (isset($_GET['avatar']) && $user['guild'] > 0) {
				if ($user['id'] != $guild['ownerid'])
					throwInfo('danger', 'Nie jesteś właścicielem tej gildii', true);
				else {
					$tmp = $_FILES['gavatar']['tmp_name'];
					$type = $_FILES['gavatar']['type'];
					$size = $_FILES['gavatar']['size'];
					if ($type != 'image/png')
						throwInfo('danger', 'Wrzucany sztandar musi mieć rozszerzenie .png', true);
					elseif ($size > 30000)
						throwInfo('danger', 'Wrzucany sztandar musi ważyć mniej niż 30kB', true);
					elseif (!is_uploaded_file($tmp))
						throwInfo('danger', 'Wystąpił błąd podczas wysyłania pliku', true);
					elseif (!move_uploaded_file($tmp, 'guild_avatar/'.$user['guild'].'.png'))
						throwInfo('danger', 'Wystąpił błąd podczas przenoszenia pliku', true);
					else {
						call("UPDATE guilds SET avatar = 1 WHERE id = ".$user['guild']);
						throwInfo('success', 'Zmieniono sztandar', true);
					}
				}
			} ?>
			<div class="panel panel-default" style="width: 48%; float: left;">
				<div class="panel-heading">Usuwanie gildii</div>
				<div class="panel-body">
					<form class="form-horizontal" action="index.php?a=guild&s=config&del_guild" method="GET">
						<fieldset>
							<div class="form-group">
								<div class="col-lg-18 col-lg-offset-4">
									<button type="submit" href="index.php?a=guild&s=config&del_guild" class="btn btn-danger btn-lg">Usuń gildię</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div class="panel panel-default" style="width: 48%; float: right;">
				<div class="panel-heading">Zmiana sztandaru</div>
				<div class="panel-body">
					<form class="form-horizontal" action="index.php?a=guild&s=config&avatar" enctype="multipart/form-data" method="POST">
						<fieldset>
							<div class="form-group">
								<div class="col-lg-12">
									<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
									<div class="well well-sm">
										<input type="file" name="gavatar"/>
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
		<?php elseif (isset($_GET['s']) && $_GET['s'] == 'applications'):
			if (isset($_GET['del']) && !empty($_GET['del'])) {
				$_GET['del'] = (int)$_GET['del'];
				$mid = vtxt($_GET['del']);
				$owner = row("SELECT ownerid FROM guilds WHERE id = ".$user['guild']);
				if ($owner['ownerid'] != $user['id'])
					throwInfo('danger', 'Nie jesteś właścicielem tej gildii', true);
				else {
					$del = call("DELETE FROM applications WHERE id = ".$mid." AND gid = ".$user['guild']);
					if ($del == true)
						throwInfo('success', 'Usunięto podanie', true);
					else
						throwInfo('danger', 'Nie ma takiego podania', true);
				}
			}
			
			if (isset($_GET['acc']) && !empty($_GET['acc'])) {
				$ap = row("SELECT uid FROM applications WHERE id = ".vtxt($_GET['acc']));
				if ($ap == false)
					throwInfo('danger', 'Błąd otrzymywania danych z podania', true);
				else {
					$acc = getUser(vtxt($ap['uid']));
					if ($acc['guild'] != 0)
						throwInfo('danger', 'Ten użytkownik już należy do gildii', true);
					else {
						$ap = row("SELECT id, gid FROM applications WHERE uid = ".$acc['id']);
						if ($ap == false)
							throwInfo('danger', 'Ten użytkownik nie aplikuje do gildii', true);
						else {
							$add = call("UPDATE users SET guild = ".$ap['gid']." WHERE id = ".$acc['id']);
							if (!$add)
								throwInfo('danger', 'Błąd podczas dodawania gracza do gildii', true);
							else {
								$del = call("DELETE FROM applications WHERE id = ".$ap['id']);
								if (!$del)
									throwInfo('danger', 'Błąd podczas usuwania podania', true);
								else {
									$prv = call("UPDATE users SET guild_priv = 1 WHERE id = ".$acc['id']);
									if (!$prv)
										throwInfo('danger', 'Błąd podczas nadawania uprawnień', true);
									else {
										$name = row("SELECT login FROM users WHERE id = ".$acc['id']);
										if ($name == false)
											throwInfo('danger', 'Błąd podczas usuwania podania', true);
										else {
											sysMail($acc['id'], "Zostałeś przyjęty do gildii ".$guild['name'], "");
											throwInfo('success', 'Dodano gracza '.$name['login'].' do gildii', true);
										}
									}
								}
							}
						}
					}
				}
			}
			
			$present = call("SELECT * FROM applications WHERE gid = ".$guild['id']);
			
			if ($present->num_rows == 0) {
				throwInfo('info', 'Brak nowych podań', false);
			} else {
				while ($msg = mysqli_fetch_array($present)):
					$name = row("SELECT login FROM users WHERE id = ".$msg['uid']); ?>
			<div class="panel panel-default">
				<div class="panel-heading"><b><i><?=$name['login'];?></i></b></div>
				<div class="panel-body">
					<?=$msg['content'];?>
				</div>
				<div class="panel-footer">
					<a href="index.php?a=guild&s=applications&acc=<?=$msg['id'];?>" class="btn btn-primary btn-sm">Akceptuj</a>
					<a href="index.php?a=guild&s=applications&del=<?=$msg['id'];?>" class="btn btn-primary btn-sm">Usuń</a>
					<br/>
				</div>
			</div>
				<?php endwhile;
			}
		elseif (isset($_GET['s']) && $_GET['s'] == 'discard'):
			$owner = row("SELECT ownerid FROM guilds WHERE id = ".$user['guild']);
			if ($owner['ownerid'] != $user['id'])
				throwInfo('danger', 'Nie jesteś właścicielem tej gildii', true);
			elseif (!isset($_GET['p']) || (isset($_GET['p']) && empty($_GET['p'])))
				throwInfo('danger', 'Nie wybrano gracza', true);
			else {
				$id = vtxt($_GET['p']);
				$p = row("SELECT guild, guild_priv FROM users WHERE id = ".$id);
				if ($p == false)
					throwInfo('danger', 'Nie ma gracza o takim ID', true);
				elseif ($p['guild'] != $guild['id'])
					throwInfo('danger', 'Gracz nie należy do tej gildii', true);
				elseif ($p['guild_priv'] == 100)
					throwInfo('danger', 'Nie można usunąć właściciela gildii', true);
				else {
					$del = call("UPDATE users SET guild = 0, guild_priv = 0 WHERE id = ".$id);
					if (!$del)
						throwInfo('danger', 'Nie można usunąć gracza z gildii', true);
					else {
						sysMail($id, "Zostałeś usunięty z gildii ".$guild['name'], "");
						throwInfo('success', 'Usunięto gracza z gildii', true);
					}
				}
			}
		else:
			$pcount = row("SELECT count(*) AS ilosc FROM users WHERE guild = ".$guild['id']);
			$leader = row("SELECT login FROM users WHERE id = ".$guild['ownerid']); ?>
			<div class="panel-body" style="width: 35%; float: left;">
				<div class="panel panel-default">
					<div class="panel-body"><?=guild_avatar($guild['id']);?></div>
				</div>
			</div>
			<div class="panel-body" style="width: 65%; float: right;">
				<div class="well">
					<legend>Informacje o ugrupowaniu</legend>
					<table width="100%">
						<tr>
						<td style="border-bottom:dashed 1px #000">Nazwa:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$guild['name'];?></span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Tag:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$guild['tag'];?></span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Lider:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$leader['login'];?></span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Członków:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pcount['ilosc'];?></span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Poziom:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$guild['lvl'];?></span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Reputacja:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$guild['rep'];?></span></td>
					</table>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
	<?php else: ?>
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=guild&s=create" class="btn btn-default">Załóż</a>
		<a href="index.php?a=guild&s=join" class="btn btn-default">Dołącz</a>
	</div>
	<br/>
	<div class="panel panel-default">
		<div class="panel-body">
		<?php if (isset($_GET['s']) && $_GET['s'] == 'create'):
			if (!empty($_POST)) {
				if (!isset($_POST['gname']) || !isset($_POST['gtag']))
					throwInfo('danger', 'Wypełnij pola poprawnie', true);
				else {
					$gname = vtxt($gname);
					$gtag = vtxt($gtag);
					
					if ($user['guild'] > 0)
						throwInfo('danger', 'Już należysz do gildii', true);
					elseif ($user['cash'] < 10000)
						throwInfo('danger', 'Nie stać cię na założenie gildii', true);
					else {
						$app = row("SELECT id FROM applications WHERE uid = ".$user['id']);
						if ($app)
							throwInfo('danger', 'Aplikujesz już do istniejącej gildii', true);
						else {
							$guild = row("SELECT id FROM guilds WHERE name = '".$gname."' OR tag = '".$gtag."'");
							if ($guild)
								throwInfo('danger', 'Istnieje już gildia o takiej nazwie lub tagu', true);
							else {
								$guild = call("INSERT INTO guilds (ownerid, tag, name) VALUES (".$user['id'].", '".$gtag."', '".$gname."')");
								if (!$guild)
									throwInfo('danger', 'Wystąpił błąd podczas tworzenia gildii', true);
								else {
									$guild = row("SELECT id FROM guilds WHERE ownerid = ".$user['id']);
									call("UPDATE users SET cash = cash - 10000, guild = ".$guild['id'].", guild_priv = 100 WHERE id = ".$user['id']);
									header("Location: index.php?a=guild");
								}
							}
						}
					}
				}
			}
			throwInfo("warning", "Do założenia gildii potrzeba 10000$", false); ?>
			<form action="index.php?a=guild&s=create" method="POST" class="form-horizontal">
				<fieldset>
					<legend>Załóż gildię</legend>
					<div class="form-group">
						<label class="col-lg-2 control-label">Nazwa gildii</label>
						<div class="col-lg-10">
							<input type="text" name="gname" style="text-align: center;" class="form-control" placeholder="Wpisz nazwę gildii">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Tag gildii</label>
						<div class="col-lg-10">
							<input type="text" name="gtag" style="text-align: center;" class="form-control" placeholder="Wpisz tag np. [AB]">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-6">
							<button type="submit" class="btn btn-primary">Załóż</button>
						</div>
					</div>
				</fieldset>
			</form>
		<?php elseif (isset($_GET['s']) && $_GET['s'] == 'join'):
			if (!empty($_POST)) {
				if (!isset($_POST['gtag']) || !isset($_POST['gcontent']))
					throwInfo('danger', 'Wypełnij pola poprawnie', true);
				else {
					$gtag = vtxt($_POST['gtag']);
					$gcontent = vtxt($_POST['gcontent']);
					
					if ($user['guild'] > 0)
						throwInfo('danger', 'Już należysz do gildii', true);
					else {
						$app = row("SELECT id FROM applications WHERE uid = ".$user['id']);
						if ($app)
							throwInfo('danger', 'Wysłałeś już aplikację do gildii', true);
						else {
							$guild = row("SELECT id, name FROM guilds WHERE tag = '".$gtag."'");
							if (!$guild) 
								throwInfo('danger', 'Nie istnieje gildia z takim tagiem', true);
							else {
								call("INSERT INTO applications (gid, uid, content) VALUES (".$guild['id'].", ".$user['id'].", '".$gcontent."')");
								throwInfo('success', 'Wysłano aplikację do gildii '.$guild['name'], true);
							}
						}
					}
				}
			} ?>
			<form action="index.php?a=guild&s=join" method="POST" class="form-horizontal">
				<fieldset>
					<legend>Aplikuj do gildii</legend>
					<div class="form-group">
						<label class="col-lg-2 control-label">Tag gildii</label>
						<div class="col-lg-10">
							<input type="text" name="gtag" value="<?=$_GET['gtag'];?>" style="text-align: center;" class="form-control" placeholder="Wpisz tag gildii">
						</div>
					</div>
					<div class="form-group">
						<label for="textArea" class="col-lg-2 control-label">Treść</label>
						<div class="col-lg-10">
							<textarea class="form-control" name="gcontent" style="max-width: 605px;" rows="3" id="textArea"></textarea>
							<span class="help-block">Treść nie może mieć więcej niż 250 znaków.</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-6">
							<button type="submit" class="btn btn-primary">Aplikuj</button>
						</div>
					</div>
				</fieldset>
			</form>
			<?php
			$app = row("SELECT applications.id, guilds.tag, guilds.name FROM applications JOIN guilds ON applications.gid = guilds.id WHERE uid = ".$user['id']);
			if ($app): ?>
			<form action="index.php?a=guild&s=delapp" method="POST" class="form-horizontal">
				<fieldset>
					<legend>Wysłana aplikacja</legend>
					<div class="form-group">
						<label class="col-lg-2 control-label">Gildia</label>
						<div class="col-lg-10">
							<input disabled type="text" name="gtag" value="<?=$app['tag'];?> <?=$app['name'];?>" style="text-align: center;" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-6">
							<button type="submit" class="btn btn-primary">Anuluj aplikację</button>
						</div>
					</div>
				</fieldset>
			</form>
			<?php endif;
		elseif (isset($_GET['s']) && $_GET['s'] == 'delapp'):
			$app = row("SELECT id FROM applications WHERE uid = ".$user['id']);
			if ($app)
				call("DELETE FROM applications WHERE id = ".$app['id']);
			header("Location: index.php?a=guild&s=join");
		else:
			throwInfo("info", "Nie należysz do żadnej gildii", false);
		endif; ?>
		</div>
	</div>
	<?php endif; ?>
</div>