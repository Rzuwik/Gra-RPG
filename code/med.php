<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Punkt pierwszej pomocy</h3>
</div>
<div class="panel-body">
	<?php
	if (!empty($_POST)) {
		if (!isset($_POST['points'])) {
			throwInfo('danger', 'Wypełnij pola porawnie!', true);
		} else {
			$points = $_POST['points'];
			if ($points < 1)
				throwInfo('danger', 'Podano nieprawidłową wartość!', true);
			elseif ($points > $user['max_hp'] - $user['hp'])
				throwInfo('danger', 'Podano za dużą wartość!', true);
			elseif ($points * 10 > $user['cash'])
				throwInfo('danger', 'Nie masz tylu pieniędzy!', true);
			else {
				call("UPDATE users SET cash = cash - ".($points * 10).", hp = hp + ".$points." WHERE id = ".$user['id']);
				throwInfo('success', 'Zregenerowano <b>'.$points.'</b> punktów zdrowia i zapłacono za leczenie <b>'.($points * 10).'$</b>.', true);
			}
		}
	}
	
	// Warunek sprawdzający czy bohater jest ranny
	if ($user['hp'] < $user['max_hp']): ?>
	<div class="well">
		<form action="index.php?a=med" method="POST" class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<label class="col-lg-2 control-label">Koszt:</label>
					<div class="col-lg-10">
						<input style="text-align: center;" class="form-control" type="text" placeholder="10$ - 1 pkt. zdrowia" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">Punkty:</label>
					<div class="col-lg-10">
						<input type="text" name="points" style="text-align: center;" class="form-control" placeholder="Wpisz ilość punktów">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-6">
						<button type="submit" class="btn btn-primary">Wylecz</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<?php else: throwInfo('info', 'Twoja postać nie wymaga leczenia.', false); endif; ?>
</div>