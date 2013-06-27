<?php
class MyList {

	public $items = array();

	public function __construct($items) {
		$this->items = $items;
	}

	public static function get_portals() {
		global $mysqli;
		$ids = array();
		$portals = array();
		$sql = "SELECT id FROM ingress_portals";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id);
			while ($stmt->fetch()) {
				$ids[] = $id;
			}
			$stmt->close();
		}
		foreach ($ids as $id) {
			$portal = Portal::get_by_id($id);
			if ($portal) {
				$portals[] = $portal;
			}
		}
		$list = new self($portals);
		return $list;
	}

	public static function get_portals_by_assignee($player) {
		global $mysqli;
		$ids = array();
		$portals = array();
		$sql = "SELECT portal FROM ingress_portal_watch WHERE player = ?";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param('s', $player);
			$stmt->execute();
			$stmt->bind_result($pid);
			while ($stmt->fetch()) {
				$ids[] = $pid;
			}
			$stmt->close();
		}
		foreach ($ids as $id) {
			$portal = Portal::get_by_id($id);
			if ($portal) {
				$portals[] = $portal;
			}
		}
		$list = new self($portals);
		return $list;
	}

	public static function get_assignees() {
		global $mysqli;
		$assignees = array();
		$sql = "SELECT player FROM ingress_portal_watch GROUP BY player";
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($player);
			while ($stmt->fetch()) {
				$assignees[] = $player;
			}
			$stmt->close();
		}
		$list = new self($assignees);
		return $list;
	}

}

