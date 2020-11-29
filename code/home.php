<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Strona główna</h3>
</div>
<div class="panel-body">	
<?php
throwInfo('info', 'Silnik został zoptymalizowany do znośnego poziomu.</a>');

$changes = call("SELECT * FROM changelog ORDER BY id DESC LIMIT 3");
$i = 0;
while ($row = mysqli_fetch_array($changes)):
	$i++;
	$comments = row("SELECT count(*) AS ilosc FROM comments WHERE cid = ".$row['id']);
	if ($i == 1):
		$style = ' style="color: white;"'; ?>
	<div class="panel panel-primary">
	<?php else:
		$style = ''; ?>
	<div class="panel panel-default">
	<?php endif; ?>
		<div class="panel-heading"><a<?=$style;?> href="index.php?a=version&b=<?=$row['id'];?>"><b>Wersja: <i><?=$row['ver'];?></i></b></a><span style="float: right;"><?=$row['date'];?></span></div>
		<div class="panel-body">
			<?=$row['content'];?>
		</div>
		<div class="panel-footer">Komentarzy: <?=$comments['ilosc'];?></div>
	</div>
<?php endwhile; ?>
</div>