<?
    include_once('../database/connection.php');
    include_once('../database/country.php');
    include_once('../database/salt.php');
    include_once('../database/events.php');

	if (isset($_POST['idEvent']) && isset($_POST['field'])) {
		
		$eventId = intval($_POST['idEvent']);
		
		if ($eventId > 0) {
			
			$stmt = $db->prepare('SELECT name FROM Events WHERE name = :name');
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->execute();
			$userExists = $stmt->fetchAll() != false;
			$changeField = $_POST['field'];
			$validOperation = false;

			if ($changeField = 'name') {
				if (isset($_POST['first-name']) && isset($_POST['last-name'])) {
					$validOperation = true;
					$fullName = "{$_POST['first-name']}";
					$stmt = $db->prepare('UPDATE Events SET name = :name WHERE idEvent = :idEvent');
					$stmt->bindParam(':name', $fullName, PDO::PARAM_STR);
				}
			}
			
			if ($changeField == 'location') {
				if (isset($_POST['location']) && isset($_POST['country'])) {
					$validOperation = true;
					$stmt = $db->prepare('UPDATE Events SET location = :location, country = :country WHERE idEvent = :idEvent');
					$stmt->bindParam(':location', $_POST['location'], PDO::PARAM_STR);
					$stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);
				}
			}
			
			if ($changeField == 'date') {
				if (isset($_POST['date'])) {
					$validOperation = true;
					$stmt = $db->prepare('UPDATE Events SET date = :date WHERE idEvent = :idEvent');
					$stmt->bindParam(':date', $_POST['date'], PDO::PARAM_STR);
				}
			}
			
			if ($changeField == 'type') {
				if (isset($_POST['type'])) {
					$validOperation = true;
					$stmt = $db->prepare('UPDATE Events SET type = :type WHERE idEvent = :idEvent');
					$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_STR);
				}
			}
			
			if ($changeField == 'description') {
				if (isset($_POST['description'])) {
					$validOperation = true;
					$stmt = $db->prepare('UPDATE Events SET description = :description, WHERE idEvent = :idEvent');
					$stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
				}
			}

			if ($validOperation) {
				$stmt->bindParam(':idEvent', $_POST['idEvent'], PDO::PARAM_STR);
				$stmt->execute();
			}
		}
	}
?>