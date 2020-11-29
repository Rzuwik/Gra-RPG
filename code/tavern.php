<!--
Created by Rzuwik
-->

<script src="API/time.js"></script>
<div class="panel-heading">
	<h3 class="panel-title">Karczma</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] != 0 && $user['status'] != 3):
		throwInfo('danger', 'Aktualnie nie możesz wybrać się do karczmy', false);
	elseif ($user['ap'] >= $user['max_ap']):
		throwInfo('info', 'Twoja postać jest w pełni sił', false);
	else:
		$i = 0;
		$p = 0;
		$tavern = row("SELECT * FROM tavern WHERE uid = ".$user['id']);
		if ($tavern['end'] == 0):
			if (!empty($_POST) && isset($_POST['hours'])) {
				$h = intval($_POST['hours']);
				$time = time();
				$end = $time + 3600 * $h;
				
				if ($user['status'] != 0)
					return;
				if ($user['ap'] >= $user['max_ap'])
					return;
				
				if ($h == 0) {
					$hours = ($user['max_ap'] - $user['ap']) / 2;
					if (fmod($hours, 2) == 0) {
						$p = $hours * 2;
						if ($user['ap'] + $p > $user['max_ap'])
							return;
						else {
							$time = time();
							$end = $time + 3600 * $hours;
							call("UPDATE users SET status = 3 WHERE id = ".$user['id']);
							call("INSERT INTO tavern (uid, start, end, hours, is_int) VALUES (".$user['id'].", ".$time.", ".$end.", ".$hours.", 1)");
							header("Location: index.php?a=tavern");
						}
					} else {
						$hours = floor($hours);
						$p = $hours * 2;
						if ($user['ap'] + $p > $user['max_ap'])
							return;
						else {
							$time = time();
							$end = $time + 3600 * $hours + 1800;
							call("UPDATE users SET status = 3 WHERE id = ".$user['id']);
							call("INSERT INTO tavern (uid, start, end, hours, is_int) VALUES (".$user['id'].", ".$time.", ".$end.", ".$hours.", 0)");
							header("Location: index.php?a=tavern");
						}
					}
				} elseif ($h > 0) {
					$p = $h * 2;
					if ($user['ap'] + $p > $user['max_ap'])
						return;
					else {
						$time = time();
						$end = $time + 3600 * $h;
						call("UPDATE users SET status = 3 WHERE id = ".$user['id']);
						call("INSERT INTO tavern (uid, start, end, hours, is_int) VALUES (".$user['id'].", ".$time.", ".$end.", ".$h.", 1)");
						header("Location: index.php?a=tavern");
					}
				}
			}
			?>
			<div class="well">
				<form action="index.php?a=tavern" method="POST" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-lg-2 control-label">Koszt:</label>
							<div class="col-lg-10">
								<input style="text-align: center;" class="form-control" type="text" placeholder="10$/godzinę - 2 pkt. akcji" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Godzin:</label>
							<div class="col-lg-10">
								<select name="hours" class="form-control">
									<?php $time = ($user['max_ap'] - $user['ap']) / 2;?>
									<option value="0">Pełny odpoczynek - <?=$time;?> godz.</option>
									<?php while ($user['max_ap'] > ($user['ap'] + $p)): $i++; $p += 2;?>
									<?php if (($p + $user['ap'] + 1) <= $user['max_ap']): ?>
									<option value="<?=$i;?>"><?=$i;?> godz.</option>
									<?php endif; ?>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-6">
								<button type="submit" class="btn btn-primary">Odpocznij</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<?php
		else:
			$time = time();
			if ($time >= $tavern['end']):
				if ($tavern['is_int'] == 0) {
					call("UPDATE users SET ap = ap + (".$tavern['hours']." * 2) + 1, cash = cash - (10 * ".$tavern['hours'].") + 5, status = 0 WHERE id = ".$user['id']);
					call("DELETE FROM tavern WHERE uid = ".$user['id']);
				} else {
					call("UPDATE users SET ap = ap + (".$tavern['hours']." * 2), cash = cash - (10 * ".$tavern['hours']."), status = 0 WHERE id = ".$user['id']);
					call("DELETE FROM tavern WHERE uid = ".$user['id']);
				}
				header("Location: index.php?a=tavern");
			else:
				if (isset($_GET['stop'])) {
					call("UPDATE users SET status = 0 WHERE id = ".$user['id']);
					call("DELETE FROM tavern WHERE uid = ".$user['id']);
					header("Location: index.php?a=tavern");
				}
				$remains = $tavern['end'] - $time; ?>
			<div class="well">
				<form action="index.php?a=tavern&stop" method="POST" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<div class="col-lg-12">
								<div class="progress">
									<div id="pasek" class="progress-bar progress-bar-info" style="width: 100%;">
										<span id="zegar">abc</span>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-5">
								<button type="submit" class="btn btn-primary">Wyjdź z karczmy</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<script type="text/javascript">postep(<?=$time;?>, <?=$tavern['start'];?>, <?=$tavern['end'];?>)</script>
			<script type="text/javascript">liczCzas(<?=$remains;?>)</script>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>