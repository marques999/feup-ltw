CREATE TABLE "Comments" (
	`idComment`		INTEGER PRIMARY KEY AUTOINCREMENT,
	`idUser`		INTEGER NOT NULL,
	`idEvent`		INTEGER NOT NULL,
	`timestamp`		INTEGER,
	`message`		TEXT NOT NULL,
	FOREIGN KEY(`idUser`) 	REFERENCES `Users`(`idUser`),
	FOREIGN KEY(`idEvent`) 	REFERENCES `Events`(`idEvent`)
)

CREATE TABLE "Events" (
	`idEvent`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`			TEXT NOT NULL,
	`date`			INTEGER,
	`location`		TEXT,
	`description`	TEXT,
	`private`		INTEGER,
	`type`			TEXT NOT NULL,
	`idUser`		INTEGER NOT NULL,
	FOREIGN KEY(`idUser`) 	REFERENCES `Users`(`idUser`)
)

CREATE TABLE "ForumPost" (
	`idPost`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`idThread`		INTEGER NOT NULL,
	`idUser`		INTEGER NOT NULL,
	`message`		TEXT NOT NULL,
	`timestamp`		INTEGER CHECK(timestamp >= 0),
	`idQuote`		INTEGER,
	FOREIGN KEY(`idThread`) REFERENCES `ForumThread`(`idThread`),
	FOREIGN KEY(`idUser`) 	REFERENCES `Users`(`idUser`)
)

CREATE TABLE "ForumThread" (
	`idThread`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`idUser`		INTEGER NOT NULL,
	`title`			TEXT NOT NULL,
	`hits`			INTEGER CHECK(hits >= 0),
	`message`		TEXT,
	`timestamp`		INTEGER,
	FOREIGN KEY(`idUser`)	REFERENCES `Users` ( `idUser` )
)

CREATE TABLE "Invites" (
	`idUser`		INTEGER NOT NULL,
	`idEvent`		INTEGER NOT NULL,
	`idSender`		INTEGER NOT NULL,
	PRIMARY KEY(idUser, idEvent),
	FOREIGN KEY(`idUser`) 	REFERENCES `Users` ( `idUser` ),
	FOREIGN KEY(`idEvent`) 	REFERENCES `Events` ( `idEvent` ),
	FOREIGN KEY(`idSender`) REFERENCES `Users` ( `idUser` )
)

CREATE TABLE "UserEvents" (
	`idEvent`		INTEGER NOT NULL,
	`idUser`		INTEGER NOT NULL,
	PRIMARY KEY(idEvent, idUser),
	FOREIGN KEY(`idEvent`) 	REFERENCES `Events`(`idEvent`),
	FOREIGN KEY(`idUser`) 	REFERENCES `Users`(`idUser`)
)

CREATE TABLE "Users" (
	`idUser`		INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`username`		TEXT NOT NULL UNIQUE,
	`password`		TEXT NOT NULL,
	`name`			TEXT NOT NULL,
	`email`			TEXT NOT NULL UNIQUE,
	`location`		TEXT,
	`country`		TEXT NOT NULL
)