<?php

    include_once('database/salt.php');

    function users_listById($user_id) {
       
        global $db;
        $stmt = $db->prepare('SELECT * FROM Users WHERE idUser = :idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function users_listAll() {
       
        global $db;
        $stmt = $db->prepare('SELECT username FROM Users');
        $stmt->execute();
        return $stmt->fetchAll();
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
        $stmt = $db->prepare('SELECT * FROM UserEvents WHERE
                                UserEvents.idEvent = :idEvent
                                AND UserEvents.idUser = :idUser');
        $stmt->bindParam(':idEvent', $event_id, PDO::PARAM_INT);    
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll() != false;
    }

    function users_listOwnEvents($user_id) {
        
        global $db;
        $stmt = $db->prepare('SELECT * FROM Events WHERE idUser = :idUser');
        $stmt->bindParam(':idUser', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    function userExists($username) {
        
        global $db;
        $stmt = $db->prepare('SELECT username FROM Users WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll() != false;
    }

    function validateLogin($username, $password) {
        
        global $db;
        $stmt = $db->prepare('SELECT * FROM Users WHERE username = :username AND password = :password');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', sha1($password), PDO::PARAM_STR);
        $stmt->execute();
        $queryResult = $stmt->fetchAll();
        $numberResults = count($queryResult);

        if ($queryResult != false) {
            return $queryResult[0]['idUser'];
        }

        return $numberResults;
    }
?>