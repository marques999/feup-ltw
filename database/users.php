<?
	$defaultUser = array(
		'idUser' => 0,
		'username' => 'Guest',
		'name' => 'Troll Face',
		'email' => 'nobody@loves.me',
		'location' => 'Beijing',
		'country' => 'cn');

	$stmt = $db->prepare('SELECT idUser, username FROM Users');
	$stmt->execute();
	$allUsers = array();
	$allUsers[0] = $defaultUser;

	while(($result = $stmt->fetch()) != null) {
		$allUsers[$result['idUser']] = $result;
	}

	function users_listById($user_id) {
		global $db;
		$stmt = $db->prepare('SELECT * FROM Users WHERE idUser = :idUser');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function users_getNextId() {
		global $db;
		$stmt = $db->prepare("SELECT * FROM SQLITE_SEQUENCE WHERE name='Users'");
		$stmt->execute();
		$result = $stmt->fetch();
		if ($result != false && is_array($result)) {
			return $result['seq'];
		}
		return -1;
	}

	function users_formatLocation($userData) {

		if (!is_array($userData) || !isset($userData['country']) || !isset($userData['location'])) {
			$userData = $defaultUser;
		}

		$countryString = getCountry($userData['country']);
		return "{$userData['location']}, $countryString";
	}

	function users_getCountryFlag($userData) {

		if (!is_array($userData) || !isset($userData['country'])) {
			$userData = $defaultUser;
		}

		$country = $userData['country'];

		if (strlen($country) != 2) {
			$country = 'europeanunion.png';
		}

		return "img/flags/$country.png";
	}

	function users_getAvatar($userData) {

		if (!is_array($userData) || !isset($userData['idUser'])) {
			$userData = $defaultUser;
		}

		$user_id = safe_getId($userData, 'idUser');
		return glob("img/avatars/$user_id.{jpg,jpeg,gif,png}", GLOB_BRACE)[0];
	}

	function users_getSmallAvatar($user_id) {

		$user_id = intval($user_id);

		if (!intval($user_id)) {
			$user_id = 0;
		}

		return glob("img/avatars/{$user_id}_small.{jpg,jpeg,gif,png}", GLOB_BRACE)[0];
	}

	function users_viewProfile($user_id) {

		$user_id = intval($user_id);

		if (!intval($user_id)) {
			$user_id = 0;
		}

		return "view_profile.php?id={$user_id}";
	}

	function numberUsers() {
		global $db;
		$stmt = $db->prepare('SELECT COUNT(*) AS count FROM Users');
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($result != false && is_array($result) && count($result) > 0) {
			 return $result[0]['count'];
		}
		return 0;
	}

	function users_listAllEvents($user_id, $private) {

		global $db;

		if ($private) {
			$stmt = $db->prepare('SELECT DISTINCT Events.* FROM UserEvents JOIN Users, Events
				ON UserEvents.idUser = :idOwner
				AND Events.idEvent = UserEvents.idEvent
				AND Users.idUser = UserEvents.idUser');
		}
		else {
			$stmt = $db->prepare('SELECT DISTINCT Events.* FROM UserEvents JOIN Users, Events
				ON UserEvents.idUser = :idOwner
				AND Events.idEvent = UserEvents.idEvent
				AND Users.idUser = UserEvents.idUser
				AND (Events.private = 0
				OR (Events.private = 1 AND UserEvents.idUser = :idUser))');
			$stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
		}

		$stmt->bindParam(':idOwner', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function users_listOwnEvents($user_id, $private) {
		global $db;

		if ($private) {
			$stmt = $db->prepare('SELECT DISTINCT * FROM Events WHERE idUser = :idOwner');
		}
		else {
			 $stmt = $db->prepare('SELECT DISTINCT Events.* FROM Events
				JOIN UserEvents ON Events.idUser = :idOwner
				AND UserEvents.idEvent = Events.idEvent
				AND (Events.private = 0 OR (Events.private = 1 AND UserEvents.idUser = :idUser))');
			 $stmt->bindParam(':idUser', $thisUser, PDO::PARAM_INT);
		}

		$stmt->bindParam(':idOwner', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function users_isParticipating($user_id, $event_id) {
		global $db;
		$stmt = $db->prepare('SELECT * FROM UserEvents WHERE UserEvents.idEvent = :idEvent AND UserEvents.idUser = :idUser');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll() != false;
	}

	function users_wasInvited($user_id, $event_id) {
		global $db;
		$stmt = $db->prepare('SELECT * FROM Invites WHERE Invites.idEvent = :idEvent AND Invites.idUser = :idUser');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll() != false;
	}

	function users_listInvite($user_id, $event_id) {
		global $db;
		$stmt = $db->prepare('SELECT Invites.idSender FROM Invites WHERE Invites.idEvent = :idEvent AND Invites.idUser = :idUser');
		$stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}

	function users_listFutureEvents($user_id, $current_date) {
		global $db;
		$stmt = $db->prepare('SELECT Events.* FROM UserEvents JOIN Users, Events
				ON UserEvents.idUser = :idUser
				AND Events.idEvent = UserEvents.idEvent
				AND Users.idUser = UserEvents.idUser
				WHERE Events.date > :currentDate');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->bindParam(':currentDate', $current_date, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function users_countInvites($user_id) {
		global $db;
		$stmt = $db->prepare('SELECT COUNT(Invites.idEvent) AS count FROM Invites
				INNER JOIN Users
				ON Invites.idUser = :idUser
				AND Users.idUser = Invites.idUser');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if (is_array($result) && count($result) > 0) {
			 return $result[0]['count'];
		}
		return 0;
	}

	function users_listInvites($user_id) {
		global $db;
		$stmt = $db->prepare('SELECT Events.*, Invites.idSender FROM Invites
			INNER JOIN Events
			ON Events.idEvent = Invites.idEvent
			AND Invites.idUser = :idUser');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	function users_usernameExists($username) {
		global $db;
		$stmt = $db->prepare('SELECT username FROM Users WHERE username = :username');
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function users_idExists($user_id) {
		global $db;
		$stmt = $db->prepare('SELECT username FROM Users WHERE idUser = :idUser');
		$stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function users_emailExists($email) {
		global $db;
		$stmt = $db->prepare('SELECT email FROM Users WHERE email = :email');
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result != false && is_array($result) && count($result) > 0;
	}

	function validateLogin($username, $password) {
		global $db;
		$safeUsername=strip_tags_content($username);
		$stmt = $db->prepare('SELECT * FROM Users WHERE username = :username');
		$stmt->bindParam(':username', $safeUsername, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($result != false && is_array($result) && count($result) > 0) {
			$result = $result[0];
			$correctHash = $result['password'];
			if(validate_password($password, $correctHash)){
				return $result['idUser'];
			}
		}
		return 0;
	}
?>