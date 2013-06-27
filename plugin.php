<?php
include "config.inc.php";
include "classes/Portal.class.php";
$version = isset($_GET['v']) ? $_GET['v'] : '';
$portal_name = isset($_GET['portalname']) ? $_GET['portalname'] : '';
$portal_owner = isset($_GET['portalowner']) ? $_GET['portalowner'] : '';
$portal_owner_since = isset($_GET['portalownersince']) ? $_GET['portalownersince'] : '';
$portal_faction = isset($_GET['portalfaction']) ? $_GET['portalfaction'] : '';
$lat = isset($_GET['lat']) ? intval($_GET['lat']) : '';
$lng = isset($_GET['lng']) ? intval($_GET['lng']) : '';
$nick = isset($_GET['nick']) ? $_GET['nick'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if (empty($lat) || empty($lng) || empty($nick)) {
 die();
}

if (!empty($portal_owner_since)) {
	$portal_owner_since = date("Y-m-d H:i", floor($portal_owner_since / 1000));
}

if (!empty($portal_faction)) {
	$portal_faction = substr($portal_faction, 0, 1);
}

if ($version != "$CURRENT_VERSION") {
?>
$('#dus-resistance').append('<tr><td rowspan="2">You version of the DUS-RES plugin is outdated.<br /> Please <a href="<?php echo $MAIN_URL; ?>js/iitc-dus-resistance.user.js">update</a> to the newest version.</td></tr>');
<?php
	die();
}

if ($lat > $NORTH || $lat < $SOUTH || $lng < $EAST || $lng > $WEST) {
?>
$('#dus-resistance').append('<tr><td rowspan="2">Portal out of our scope.</td></tr>');
<?php
	die();
}

$portal = Portal::get_by_coordinates($lat, $lng);
if (!$portal) {
	$portal = new Portal(0, $lat, $lng, $portal_name, $portal_owner, $portal_owner_since, $portal_faction);
	$portal->save();
}
if ($portal) {	
	$portal->owner = $portal_owner;
	$portal->owner_since = $portal_owner_since;
	$portal->faction = $portal_faction;
	$portal->save();
	switch ($action) {
	case "assign":
		$portal->assign_player($nick);
		die();
	case "unassign":
		$portal->unassign_player($nick);
		die();
	}

	$nick_assigned = FALSE;
	$url = $MAIN_URL."plugin.php?lat=$lat&lng=$lng&nick=$nick&action=";
	foreach ($portal->assigned_players as $assignee) {
		$options = "";
		if ($assignee->name == $nick) {
			$nick_assigned = TRUE;
			$options = ' [<a target="dus-resistance-update" title="Remove from list" href="'.$url.'unassign">X</a>] [<a target="dus-resistance-update" title="Confirm status" href="'.$url.'assign">OK</a>]';
		}
?>
$('#dus-resistance').append('<tr><td><?php echo $assignee->name; ?></td><td><?php echo $assignee->since; ?><?php echo $options ?></td></tr>');
<?php
	}
	if (count($portal->assigned_players) == 0) {
?>
$('#dus-resistance').append('<tr><td rowspan="2">Nobody assigned yet.</td></tr>');
<?php
	}
	if (!$nick_assigned) {
?>
$('#dus-resistance').append('<tr><td rowspan="2"><a href="<?php echo $url; ?>assign" target="dus-resistance-update">Take responsibility :)</a></td></tr>');
<?php
	}
}
?>
