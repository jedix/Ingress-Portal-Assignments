<form name="filter">
<select name="player" onChange="document.forms.filter.submit()">
<option value="">show portals for one player</option>
<?php
$player = isset($_GET['player']) ? $_GET['player'] : FALSE;
$playerlist = MyList::get_assignees();
foreach ($playerlist->items as $myplayer) {
	echo '<option value="'.$myplayer.'"'.($player == $myplayer ? ' selected' : '').'>'.$myplayer.'</option>';
} 
?>
</select>
</form>


<table class="list">
<tr>
<th>Portal</th>
<th>Options</th>
</tr>
<?php
if (empty($player)) {
	$portallist = MyList::get_portals();
} else {
	$portallist = MyList::get_portals_by_assignee($player);
}
foreach ($portallist->items as $portal) {
?>
<tr>
 <td class="owner-<?php echo $portal->faction; ?>"><?php echo $portal->name; ?></td>
 <td><a class="intel" href="http://www.ingress.com/intel?pll=<?php echo ($portal->lat / 1000000).','.($portal->lng / 1000000); ?>&z=17" target="_blank" title="Show on Intel map: <?php echo ($portal->lng / 1000000)." | ".($portal->lat / 1000000) ; ?>"></a>
<a class="info" title="Show portal information" href="?lat=<?php echo $portal->lat; ?>&lng=<?php echo $portal->lng; ?>"></a></td>
</tr>
<?php
}
?>
</table> 
