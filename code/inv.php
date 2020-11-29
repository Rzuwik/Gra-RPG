<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Ekwipunek</h3>
</div>
<div class="panel-body">
	<?php
	if (!empty($_GET['item'])) {
		$item = $_GET['item'];
		if (!empty($item) && is_numeric($item)) {
			$data = row("SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND inventory.id = ".$item);
			if ($data) {
				if ($data['used'] == 1) {
					call("UPDATE inventory SET used = 0 WHERE id = ".$data['iid']);
					throwInfo('success', 'Zdjęto przedmiot', true);
				} else {
					if ($data['stamina'] < 1)
						throwInfo('danger', 'Ten przedmiot jest zniszczony', true);
					else {
						$is_wearing = row("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = '".$data['type']."'");
						if ($is_wearing)
							throwInfo('danger', 'Postać już ma założony jakiś przedmiot', true);
						else {
							call("UPDATE inventory SET used = 1 WHERE id = ".$data['iid']);
							throwInfo('success', 'Założono przedmiot', true);
						}
					}
				}
			}
		}
	}
	
	$weapon = arr("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = 'weapon' LIMIT 1");
	$armor = arr("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = 'armor' LIMIT 1");
	$neck = arr("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = 'neck' LIMIT 1");
	?>
	<div class="well well-sm">
		<div class="panel panel-default" style="width:150px; float:left;">
			<div class="panel-body" style="text-align:center;">
				Broń podstawowa<br/>
				<?php if ($weapon): ?>
				<a href="index.php?a=inv&item=<?=$weapon[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$weapon['type'];?>/<?=$weapon['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
					<?php if ($weapon['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width:<?=$weapon['stamina'];?>%;"><span><?=$weapon['stamina'];?>%</span></div>
					<?php else: ?>
						<div class="progress-bar" style="width:<?=$weapon['stamina'];?>%;"><span><?=$weapon['stamina'];?>%</span></div>
					<?php endif; ?>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:500px; float:right;">
			<div class="panel-body" style="text-align:left;">
				<?php if ($weapon): ?>
				<div class="form-group">
					<label class="control-label" for="inputSmall">Obrażenia</label>
					<input class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=$weapon['min_dmg'];?>/<?=$weapon['max_dmg'];?>">
					<label class="control-label" for="inputSmall">Wytrzymałość</label>
					<input class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=$weapon['stamina'];?>%">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<br clear="both"/>
		<div class="panel panel-default" style="width:150px; float:left;">
			<div class="panel-body" style="text-align:center;">
				Zbroja<br/>
				<?php if ($armor): ?>
				<a href="index.php?a=inv&item=<?=$armor[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$armor['type'];?>/<?=$armor['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$armor['stamina'];?>%;"><span><?=$armor['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:500px; float:right;">
			<div class="panel-body" style="text-align:left;">
				<?php if ($armor): ?>
				<div class="form-group">
					<label class="control-label" for="inputSmall">Odporność</label>
					<input class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=$armor['resist'];?>%">
				</div>
				<div class="form-group">
					<label class="control-label" for="inputSmall">Wytrzymałość</label>
					<input class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=$armor['stamina'];?>%">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<br clear="both"/>
		<div class="panel panel-default" style="width:150px; float:left;">
			<div class="panel-body" style="text-align:center;">
				Talizman<br/>
				<?php if ($neck): ?>
				<a href="index.php?a=inv&item=<?=$neck[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$neck['type'];?>/<?=$neck['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$neck['stamina'];?>%;"><span><?=$neck['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:500px;float:right;">
			<div class="panel-body" style="text-align:left;">
				<?php if ($neck): ?>
				<div class="form-group">
					<label class="control-label" for="inputSmall">Wytrzymałość</label>
					<input class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=$neck['stamina'];?>%">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<br clear="both"/>
	</div>
	<?php
	$object = "SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 0 ORDER BY stamina";
	if (row($object)):
		$object = call($object); ?>
	<div class="well well-sm">
		<center>EKWIPUNEK</center>
		<div width="100%" height="200px">
		<?php while ($s = mysqli_fetch_array($object)): ?>
			<div style="width: 100px; float:left; margin-right:6px;">
				<a href="index.php?a=inv&item=<?=$s['iid'];?>">
					<img style="display: block; margin: 0 auto;" src="img/<?=$s['type'];?>/<?=$s['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
					<?php if ($s['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
					<?php else: ?>
						<div class="progress-bar" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
					<?php endif; ?>
					</div>
				</a>
			</div>
		<?php endwhile; ?>
			<br clear="both"/>
		</div>
	</div>
	<?php endif; ?>
</div>