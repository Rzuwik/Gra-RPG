<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Lista zmian</h3>
</div>
<div class="panel-body">
	<table class="table table-striped table-hover ">
		<thead>
			<tr>
				<th>#</th>
				<th>Wersja</th>
				<th>Data</th>
				<th>Komentarzy</th>
			</tr>
		</thead>
		<?php
		$list = call("SELECT * FROM changelog ORDER BY id DESC");
		$i = 0;
		while($row = mysqli_fetch_array($list)):
			$i++;
			$comments = row("SELECT count(*) AS ilosc FROM comments WHERE cid = ".$row['id']); ?>
		<tbody>
			<?=($i == 1) ? '<tr class="info">' : '<tr>';?>
				<td><?=$i;?></td>
				<td><a href="index.php?a=version&b=<?=$row['id'];?>"><?=$row['ver'];?></a></td>
				<td><?=$row['date'];?></td>
				<td><?=$comments['ilosc'];?></td>
			</tr>
		</tbody>
		<?php endwhile; ?>
	</table>
</div>