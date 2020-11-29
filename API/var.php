<!--
Created by Rzuwik
-->

<?php
$full = 100;
$fhp = $user['max_hp'];
$hp = $user['hp'];
$whp = ($hp / $fhp) * 100;
if (is_float($whp) == false) $pbhp = $whp; else $pbhp = round($whp, 0);

$fpa = $user['max_ap'];
$pa = $user['ap'];
$wpa = ($pa / $fpa) * 100;
if (is_float($wpa) == false) $pbpa = $wpa; else $pbpa = round($wpa, 0);

$fpd = $user['max_xp'];
$pd = $user['xp'];
$wpd = ($pd / $fpd) * 100;
if (is_float($wpd) == false) $pbpd = $wpd; else $pbpd = round($wpd, 0);

if (!empty($_GET['a']))
	$hl = 'index.php?a='.$_GET['a'];
else
	$hl = 'index.php?';

if ($user['sp'] > 0) {
	$sibutton = '
		<span class="input-group-btn">
			<a class="btn btn-default" href="'.$hl.'&skill=str"><span class="glyphicon glyphicon-upload"/></a>
		</span>
	';
	$zrbutton = '
		<span class="input-group-btn">
			<a class="btn btn-default" href="'.$hl.'&skill=dex"><span class="glyphicon glyphicon-upload"/></a>
		</span>
	';
	$wybutton = '
		<span class="input-group-btn">
			<a class="btn btn-default" href="'.$hl.'&skill=sta"><span class="glyphicon glyphicon-upload"/></a>
		</span>
	';
	$inbutton = '
		<span class="input-group-btn">
			<a class="btn btn-default" href="'.$hl.'&skill=intell"><span class="glyphicon glyphicon-upload"/></a>
		</span>
	';
} else {
	$sibutton = '<span class="input-group-addon"></span>';
	$zrbutton = '<span class="input-group-addon"></span>';
	$wybutton = '<span class="input-group-addon"></span>';
	$inbutton = '<span class="input-group-addon"></span>';
}
?>