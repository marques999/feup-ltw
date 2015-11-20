<?php
	session_start();

	include_once('database/connection.php');
	include_once('database/comments.php');

	global $db;
	$currentTime = time();
	$stmt = $db->prepare('INSERT INTO Comments VALUES(NULL, :idUser, :idEvent, :timestamp, :message)');
	$stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_INT);
	$stmt->bindParam(':idEvent', $_POST['idEvent'], PDO::PARAM_INT);
	$stmt->bindParam(':timestamp', $currentTime, PDO::PARAM_INT);
	$stmt->bindParam(':message', $_POST['message'], PDO::PARAM_STR);

	if ($stmt->execute() != true) {
		include("message_database.php");
	}
	else {
		header("Location: view_event.php?id={$_POST['idEvent']}#comments");
	}
?>