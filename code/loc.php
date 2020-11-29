<!--
Created by Rzuwik
-->

<?php
$loc = row("SELECT * FROM locations WHERE id = ".vtxt($_GET['id']));
?>

<div class="panel-heading">
	<h3 class="panel-title">Lokacja</h3>
</div>
<div class="panel-body">
	<div class="panel-body" style="width: 35%; float: left;">
		<div class="panel panel-default">
			<div class="panel-body"><?=avatar(false);?></div>
		</div>
	</div>
	<div class="panel-body" style="width: 65%; float: right;">
		<div class="well">
			<legend><?=loc_name($loc['type']);?> <?=$loc['name'];?></legend>
			<table width="100%">
				<tr>
					<td style="border-bottom:dashed 1px #000">Położenie geograficzne:</td>
					<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><b>X:<?=$loc['x'];?> Y:<?=$loc['y'];?></b></span></td>
				</tr>
				<tr>
					<td style="border-bottom:dashed 1px #000">Minimalne wynagrodzenie:</td>
					<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$loc['re_cash'];?>$</span></td>
				</tr>
				<tr>
					<td style="border-bottom:dashed 1px #000">Minimalna stopa doświadczenia:</td>
					<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$loc['re_xp'];?> pkt.</span></td>
				</tr>
			</table>
		</div>
		<?=($user['status'] == 0) ? '<a href="index.php?a=trip&id='.$loc['id'].'" class="btn btn-primary">Idź do lokacji</a>' : '';?>
	</div>
	<br clear="all"/>
	<a href="index.php?a=map" class="btn btn-primary" style="float: right;">Powrót do mapy</a>
</div>