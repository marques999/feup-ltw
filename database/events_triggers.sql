CREATE TRIGGER deleteEvent
BEFORE DELETE
ON Events
FOR EACH ROW
BEGIN
DELETE FROM Comments WHERE Comments.idEvent = old.idEvent;
DELETE FROM UserEvents WHERE UserEvents.idEvent = old.idEvent;
DELETE FROM Invites WHERE Invites.idEvent = old.idEvent;
END

CREATE TRIGGER deleteUsers
BEFORE DELETE
ON Users
FOR EACH ROW
BEGIN
DELETE FROM Comments  WHERE Comments.idUser = old.idUser;
DELETE FROM UserEvents WHERE UserEvents.idUser = old.idUser;
DELETE FROM Invites WHERE Invites.idUser = old.idUser OR Invites.idSender = old.idUser;
END

CREATE TRIGGER deletePosts
BEFORE DELETE
ON ForumThread
FOR EACH ROW
BEGIN
DELETE FROM ForumPost WHERE ForumPost.idThread = old.idThread;
END