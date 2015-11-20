<?php
	function inviteUser($user_id, $event_id) {
		global $db;
		$stmt = $db->prepare('INSERT INTO Users VALUES(NULL, :username, :password, :name, :email, :location');
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', sha1($password), PDO::PARAM_STR);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':location', $location, PDO::PARAM_STR);
		$stmt->execute();
	}

	function deleteInvite($invite_id) {
		global $db;
		$stmt = $db->prepare('DELETE FROM Invites WHERE idInvite = :invite_id');
		$stmt->bindParam(':invite_id', $invite_id, PDO::PARAM_INT);
		$stmt->execute();
	}

	function acceptInvite($user_id, $event_id) {
		
		global $db;
		$stmt = $db->prepare('SELECT * FROM Invites WHERE idUser = :idUser AND idEvent = :idEvent');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();

		if ($stmt->fetch()) {
			deleteInvite($result['idInvite']);
		}
	}

	function declineInvite($user_id, $event_id) {

		global $db;
		$stmt = $db->prepare('SELECT * FROM Invites WHERE idUser = :idUser AND idEvent = :idEvent');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();

		if ($stmt->fetch()) {
			deleteInvite($result['idInvite']);
		}
	}
?>