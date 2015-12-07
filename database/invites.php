<?
	function invites_filterParticipating($eventId, $sortName, $sortType) {
		global $db;
		$query = 'SELECT idUser, name, username FROM Users WHERE';

		if (!empty($sortName)) {
			if ($sortType == 2) {
				$query .= " name LIKE '%{$sortName}%' AND";
			}
			else {
				$query .= " username LIKE '%{$sortName}%' AND";
			}
		}

		$query .= ' Users.idUser IN (SELECT UserEvents.idUser FROM UserEvents WHERE	UserEvents.idEvent = :eventId)';
		$stmt = $db->prepare($query);
		$stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function invites_filterAvailable($eventId, $userId, $sortName, $sortType) {
		global $db;
		$query = 'SELECT idUser, name, username FROM Users WHERE';

		if (!empty($sortName)) {
			if ($sortType == 2) {
				$query .= " name LIKE '%{$sortName}%' AND";
			}
			else {
				$query .= " username LIKE '%{$sortName}%' AND";
			}
		}

		$query .= ' Users.idUser NOT IN (SELECT Invites.idUser FROM Invites WHERE Invites.idEvent = :eventId) AND Users.idUser != :userId AND Users.idUser NOT IN (SELECT UserEvents.idUser FROM UserEvents WHERE UserEvents.idEvent = :eventId)';
		$stmt = $db->prepare($query);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function invites_filterInvited($eventId, $sortName, $sortType) {
		global $db;
		$query = 'SELECT idUser, name, username FROM Users WHERE';

		if (!empty($sortName)) {
			if ($sortType == 2) {
				$query .= " name LIKE '%{$sortName}%' AND";
			}
			else {
				$query .= " username LIKE '%{$sortName}%' AND";
			}
		}

		$query .= ' Users.idUser IN (SELECT Invites.idUser FROM Invites WHERE Invites.idEvent = :eventId);';
		$stmt = $db->prepare($query);
		$stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
?>