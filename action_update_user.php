<?php
	if (!isset($_POST['idUser'])) {
		exit(0);
	}

	if (!isset($_POST['username']) || !isset($_POST['password']))) {
		exit(0);
	}

    global $db;
    $stmt = $db->prepare('UPDATE Users SET
    						password = :password,
    						name = :name,
    						email = :email,
    						location = :location,
    						WHERE idUser = :idUser');
    $stmt->bindParam(':passowrd', $_POST['password'], PDO::PARAM_STR);
    $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
 	$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->bindParam(':location', $_POST['location'], PDO::PARAM_STR);
    $stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_STR);
    $stmt->execute();
?>