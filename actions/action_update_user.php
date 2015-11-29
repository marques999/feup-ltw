<?
	include_once('../database/connection.php');
	include_once('../database/country.php');
	include_once('../database/salt.php');
	include_once('../database/users.php');

	if (isset($_POST['idUser']) && isset($_POST['field'])) {

		$stmt = $db->prepare('SELECT username FROM Users WHERE username = :username');
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$userExists = $stmt->fetchAll() != false;
		$changeField = $_POST['field'];
		$validOperation = false;

		if ($changeField = 'password' && isset($_POST['password'])) {
			if (isset($_POST['password'])) {
				$validOperation = true;
				$stmt = $db->prepare('UPDATE Users SET password = :password WHERE idUser = :idUser');
				$stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
			}
		}
		else if ($changeField = 'name') {
			if (isset($_POST['first-name']) && isset($_POST['last-name'])) {
				$validOperation = true;
				$fullName = "{$_POST['first-name']} {$_POST['last-name']}";
				$stmt = $db->prepare('UPDATE Users SET name = :name WHERE idUser = :idUser');
				$stmt->bindParam(':name', $fullName, PDO::PARAM_STR);
			}
		}
		else if ($changeField == 'email') {
			if (isset($_POST['email'])) {
				$validOperation = true;
				$stmt = $db->prepare('UPDATE Users SET email = :email WHERE idUser = :idUser');
				$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
			}
		}
		else if ($changeField == 'location') {
			if (isset($_POST['location']) && isset($_POST['country'])) {
				$validOperation = true;
				$stmt = $db->prepare('UPDATE Users SET location = :location, country = :country WHERE idUser = :idUser');
				$stmt->bindParam(':location', $_POST['location'], PDO::PARAM_STR);
				$stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);
			}
		}

		if ($validOperation) {
			$stmt->bindParam(':idUser', $_POST['idUser'], PDO::PARAM_STR);
			$stmt->execute();
		}
	}
?>