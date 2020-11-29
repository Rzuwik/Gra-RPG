<!--
Created by Rzuwik
-->

<div class="panel-heading">
	<h3 class="panel-title">Sklep</h3>
</div>
<div class="panel-body">
	<?php if ($user['status'] == 0): ?>
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=shop&b=weapon" class="btn btn-default">Bronie</a>
		<a href="index.php?a=shop&b=armor" class="btn btn-default">Zbroje</a>
		<a href="index.php?a=shop&b=neck" class="btn btn-default">Talizmany</a>
		<a href="index.php?a=shop&b=potion" class="btn btn-default">Mikstury</a>
	</div>
	<br/>
	<?php endif;
	if ($user['status'] == 0):
		// Akcje
		if (isset($_POST['item_id'])) {
			$item_id = $_POST['item_id'];
			if (!is_numeric($item_id) && $item_id <= 0)
				header("Location: index.php?a=shop");
			else {
				$item = row("SELECT * FROM items WHERE id = ".$item_id);
				if (empty($item) || $item['lvl'] > $user['lvl'])
					header("Location: index.php?a=shop");
				else {
					$inv = row("SELECT count(*) AS cap FROM inventory WHERE uid = ".$user['id']);
					if ($inv['cap'] > 13)
						throwInfo('success', 'Nie pomieścisz już więcej w plecaku', true);
					else {
						if ($item['cost'] > $user['cash'])
							throwInfo('success', 'Nie masz wystarczającej ilości gotówki', true);
						else {
							call("INSERT INTO inventory (obj, uid) VALUES (".$item['id'].", ".$user['id'].")");
							call("UPDATE users SET cash = cash - ".$item['cost']." WHERE id = ".$user['id']);
							throwInfo('success', 'Przedmiot zakupiony za '.$item['cost'].'$', true);
						}
					}
				}
			}
		} elseif (isset($_GET['sell'])) {
			$item = vtxt($_GET['item']);
			if (!is_numeric($item) && $item <= 0)
				header("Location: index.php?a=shop");
			$data = row("SELECT * FROM inventory WHERE uid = ".$user['id']." AND id = ".$item);
			if (!$data || $data['used'] == 1)
				throwInfo('danger', 'Błąd (Brak przedmiotu lub przedmiot używany)', true);
			else {
				$cost = row("SELECT cost FROM items WHERE id = ".$data['obj']);
				if (!$cost)
					throwInfo('danger', 'Błąd (Brak danych o cenie przedmiotu)', true);
				else {
					$cost = floor(($cost['cost'] / 2) * ($data['stamina'] / 100));
					call("DELETE FROM inventory WHERE id = ".$data['id']);
					call("UPDATE users SET cash = cash + ".$cost." WHERE id = ".$zapytanie['id']);
					throwInfo('success', 'Sprzedano przedmiot za '.$cost.'$', true);
				}
			}
		}
		
		// Kategorie
		if (isset($_GET['b']))
			$type = vtxt($_GET['b']);
		else
			$type = 'weapon';
		
		// Filtrowanie
		if (!isset($_GET['s'])) {
			$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY lvl");
			$s = 2;
		} elseif (isset($_GET['o'])) {
			if ($_GET['o'] == 1) {
				$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY ".vtxt($_GET['s']));
				$s = 2;
			} elseif ($_GET['o'] == 2) {
				$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY ".vtxt($_GET['s'])." DESC");
				$s = 1;
			} else
				$s = 2;
		} else {
			$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY ".vtxt($_GET['s']));
			$s = 2;
		}
		
		// Licznik przedmiotów
		$i = 1;
		
		// Wybieramy ekwipunek
		$object = "SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 0 ORDER BY stamina";
		
		// Czy są jakieś przedmioty
		if (empty($items) || !isset($items) || !($items) || $items->num_rows == 0):
			throwInfo('info', 'Brak ofert sprzedaży, prosimy spróbować później', false);
		else:?>
	<div class="well well-sm">
		<?php if ($type == 'weapon'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=min_dmg&o=<?=$s;?>">Obrażenia</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php while ($s = mysqli_fetch_assoc($items)): ?>
					<tr>
						<td><?=$i++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['min_dmg'];?> / <?=$s['max_dmg'];?> (<?=floor(avg(array($s['min_dmg'], $s['max_dmg'])));?>)</td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'armor'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=resist&o=<?=$s;?>">Odporność (w %)</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php while ($s = mysqli_fetch_assoc($items)): ?>
					<tr>
						<td><?=$i++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['resist'];?></td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'neck'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=stat&o=<?=$s;?>">Umiejętność</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=val&o=<?=$s;?>">Bonus</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php while ($s = mysqli_fetch_assoc($items)): ?>
					<tr>
						<td><?=$i++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['stat'];?></td>
						<td><?=$s['val'];?> pkt.</td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
	<?php if (row($object)): ?>
	<div class="well well-sm">
		<center>EKWIPUNEK</center>
		<div width="100%" height="200px">
		<?php $object = call($object);
			while ($s = mysqli_fetch_assoc($object)): ?>
			<div style="width: 100px; float:left; margin-right:6px;">
				<a href="index.php?a=shop&sell=<?=$s['iid'];?>">
					<img style="display: block; margin: 0 auto;" src="img/<?=$s['type'];?>/<?=$s['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<?php if ($s['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
						<?php else: ?>
						<div class="progress-bar" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
						<?php endif; ?>
					</div>
					<div class="form-group" style="margin-top:5px; margin-bottom:0px;">
						<input style="text-align:center;" class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=floor(($s['cost'] / 2) * ($s['stamina'] / 100));?>$">
					</div>
				</a>
			</div>
		<?php endwhile; ?>
			<br clear="both"/>
		</div>
	</div>
	<?php endif; endif; else: throwInfo('danger', 'Zakończ aktualną akcję, aby wejść do sklepu', false); endif; ?>
</div>