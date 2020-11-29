<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Mapa świata</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] == 2)
		header("Location: index.php?a=trip")
	?>
	<form action="index.php?a=map" method="GET">
		<div class="form-group">
			<input type="hidden" name="a" value="map"/>
			<div class="input-group">
				<span class="input-group-addon">x</span>
				<?php if (!empty($_GET['x']) || !empty($_GET['y'])): ?>
				<input name="x" type="text" value="<?=$_GET['x'];?>" class="form-control"/>
				<?php else: ?>
				<input name="x" type="text" value="<?=$user['pos_x'];?>" class="form-control"/>
				<?php endif; ?>
				<span class="input-group-addon">y</span>
				<?php if (!empty($_GET['x']) || !empty($_GET['y'])): ?>
				<input name="y" type="text" value="<?=$_GET['y'];?>" class="form-control"/>
				<?php else: ?>
				<input name="y" type="text" value="<?=$user['pos_y'];?>" class="form-control"/>
				<?php endif; ?>
				<span class="input-group-btn">
					<input type="submit" class="btn btn-default" value="Idź"/>
				</span>
			</div>
		</div>
	</form>
	<div>
	<?php if (!empty($_GET['x']) || !empty($_GET['y'])):
		if (($_GET['x'] >= 1) && ($_GET['x'] <= 8) && ($_GET['y'] >= 1) && ($_GET['y'] <= 8)): ?>
		<table align="center" style="text-align: center;">
			<tr>
				<td></td>
				<td><a href="index.php?a=map&x=<?=($_GET['x']);?>&y=<?=($_GET['y']-1);?>"><span class="glyphicon glyphicon-arrow-up"/></a></td>
				<td></td>
			</tr>
			<tr>
				<td><a href="index.php?a=map&x=<?=($_GET['x']-1);?>&y=<?=($_GET['y']);?>"><span class="glyphicon glyphicon-arrow-left"/></a></td>
				<td><?php
					$x = $_GET['x'];
					$y = $_GET['y'];
					if (($x >= 1) && ($y >= 1)) {
						for ($m = $y - 3; $m <= $y + 3; $m++) {
							for ($n = $x - 3; $n <= $x + 3; $n++) {
								//$query = row("SELECT * FROM locations WHERE x = ".$n." AND y = ".$m);
								$query = getLocData($n, $m);
								if ($query):
									if (($n == $user['pos_x']) && ($m == $user['pos_y'])): ?><a href="index.php?a=table"><img src="img/3.png" width="64px" alt="" title="<?=$query['name'];?>" /></a><?php else: ?><a href="index.php?a=loc&id=<?=$query['id'];?>"><img src="img/1.png" width="64px" alt="" title="<?=$query['name'];?>" /></a><?php
									endif;
								else:
									if ($m < 1 || $m > 8 || $n < 1 || $n > 8): ?><img src="img/0.png" width="64px" alt="" /><?php else: ?><img src="img/2_<?=rand(1,2);?>.png" width="64px" alt="" /><?php
									endif;
								endif;
							} ?><br/><?php
						}
					}
				?></td>
				<td><a href="index.php?a=map&x=<?=($_GET['x']+1);?>&y=<?=($_GET['y']);?>"><span class="glyphicon glyphicon-arrow-right"/></a></td>
			</tr>
			<tr>
				<td></td>
				<td><a href="index.php?a=map&x=<?=($_GET['x']);?>&y=<?=($_GET['y']+1);?>"><span class="glyphicon glyphicon-arrow-down"/></a></td>
				<td></td>
			</tr>
		</table>
		<?php else:
		throwInfo('danger', 'Wykroczyłeś poza zakres', false); 
		endif;
	else: ?>
		<table align="center" style="text-align: center;">
			<tr>
				<td></td>
				<td><a href="index.php?a=map&x=<?=($user['pos_x']);?>&y=<?=($user['pos_y']-1);?>"><span class="glyphicon glyphicon-arrow-up"/></a></td>
				<td></td>
			</tr>
			<tr>
				<td><a href="index.php?a=map&x=<?=($user['pos_x']-1);?>&y=<?=($user['pos_y']);?>"><span class="glyphicon glyphicon-arrow-left"/></a></td>
				<td><?php
					$x = $user['pos_x'];
					$y = $user['pos_y'];
					if (($x >= 1) && ($y >= 1)) {
						for ($m = $y - 3; $m <= $y + 3; $m++) {
							for ($n = $x - 3; $n <= $x + 3; $n++) {
								//$query = row("SELECT * FROM locations WHERE x = ".$n." AND y = ".$m);
								$query = getLocData($n, $m);
								if ($query):
									if (($n == $user['pos_x']) && ($m == $user['pos_y'])): ?><a href="index.php?a=table"><img src="img/3.png" width="64px" alt="" title="<?=$query['name'];?>"/></a><?php else: ?><a href="index.php?a=loc&id=<?=$query['id'];?>"><img src="img/1.png" width="64px" alt="" title="<?=$query['name'];?>" /></a><?php
									endif;
								else:
									if ($m < 1 || $m > 8 || $n < 1 || $n > 8): ?><img src="img/0.png" width="64px" alt=""/><?php else: ?><img src="img/2_<?=rand(1,2);?>.png" width="64px" alt=""/><?php endif;
								endif;
							} ?><br/><?php
						}
					}
				?></td>
				<td><a href="index.php?a=map&x=<?=($user['pos_x']+1);?>&y=<?=($user['pos_y']);?>"><span class="glyphicon glyphicon-arrow-right"/></a></td>
			</tr>
			<tr>
				<td></td>
				<td><a href="index.php?a=map&x=<?=($user['pos_x']);?>&y=<?=($user['pos_y']+1);?>"><span class="glyphicon glyphicon-arrow-down"/></a></td>
				<td></td>
			</tr>
		</table>
	<?php endif;?>
	</div>
</div>