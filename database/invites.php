<?php
	function inviteUser($user_id, $event_id) {
		global $db;
		$stmt = $db->prepare('INSERT INTO Invites VALUES(:idUser, :idEvent)';
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->execute();
	}
?>