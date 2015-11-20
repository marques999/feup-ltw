<?php
	global $db;
	$stmt = $db->prepare('INSERT INTO Invites VALUES(NULL, :idEvent, :idUser)');
	$stmt->bindParam(':idEvent', $_POST['idEvent'], PDO::PARAM_INT);
	$stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
	$stmt->execute();
?>