<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Arena</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] != 0 || $user['hp'] < 1 || $user['ap'] < 1)
		throwInfo('danger', 'Upewnij się, że spełniasz wszystkie warunki, aby wejść na arenę!', false);
	elseif (!isset($_GET['nick'])) { ?> 
	<div class="well">
		<form action="index.php" method="GET" class="form-horizontal">
			<fieldset>
				<input type="hidden" name="a" value="arena">
				<div class="form-group">
					<label class="col-lg-2 control-label">Nick gracza</label>
					<div class="col-lg-10">
						<input type="text" name="nick" style="text-align: center;" class="form-control" placeholder="Wpisz nick przeciwnika">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-6">
						<button type="submit" class="btn btn-primary">Walcz</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<?php } else {
		$nick = vtxt($_GET['nick']);
		if (empty($nick))
			throwInfo('danger', 'Nie wybrano przeciwnika!', false);
		elseif (strtolower($user['login']) == strtolower($nick))
			throwInfo('danger', 'Nie możesz walczyć ze sobą!', false);
		elseif ($user['ap'] < 1)
			throwInfo('danger', 'Nie masz siły na walkę na arenie!', false);
		else {
			$enemy = row("SELECT * FROM users WHERE login = '".$nick."'");
			if (empty($enemy))
				throwInfo('danger', 'Podany gracz nie istnieje!', false);
			elseif ($enemy['status'] != 0)
				throwInfo('danger', 'Podany gracz jest teraz czymś zajęty!', false);
			elseif ($user['pos_x'] != $enemy['pos_x'] || $user['pos_y'] != $enemy['pos_y'])
				throwInfo('danger', 'Tego gracza nie ma w tym mieście!', false);
			elseif ($user['guild'] != 0 && $enemy['guild'] != 0 && $user['guild'] == $enemy['guild'])
				throwInfo('danger', 'Nie możesz atakować członka swojej gildii!', false);
			else {
				$pprocent = ($user['hp'] >= $user['max_hp'] * 0.25) ? true : false;
				$eprocent = ($enemy['hp'] >= $enemy['max_hp'] * 0.25) ? true : false;
				if (!$pprocent)
					throwInfo('danger', 'Jesteś ranny! Ulecz się zanim się z kimś pobijesz!', false);
				elseif (!$eprocent)
					throwInfo('danger', 'Przeciwnik jest ranny! Nie możesz go zaatakować!', false);
				else {
					$pdamage = getPlayerDamage($user['id']);
					if (is_array($pdamage))
						$pdam = $pdamage[0].' / '.$pdamage[1].' ('.floor(avg($pdamage)).')';
					else
						$pdam = $pdamage;
					
					$edamage = getPlayerDamage($enemy['id']);
					if (is_array($edamage))
						$edam = $edamage[0].' / '.$edamage[1].' ('.floor(avg($edamage)).')';
					else
						$edam = $edamage;
					
					$pdodge = getPlayerDodge($user['id']);
					$edodge = getPlayerDodge($enemy['id']);
					
					$pcritical = getPlayerCritical($user['id']);
					$ecritical = getPlayerCritical($enemy['id']);
					
					$ptag = ($user['guild'] != 0) ? row("SELECT tag FROM guilds WHERE id = ".$user['guild']) : "";
					$etag = ($enemy['guild'] != 0) ? row("SELECT tag FROM guilds WHERE id = ".$enemy['guild']) : "";
					?>
					
					<div class="well well-sm" style="width: 45%; float: left;"><?=avatar($user['id']);?></div>
					<div class="well well-sm" style="width: 45%; float: right;"><?=avatar($enemy['id']);?></div>
					
					<div class="panel-body" style="width: 45%; float: left;">
						<div class="well">
							<legend>Atakujący</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Nick:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$ptag['tag'];?> <?=$user['login'];?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$user['hp'];?> / <?=$user['max_hp'];?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pdam;?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Unik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pdodge;?>%</span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pcritical;?>%</span></td>
								</tr>
							</table>
						</div>
					</div>
					
					<div class="panel-body" style="width: 45%; float: right;">
						<div class="well">
							<legend>Broniący się</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Nick:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$etag['tag'];?> <?=$enemy['login'];?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$enemy['hp'];?> / <?=$enemy['max_hp'];?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$edam;?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Unik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$edodge;?>%</span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$ecritical;?>%</span></td>
								</tr>
							</table>
						</div>
					</div>
					<br clear="both"/>
					
					<?php
					$i = 0;
					$pscore = 0;
					$escore = 0;
					
					while ($user['hp'] > 0 && $enemy['hp'] > 0) {
						$i++;
						?>
					<div class="panel panel-default">
						<div class="panel-heading" style="text-align: center;"><b>Runda: <?=$i;?></b></div>
						<div class="panel-body">
						<?php
						$pmiss = ($edodge <= rand(1, 100)) ? false : true;
						if ($pmiss)
							throwInfo('success', 'Spudłowałeś!', false);
						else {
							if (is_array($pdamage))
								$patt = rand($pdamage[0], $pdamage[1]);
							else
								$patt = rand($pdamage-1, $pdamage+1);
							
							if ($pcritical >= rand(1, 100)) {
								$patt *= 2;
								throwInfo('success', 'Uderzenie krytyczne!', false);
							}
							
							$enemy['hp'] -= $patt;
							$pscore += $patt;
							throwInfo('success', 'Zadałeś '.$patt.' obrażeń!', false);
						}
						
						if ($user['hp'] < 1 || $enemy['hp'] < 1) {
							echo '
						</div>
					</div>
							';
							break;
						}
						
						$emiss = ($pdodge <= rand(1, 100)) ? false : true;
						if ($emiss)
							throwInfo('danger', 'Wróg spudłował!', false);
						else {
							if (is_array($edamage)) $eatt = rand($edamage[0], $edamage[1]);
							else $eatt = rand($edamage-1, $edamage+1);
							
							if ($ecritical >= rand(1, 100)) {
								$eatt *= 2;
								throwInfo('danger', 'Uderzenie krytyczne!', false);
							}
							
							$user['hp'] -= $eatt;
							$escore += $eatt;
							throwInfo('danger', 'Wróg zadał Ci '.$eatt.' obrażeń!', false);
						}
						
						echo '
						</div>
					</div>
						';
					}
					
					if ($pscore == $escore) {
						if ($user['guild'] != 0) {
							call("UPDATE guilds SET rep = rep + 2 WHERE id = ".$user['guild']);
							$prep = '<tr>
								<td style="border-bottom:dashed 1px #000">Reputacja:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+2</span></td>
							</tr>';
						} else $prep = '';
						if ($enemy['guild'] != 0) {
							call("UPDATE guilds SET rep = rep + 2 WHERE id = ".$enemy['guild']);
							$erep = '<tr>
								<td style="border-bottom:dashed 1px #000">Reputacja:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+2</span></td>
							</tr>';
						} else $erep = '';
						
						$xp_remis = 5;
						
						$pmsg = array(
							'login' => $etag['tag'].' '.$enemy['login'],
							'max_hp' => $enemy['max_hp'],
							'dam' => $edam,
							'dodge' => $edodge,
							'critical' => $ecritical,
							'rounds' => $i,
							'score' => $pscore,
							'xp' => $xp_remis,
							'rep' => $prep
						);
						
						$emsg = array(
							'login' => $ptag['tag'].' '.$user['login'],
							'max_hp' => $user['max_hp'],
							'dam' => $pdam,
							'dodge' => $pdodge,
							'critical' => $pcritical,
							'rounds' => $i,
							'score' => $escore,
							'xp' => $xp_remis,
							'rep' => $erep
						);
						
						sysMail($user['id'], '[REMIS]Raport z walki', array(true, $pmsg), 'arena');
						sysMail($enemy['id'], '[REMIS]Raport z walki', array(false, $emsg), 'arena');
						
						call("UPDATE users SET hp = ".floor($user['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5, ap = ap - 1 WHERE id = ".$user['id']);
						call("UPDATE users SET hp = ".floor($enemy['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5 WHERE id = ".$enemy['id']);
						call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
						call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
						?>
						
					<div class="panel-body" style="width: 60%; margin: 0 auto;">
						<div class="well">
							<legend>REMIS!</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Wynik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pscore;?> pkt.</span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">5 pkt.</span></td>
								</tr>
								<?=($user['guild'] != 0) ? $prep : '';?>
							</table>
						</div>
					</div>
						
						<?php
					} else {
						if ($pscore > $escore) {
							if ($user['guild'] != 0) {
								call("UPDATE guilds SET rep = rep + 4 WHERE id = ".$user['guild']);
								$prep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+4</span></td>
								</tr>';
							}
							if ($enemy['guild'] != 0) {
								$rep = row("SELECT rep FROM guilds WHERE id = ".$enemy['guild']);
								if ($rep['rep'] >= 4) {
									call("UPDATE guilds SET rep = rep - 4 WHERE id = ".$enemy['guild']);
									$points = 4;
								} elseif ($rep['rep'] > 0 && $rep['rep'] < 4) {
									call("UPDATE guilds SET rep = rep - ".$rep['rep']." WHERE id = ".$enemy['guild']);
									$points = $rep['rep'];
								}
								$erep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">-'.$points.'</span></td>
								</tr>';
							}
							
							$xp_win = ($user['lvl'] * 5);
							$xp_lose = 5;
							
							$pmsg = array(
								'login' => $etag['tag'].' '.$enemy['login'],
								'max_hp' => $enemy['max_hp'],
								'dam' => $edam,
								'dodge' => $edodge,
								'critical' => $ecritical,
								'rounds' => $i,
								'score' => $pscore.' do '.$escore,
								'xp' => $xp_win,
								'rep' => $prep
							);
							
							$emsg = array(
								'login' => $ptag['tag'].' '.$user['login'],
								'max_hp' => $user['max_hp'],
								'dam' => $pdam,
								'dodge' => $pdodge,
								'critical' => $pcritical,
								'rounds' => $i,
								'score' => $escore.' do '.$pscore,
								'xp' => $xp_lose,
								'rep' => $erep
							);
							
							sysMail($user['id'], '[WYGRANA]Raport z walki', array(true, $pmsg), 'arena');
							sysMail($enemy['id'], '[PORAŻKA]Raport z walki', array(false, $emsg), 'arena');
							
							if ($user['hp'] > 0)
								call("UPDATE users SET hp = ".$user['hp'].", allxp = allxp + ".($user['lvl'] * 5).", xp = xp + ".($user['lvl'] * 5).", ap = ap - 1 WHERE id = ".$user['id']);
							else
								call("UPDATE users SET hp = ".floor($user['max_hp'] * 0.1).", allxp = allxp + ".($user['lvl'] * 5).", xp = xp + ".($user['lvl'] * 5).", ap = ap - 1 WHERE id = ".$user['id']);
							
							call("UPDATE users SET hp = ".floor($enemy['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5 WHERE id = ".$enemy['id']);
							call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
							call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
							?>
							
					<div class="panel-body" style="width: 45%; float: left;">
						<div class="well">
							<legend>WYGRANA!</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Wynik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pscore;?> do <?=$escore;?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=($user['lvl'] * 5);?> pkt.</span></td>
								</tr>
								<?=($user['guild'] != 0) ? $prep : '';?>
							</table>
						</div>
					</div>
					<div class="well well-sm" style="width: 45%; float: right;"><?=avatar($user['id']);?></div>
					<br clear="both"/>
							<?php
						} else {
							if ($enemy['guild'] != 0) {
								call("UPDATE guilds SET rep = rep + 4 WHERE id = ".$enemy['guild']);
								$erep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+4</span></td>
								</tr>';
							}
							
							if ($user['guild'] != 0) {
								$rep = row("SELECT rep FROM guilds WHERE id = ".$user['guild']);
								if ($rep['rep'] >= 4) {
									call("UPDATE guilds SET rep = rep - 4 WHERE id = ".$user['guild']);
									$points = 4;
								} elseif ($rep['rep'] > 0 && $rep['rep'] < 4) {
									call("UPDATE guilds SET rep = rep - ".$rep['rep']." WHERE id = ".$user['guild']);
									$points = $rep['rep'];
								}
								$prep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">-'.$points.'</span></td>
								</tr>';
							}
							
							$xp_win = ($user['lvl'] * 5);
							$xp_lose = 5;
							
							$pmsg = array(
								'login' => $etag['tag'].' '.$enemy['login'],
								'max_hp' => $enemy['max_hp'],
								'dam' => $edam,
								'dodge' => $edodge,
								'critical' => $ecritical,
								'rounds' => $i,
								'score' => $pscore.' do '.$escore,
								'xp' => $xp_lose,
								'rep' => $prep
							);
							
							$emsg = array(
								'login' => $ptag['tag'].' '.$user['login'],
								'max_hp' => $user['max_hp'],
								'dam' => $pdam,
								'dodge' => $pdodge,
								'critical' => $pcritical,
								'rounds' => $i,
								'score' => $escore.' do '.$pscore,
								'xp' => $xp_win,
								'rep' => $erep
							);
							
							sysMail($user['id'], '[PORAŻKA]Raport z walki', array(true, $pmsg), 'arena');
							sysMail($enemy['id'], '[WYGRANA]Raport z walki', array(false, $emsg), 'arena');
							
							if ($enemy['hp'] > 0)
								call("UPDATE users SET hp = ".$enemy['hp'].", allxp = allxp + ".($enemy['lvl'] * 5).", xp = xp + ".($enemy['lvl'] * 5)." WHERE id = ".$enemy['id']);
							else
								call("UPDATE users SET hp = ".floor($enemy['max_hp'] * 0.1).", allxp = allxp + ".($enemy['lvl'] * 5).", xp = xp + ".($enemy['lvl'] * 5)." WHERE id = ".$enemy['id']);
							
							call("UPDATE users SET hp = ".floor($user['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5, ap = ap - 1 WHERE id = ".$user['id']);
							call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
							call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
							?>
							
					<div class="panel-body" style="width: 45%; float: left;">
						<div class="well">
							<legend>PRZEGRANA!</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Wynik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$escore;?> do <?=$pscore;?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">5 pkt.</span></td>
								</tr>
								<?=($user['guild'] != 0) ? $prep : '';?>
							</table>
						</div>
					</div>
					<div class="well well-sm" style="width: 45%; float: right;"><?=avatar($enemy['id']);?></div>
					<br clear="both"/>
							
							<?php
						}
					}
				}
			}
		}
		?><a href="index.php?a=arena" class="btn btn-primary">Powrót</a>
	<?php } ?>
</div>