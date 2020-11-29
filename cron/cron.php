<?php
/*
Created by Rzuwik
*/

$time = time();
$lastAction = row("SELECT last_action AS a FROM cron");
$nextAction = $lastAction['a'] + 86400;
if ($time >= $nextAction) {
	restorePoints();
	call("UPDATE cron SET last_action = ".$time);
}

?>