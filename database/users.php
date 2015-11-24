<?php
    $stmt = $db->prepare('SELECT idUser, username FROM Users');
    $stmt->execute();
    $allUsers = $stmt->fetchAll();

    function users_listById($user_id) {     
        global $db;
        $stmt = $db->prepare('SELECT * FROM Users WHERE idUser = :idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function users_formatLocation($user_data) {
        $countryString = getCountry($user_data['country']);
        return "{$user_data['location']}, $countryString";
    }

    function users_getCountryFlag($userData) {

        $country = $userData['country'];
        
        if (strlen($country) != 2) {
            $country = 'europeanunion.png';
        }

        return "img/flags/$country.png";
    }

    function users_getAvatar($userData) {

        $user_id = intval($userData['idUser']);
        
        if (!intval($user_id)) {
            $user_id = 0;
        }

        return "img/avatars/$user_id.png";
    }

    function users_getSmallAvatar($userData) {
       
       $user_id = intval($userData['idUser']);

        if (!intval($user_id)) {
            $user_id = 0;
        }

        return "img/avatars/{$user_id}_small.png";
    }

    function users_viewProfile($userData) {

        $user_id = intval($userData['idUser']);

        if (!intval($user_id)) {
            $user_id = 0;
        }

        return "view_profile.php?id={$user_id}";     
    }

    function numberUsers() {   
        global $db;
        $stmt = $db->prepare('SELECT COUNT(*) AS count FROM Users');
        $stmt->execute();
        return $stmt->fetch();
    }

    function users_listAllEvents($user_id) {
        global $db;
        $stmt = $db->prepare('SELECT Events.* FROM UserEvents JOIN Users, Events 
                                ON UserEvents.idUser = :idUser
                                AND Events.idEvent = UserEvents.idEvent
                                AND Users.idUser = UserEvents.idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
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
        $stmt = $db->prepare('SELECT COUNT(*) AS count FROM Invites 
                                INNER JOIN Users 
                                ON Invites.idUser = :idUser
                                AND Users.idUser = Invites.idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0) {
             return $result[0]['count'];
        }
       
       return null;
    }

    function users_listInvites($user_id) {
        global $db;
        $stmt = $db->prepare('SELECT Events.*, Invites.idSender FROM Invites
                                INNER JOIN Users, Events
                                ON Events.idEvent = Invites.idEvent
                                AND Invites.idUser = :idUser
                                AND Users.idUser = Invites.idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    function users_listOwnEvents($user_id) {    
        global $db;
        $stmt = $db->prepare('SELECT * FROM Events WHERE idUser = :idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function users_userExists($username) {   
        global $db;
        $stmt = $db->prepare('SELECT username FROM Users WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll() != false;
    }

    function users_emailExists($email) {
        global $db;
        $stmt = $db->prepare('SELECT email FROM Users WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll() != false;
    }

    function validateLogin($username, $password) {
       
        global $db;
        $stmt = $db->prepare('SELECT * FROM Users WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $queryResult = $stmt->fetchAll();
        $numberResults = count($queryResult);

        if ($queryResult != false && $numberResults == 1) {
            $correctHash = $queryResult[0]['password'];
            $validateResult = validate_password($password, $correctHash); 
            return $queryResult[0]['idUser'];
        }

        return $numberResults;
    }
?>