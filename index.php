<?php
include "config.inc.php";
include "classes/Portal.class.php";
include "classes/List.class.php";

$lat = isset($_GET['lat']) ? intval($_GET['lat']) : '';
$lng = isset($_GET['lng']) ? intval($_GET['lng']) : '';

if (!empty($lat) && !empty($lng)) {
	$portal = Portal::get_by_coordinates($lat, $lng);
}


include "templates/_header.php";
if ($portal) {
	include "templates/single.php";
} else {
	include "templates/list.php";
}
include "templates/_footer.php";
?>
