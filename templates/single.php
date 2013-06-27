<h1><?php echo $portal->name; ?></h1>
<table>
<tr>
<th>Coordinates:</th>
<td><?php echo ($portal->lat/1000000)." | ".($portal->lng/1000000); ?></td>
</tr>
<tr>
<th>Discovered:</th>
<td><?php echo $portal->discovered; ?></td>
</tr>
</table>

<h2>Assigned players</h2>
<table class="list">
<tr><th>Player</th><th>Last update</th></tr>
<?php
foreach ($portal->assigned_players as $player) {
?>
<tr><td><a title="Show all portals assigned to <?php echo $player->name; ?>" href="?player=<?php echo $player->name; ?>"><?php echo $player->name; ?></a></td><td><?php echo $player->since; ?></td></tr>
<?php
}
?> 
</table>
