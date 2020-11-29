<!--
Created by Rzuwik
-->

<?php
$location = getLocData($user['pos_x'], $user['pos_y']);

$sql = "SELECT * FROM users WHERE pos_x = ".$user['pos_x']." AND pos_y = ".$user['pos_y'];
if (empty($_GET['s']))
	$players = call($sql." ORDER BY lvl");
elseif (!empty($_GET['o'])) {
	if ($_GET['o'] == 1)
		$players = call($sql." ORDER BY ".vtxt($_GET['s']));
	if ($_GET['o'] == 2)
		$players = call($sql." ORDER BY ".vtxt($_GET['s'])." DESC");
} else
	$players = call($sql." ORDER BY ".vtxt($_GET['s']));

if (empty($_GET['o']))
	$_GET['o'] = 0;
if ($_GET['o'] == 2)
	$s = 1;
else
	$s = 2;
?>

<div class="panel-heading">
	<h3 class="panel-title">
		<b><?=loc_name($location['type']);?>&nbsp;<?=$location['name'];?></b>
	</h3>
</div>
<div class="panel-body">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Gracze w mieście</b></h3>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-hover ">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="index.php?a=rank&s=login&o=<?php echo $s; ?>">Login</a></th>
						<th><a href="index.php?a=rank&s=lvl&o=<?php echo $s; ?>">Poziom</a></th>
						<th><a href="index.php?a=rank&s=allxp&o=<?php echo $s; ?>">Doświadczenie</a></th>
						<th><a href="index.php?a=rank&s=str&o=<?php echo $s; ?>">SI</a></th>
						<th><a href="index.php?a=rank&s=dex&o=<?php echo $s; ?>">ZR</a></th>
						<th><a href="index.php?a=rank&s=sta&o=<?php echo $s; ?>">WY</a></th>
						<th><a href="index.php?a=rank&s=intell&o=<?php echo $s; ?>">IN</a></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					while ($row = mysqli_fetch_array($players)): ?>
					<tr>
						<td><?=$i++;?></td>
						<?php if ($row['guild'] > 0):
						$guild = row("SELECT tag FROM guilds WHERE id = ".$row['guild']); ?>
						<td><a href="index.php?a=stats&g=<?=$row['guild'];?>"><?=$guild['tag'];?></a> <a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
						<?php else: ?>
						<td><a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
						<?php endif; ?>
						<td><?=$row['lvl'];?></td>
						<td><?=$row['allxp'];?></td>
						<td><?=$row['str'];?></td>
						<td><?=$row['dex'];?></td>
						<td><?=$row['sta'];?></td>
						<td><?=$row['intell'];?></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>