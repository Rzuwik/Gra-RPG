<!--
Created by Rzuwik
-->

<script src="API/time.js"></script>
<div class="panel-heading">
	<h3 class="panel-title">Praca</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] != 0 && $user['status'] != 1):
		throwInfo('danger', 'Aktualnie nie możesz pracować', false);
	else:
		$location = getLocData($user['pos_x'], $user['pos_y']);
		if (!$location):
			throwInfo('danger', 'Błąd pobierania danych lokacji', true);
		else:
			$work = row("SELECT * FROM work WHERE uid = ".$user['id']);
			if ($work['end'] == 0):
				if (!empty($_POST) && isset($_POST['time'])) {
					$h = intval($_POST['time']);
					$time = time();
					$end = $time + 3600 * $h;
					if ($user['ap'] < $h)
						throwInfo('danger', 'Nie masz siły na taką pracę!', true);
					else {
						call("UPDATE users SET status = 1 WHERE id = ".$user['id']);
						call("INSERT INTO work (uid, start, end, hours) VALUES (".$user['id'].", ".$time.", ".$end.", ".$h.")");
						header("Location: index.php?a=work");
					}
				} ?>
	<div class="well">
		<form action="index.php?a=work" method="POST" class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<label class="col-lg-2 control-label" for="praca">1 godzina:</label>
					<div class="col-lg-10">
						<input style="text-align: center;" class="form-control" id="praca" type="text" placeholder="<?=$location['re_cash'];?>$ / <?=$location['re_xp'];?> pkt. doświadczenia" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Czas pracy</label>
					<div class="col-lg-10">
						<select name="time" class="form-control">
							<option value="1">1 godz.</option>
							<option value="2">2 godz.</option>
							<option value="3">3 godz.</option>
							<option value="4">4 godz.</option>
							<option value="5">5 godz.</option>
							<option value="6">6 godz.</option>
							<option value="7">7 godz.</option>
							<option value="8">8 godz.</option>
							<option value="9">9 godz.</option>
							<option value="10">10 godz.</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-5">
						<button type="submit" class="btn btn-primary">Pracuj</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
			<?php else:
				$time = time();
				if ($time >= $work['end']):
					$location = getLocData($user['pos_x'], $user['pos_y']);
					call("UPDATE users SET xp = xp + hours * ".$location['re_xp'].", allxp = allxp + hours * ".$location['re_xp'].", ap = ap - hours, cash = cash + hours * ".$location['re_cash'].", status = 0 WHERE id = ".$user['id']);
					call("DELETE FROM work WHERE uid = ".$user['id']);
					header("Location: index.php?a=work");
				else:
					if (isset($_GET['stop'])) {
						call("UPDATE users SET status = 0 WHERE id = ".$user['id']);
						call("DELETE FROM work WHERE uid = ".$user['id']);
						header("Location: index.php?a=work");
					}
					$remains = $work['end'] - $time; ?>
	<div class="well">
		<form action="index.php?a=work&stop" method="POST" class="form-horizontal">
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
						<button type="submit" class="btn btn-primary">Zakończ pracę</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<script type="text/javascript">postep(<?=$time;?>, <?=$work['start'];?>, <?=$work['end'];?>)</script>
	<script type="text/javascript">liczCzas(<?=$remains;?>)</script>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>