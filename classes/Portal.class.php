<?php
class Portal {
	public $id = '';
	public $name = '';
	public $owner = '';
	public $owner_since = '';
	public $faction = '';
	public $lat = '';
	public $lng = '';
	public $discovered = '';
	public $updated = '';

	public function __construct($id, $lat, $lng, $name, $owner, $owner_since, $faction, $discovered='', $updated='') {
		$this->id = $id;
		$this->lat = $lat;
		$this->lng = $lng;
		$this->name = $name;
		$this->owner = $owner;
		$this->owner_since = $owner_since;
		$this->faction = $faction;
		$this->discovered = $discovered;
		$this->updated = $updated;
	}

	protected function load_assignees() {
		$this->assigned_players = $this->get_assigned_players();
	}

	public static function get_by_id($id) {
		global $mysqli;
		$portal = FALSE;
		$sql = "SELECT id, lat, lng, name, owner, owner_since, faction, discovered, updated FROM ingress_portals WHERE id = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $id);
                	$stmt->execute();
                	$stmt->bind_result($id, $lat, $lng, $name, $owner, $owner_since, $faction, $discovered, $updated);
                	if ($stmt->fetch()) {
				$portal = new self($id, $lat, $lng, $name, $owner, $owner_since, $faction, $discovered, $updated);
			}
			$stmt->close();
		}
		if ($portal) {
			$portal->load_assignees();
		}
		return $portal;
	}

	public static function get_by_coordinates($lat, $lng) {
		global $mysqli;
		$portal = FALSE;
		$sql = "SELECT id, lat, lng, name, owner, owner_since, faction, discovered, updated FROM ingress_portals WHERE lat = ? AND lng = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ii', $lat, $lng);
                	$stmt->execute();
                	$stmt->bind_result($id, $lat, $lng, $name, $owner, $owner_since, $faction, $discovered, $updated);
                	if ($stmt->fetch()) {
				$portal = new self($id, $lat, $lng, $name, $owner, $owner_since, $faction, $discovered, $updated);
			}
			$stmt->close();
		}
		if ($portal) {
			$portal->load_assignees();
		}
		return $portal;
	}

	public function save() {
		$this->updated = date('Y-m-d H:i');
		if (!empty($this->id)) {
			$this->discovered = date('Y-m-d H:i');
			return $this->update();
		} else {
			return $this->insert();
		}
	}

	private function update() {
		global $mysqli;
		$success = FALSE;
		$sql = "UPDATE ingress_portals SET lat=?, lng=?, name=?, owner=?, owner_since=?, faction=?, discovered=?, updated=? WHERE id=?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iisssssi', $this->lat, $this->lng, $this->name, $this->owner, $this->owner_since, $this->faction, $this->discovered, $this->updated, $this->id);
			$stmt->execute();
			if ($stmt->affected_rows == 1) {
				$success = TRUE;
			}	
			$stmt->close();
		}
		return $success;
	}

	private function insert() {
		global $mysqli;
		$success = FALSE;
		$sql = "INSERT INTO ingress_portals (lat, lng, name, owner, owner_since, faction, discovered, updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('iisssss', $this->lat, $this->lng, $this->name, $this->owner, $this->owner_since, $this->faction, $this->discovered, $this->updated);
			$stmt->execute();
			if ($stmt->affected_rows == 1) {
				$success = TRUE;
			}	
			$stmt->close();
		}
		return $success;
	}

	protected function get_assigned_players() {
		global $mysqli;
		$assigned_players = array();
		$sql = "SELECT id, player, since FROM ingress_portal_watch WHERE portal = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('i', $this->id);
                	$stmt->execute();
                	$stmt->bind_result($id, $player, $since);
                	while ($stmt->fetch()) {
				$assignee = new stdClass();
				$assignee->id = $id;
				$assignee->name = $player;
				$assignee->since = $since;
				$assigned_players[] = $assignee;
			}
			$stmt->close();
		}
		return $assigned_players;
	}

	public function assign_player($player) {
		global $mysqli;
		$success = FALSE;
		$sql = "INSERT INTO ingress_portal_watch (portal, player, since) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE since = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('isss', $this->id, $player, date("Y-m-d H:i"), date("Y-m-d H:i"));
			$stmt->execute();
			if ($stmt->affected_rows == 1) {
				$success = TRUE;
			}
			$stmt->close();
		}
		return $success;
	
	}

	public function unassign_player($player) {
		global $mysqli;
		$success = FALSE;
		$sql = "DELETE FROM ingress_portal_watch WHERE portal = ? AND player = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('ii', $this->id, $player);
			$stmt->execute();
			if ($stmt->affected_rows == 1) {
				$success = TRUE;
			}
			$stmt->close();
		}
		return $success;
	}

}
