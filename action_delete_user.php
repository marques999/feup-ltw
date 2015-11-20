<?php
	if (!isset($_POST['username'])) {
		exit(0);
	}

    global $db;
    $stmt = $db->prepare('DELTE FROM Users WHERE username = :username');
    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
?>