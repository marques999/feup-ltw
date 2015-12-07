PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "Users" (
	`idUser`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`username`	TEXT NOT NULL UNIQUE,
	`password`	TEXT NOT NULL,
	`name`	TEXT NOT NULL,
	`email`	TEXT NOT NULL UNIQUE,
	`location`	TEXT,
	`country`	TEXT NOT NULL
);

INSERT INTO "Users" VALUES(1,'somouco','sha256:1000:jBZuofLc3IuDjvStQqtlUsuPiedfGfNC:IbJ/PyxTRAnkRzkgCtBHAMEKCWKyNxoI','Carlos Samouco','carlossamouco@hotmail.com','Kingston','jm');
INSERT INTO "Users" VALUES(2,'marques999','sha256:1000:uCS1w3ZCyyuPFTQLqY3lpcPGeHzyVpfp:NgwgaBkE4Bwxrl4TyuED9aH/fgXijt6w','Diogo Marques','xmarques999@hotmail.com','Saitama','jp');
INSERT INTO "Users" VALUES(3,'dp','sha256:1000:zleLJCUr4/UvlmCXLf6dJn4ooU8A3LsM:JBJyCUJscHdcMdjO6jDyeu7clvV8B8uM','Diana Pinto','digpinto@gmail.com','Trancoso','pt');
INSERT INTO "Users" VALUES(4,'manel','sha256:1000:mqjgYkSHjEXjOC/r6kEMMzKCVpRoN+W4:El7IOZo/DY1cAe33UVyiVnXqAfCFENx1','Manel Manel','manel_antonio@fixolas.com','San Francisco','us');
INSERT INTO "Users" VALUES(8,'annaka','sha256:1000:2Siy03we+HWPyLvfhK+TRhgaW8ekpC/e:RGY+CgfWPgyl6GjCG0Q1zqwk1B+ZUeaq','Aisaka Taiga','whatevermate@dot.com','Bridgetown','bb');
INSERT INTO "Users" VALUES(10,'fpsdoug','sha256:1000:cLClKEQjf0Y04S1yISkZ7HGzAXxIVWuT:Oj/vtraEedHK/7pw+29dzBakaOOBX3pv','Pure Pwnage','trynhitme@noob.com','Ontario','ca');
INSERT INTO "Users" VALUES(11,'mark','sha256:1000:qfZpyaUbAr+Hsng7ElSRxvW/FC0Qfrjx:BV0wpqgoLxctgbOZgK7x43I8JOZgg0E1','Marky Mark','markymark@show.biz','Malmö','af');
INSERT INTO "Users" VALUES(13,'mimi','sha256:1000:yVNLmLGglkbCO+z1Yn3VpPNjIbFY8Kmp:Ige0B/u36FgdhP6PmpZKa2xiNH3qjOEr','Mimi Usa','kawaiii@desu.ne','Akihabara','af');
INSERT INTO "Users" VALUES(14,'maome','sha256:1000:Pf1Y/vtChf9T1avMJRCTYbBHRjJ2kH/6:2SSs4283sWQNTrUoqA72hHguvne8KjYv','Mahmoud Ahmadinejad','iranian@president.gov','Teeran','ir');
INSERT INTO "Users" VALUES(15,'sanik','sha256:1000:QyW1clV5SW0lEmwESnIiSsNK+0bwGAXO:3ZeVMzkj1BDcnCcxMz8tDYJO4Khs9lfB','Sanic Hegehog','rollingaround@speed.snd','New York','us');
INSERT INTO "Users" VALUES(16,'ermahgerd','sha256:1000:7+95dfq+7ioUH+Hjgwbbn28urqSf1JlL:D7CjCLHKSylyyFVSDnKwOOPIo/tK7KXm','Ermahgerd Gersberms','berks@yohoo.com','Vancouver','ca');
INSERT INTO "Users" VALUES(17,'jj','sha256:1000:HOlKq6tUqbbj01rxucV1AGuMTcSb1DQF:unOy4Ww2BtcFwX9EV5vd4NBGbx6cAK/6','Jorge Jesus','benfica@fcp.pt','Lisboa','pt');
INSERT INTO "Users" VALUES(18,'arekusandaro','sha256:1000:00rSdijr9M/ZIs6SNxfJmT7oeYO+LgfY:mFaWObly8jsZz/knTWCfbwlLXdzs+AED','Varg Vikernes','burn@churches.net','Hordaland','no');

CREATE TABLE "ForumPost" (
	`idPost`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`idThread`	INTEGER NOT NULL,
	`idUser`	INTEGER NOT NULL,
	`message`	TEXT NOT NULL,
	`timestamp`	INTEGER CHECK(timestamp >= 0),
	`idQuote`	INTEGER,
	FOREIGN KEY(`idThread`) REFERENCES `ForumThread`(`idThread`),
	FOREIGN KEY(`idUser`) REFERENCES `Users`(`idUser`)
);

INSERT INTO "ForumPost" VALUES(1,1,4,'O que temos que ter sempre em mente é que a determinação clara de objetivos promove a alavancagem da gestão inovadora da qual fazemos parte.',1,NULL);
INSERT INTO "ForumPost" VALUES(2,1,5,'Do mesmo modo, a competitividade nas transações comerciais acarreta um processo de reformulação e modernização dos níveis de motivação departamental.',3,1);
INSERT INTO "ForumPost" VALUES(3,1,1,' nível organizacional, a consolidação das estruturas nos obriga à análise do retorno esperado a longo prazo.',2,2);
INSERT INTO "ForumPost" VALUES(4,2,1,'WHAT? :o',5,'');
INSERT INTO "ForumPost" VALUES(5,2,2,':P',7,NULL);
INSERT INTO "ForumPost" VALUES(7,1,2,'COOL STORY BROS!!! XD',15,3);
INSERT INTO "ForumPost" VALUES(8,1,1,' :@ :S :| XD :D :) :o :( :P',1448921013,NULL);
INSERT INTO "ForumPost" VALUES(9,1,1,' XD XD XD XD',1448921055,NULL);
INSERT INTO "ForumPost" VALUES(10,1,1,' :o',1448921119,NULL);
INSERT INTO "ForumPost" VALUES(11,1,1,' :@',1448921139,NULL);
INSERT INTO "ForumPost" VALUES(12,1,1,' :x',1448921182,NULL);
INSERT INTO "ForumPost" VALUES(13,1,1,'teste testando testes XD',1448921193,NULL);
INSERT INTO "ForumPost" VALUES(14,1,1,' :D :D :D',1448921210,NULL);
INSERT INTO "ForumPost" VALUES(15,1,1,'vai ser desta  :@',1448921260,NULL);
INSERT INTO "ForumPost" VALUES(18,1,1,'NOPE AINDA NÃO ESTÁ A FUNCIONAR  :o :o :o :o',1448921997,NULL);
INSERT INTO "ForumPost" VALUES(20,4,3,'#aioque  XD',1448927478,NULL);
INSERT INTO "ForumPost" VALUES(22,3,4,'olha olha .....  :P',1448927632,NULL);
INSERT INTO "ForumPost" VALUES(23,4,4,'um dia...
um dia...',1448927687,NULL);
INSERT INTO "ForumPost" VALUES(24,4,4,'olha olha.....
',1448927701,NULL);
INSERT INTO "ForumPost" VALUES(25,4,4,'olha olha..... ehhhhh!
Tu queres é um iPhone dos novos

 :|',1448927729,NULL);
INSERT INTO "ForumPost" VALUES(26,4,2,'está calado meu, e trabalha!  :|',1448927793,NULL);
INSERT INTO "ForumPost" VALUES(27,4,2,'ainda há muita coisa para fazer ntes da entrega  :S',1448927814,NULL);
INSERT INTO "ForumPost" VALUES(28,4,2,'antes*
 :D',1448927820,NULL);
INSERT INTO "ForumPost" VALUES(29,4,2,'amanhã quero essa browse feita com paginação, e search também!!!
 :@ :@ :@',1448927852,NULL);
INSERT INTO "ForumPost" VALUES(35,3,8,'えええええええええ？',1449008968,34);
INSERT INTO "ForumPost" VALUES(36,8,8,'fail',1449011135,0);
INSERT INTO "ForumPost" VALUES(39,8,8,'more quotes',1449011178,36);
INSERT INTO "ForumPost" VALUES(43,8,8,'all the quotes',1449011200,39);
INSERT INTO "ForumPost" VALUES(46,8,8,'quote apocalipse',1449011222,43);
INSERT INTO "ForumPost" VALUES(47,8,8,'A',1449011247,46);
INSERT INTO "ForumPost" VALUES(48,8,8,'B',1449011253,47);
INSERT INTO "ForumPost" VALUES(49,8,8,'C',1449011258,48);
INSERT INTO "ForumPost" VALUES(50,8,8,'D',1449011267,49);
INSERT INTO "ForumPost" VALUES(51,8,8,'E',1449011272,50);
INSERT INTO "ForumPost" VALUES(52,8,8,'F',1449011277,51);
INSERT INTO "ForumPost" VALUES(53,8,8,'G',1449011281,52);
INSERT INTO "ForumPost" VALUES(54,8,8,'H',1449011292,53);
INSERT INTO "ForumPost" VALUES(55,8,8,'I',1449011301,54);
INSERT INTO "ForumPost" VALUES(56,8,8,'J',1449011307,55);
INSERT INTO "ForumPost" VALUES(57,8,8,'K',1449011313,56);
INSERT INTO "ForumPost" VALUES(58,8,8,'L',1449011317,57);
INSERT INTO "ForumPost" VALUES(59,8,8,'M',1449011321,58);
INSERT INTO "ForumPost" VALUES(60,8,8,'N',1449011326,59);
INSERT INTO "ForumPost" VALUES(61,8,8,'O',1449011330,60);
INSERT INTO "ForumPost" VALUES(62,8,8,'P',1449011334,61);
INSERT INTO "ForumPost" VALUES(65,8,2,'WHAT',1449093826,NULL);
INSERT INTO "ForumPost" VALUES(66,8,2,'HEY QUOTE',1449093830,65);
INSERT INTO "ForumPost" VALUES(68,20,14,':@',1449099166,NULL);
INSERT INTO "ForumPost" VALUES(69,30,16,'OMG OMG OMG BERST CURSMAS ERVER',1449276356,0);
INSERT INTO "ForumPost" VALUES(79,2,2,'qué pasa? :|',1449432652,NULL);

CREATE TABLE "ForumThread" (
	`idThread`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`idUser`	INTEGER NOT NULL,
	`title`	TEXT NOT NULL,
	`hits`	INTEGER CHECK(hits >= 0),
	`message`	TEXT,
	`timestamp`	INTEGER,
	FOREIGN KEY(`idUser`) REFERENCES `Users` ( `idUser` )
);

INSERT INTO "ForumThread" VALUES(1,1,'DORMIR 24H',171,'mY NamE Is JEff :D',231231);
INSERT INTO "ForumThread" VALUES(2,3,'Entrega de LTW',166,'ooooooooooooooooooooooooooooooo',578671);
INSERT INTO "ForumThread" VALUES(3,3,'Oláaaaa :DD',189,'pppppppppppppppp',3879711);
INSERT INTO "ForumThread" VALUES(4,1,'xau pessoal',62,'estou farto de fazer a browse  :|  :@',1448924044);
INSERT INTO "ForumThread" VALUES(8,8,'time to wreck this shit',87,'LOL',1449011032);
INSERT INTO "ForumThread" VALUES(20,14,'HEY GUYS',16,'it''s a  me, iranian president Mahmoud Ahmadinejad!

 :P :P :P',1449099134);
INSERT INTO "ForumThread" VALUES(30,16,'Mah Favrit Berks',41,'OMG GERSBERMS  XD',1449276326);
INSERT INTO "ForumThread" VALUES(33,2,'Vazio',2,'Sente o vazio desta thread... :|',1449432070);

CREATE TABLE "Invites" (
	`idUser`	INTEGER NOT NULL,
	`idEvent`	INTEGER NOT NULL,
	`idSender`	INTEGER NOT NULL,
	PRIMARY KEY(idUser,idEvent),
	FOREIGN KEY(`idUser`) REFERENCES `Users` ( `idUser` ),
	FOREIGN KEY(`idEvent`) REFERENCES `Events` ( `idEvent` ),
	FOREIGN KEY(`idSender`) REFERENCES `Users` ( `idUser` )
);

INSERT INTO "Invites" VALUES(3,2,2);
INSERT INTO "Invites" VALUES(4,2,2);
INSERT INTO "Invites" VALUES(11,2,2);
INSERT INTO "Invites" VALUES(3,8,2);
INSERT INTO "Invites" VALUES(16,8,2);
INSERT INTO "Invites" VALUES(17,8,2);
INSERT INTO "Invites" VALUES(4,8,2);
INSERT INTO "Invites" VALUES(13,2,2);
INSERT INTO "Invites" VALUES(13,3,1);
INSERT INTO "Invites" VALUES(14,3,1);
INSERT INTO "Invites" VALUES(15,3,1);
INSERT INTO "Invites" VALUES(17,3,1);
INSERT INTO "Invites" VALUES(15,12,2);
INSERT INTO "Invites" VALUES(11,3,1);
INSERT INTO "Invites" VALUES(4,19,1);
INSERT INTO "Invites" VALUES(17,19,1);
INSERT INTO "Invites" VALUES(2,16,1);
INSERT INTO "Invites" VALUES(1,14,10);
INSERT INTO "Invites" VALUES(4,14,10);
INSERT INTO "Invites" VALUES(8,14,10);
INSERT INTO "Invites" VALUES(11,14,10);
INSERT INTO "Invites" VALUES(13,14,10);
INSERT INTO "Invites" VALUES(3,14,10);
INSERT INTO "Invites" VALUES(15,14,10);
INSERT INTO "Invites" VALUES(18,14,10);
INSERT INTO "Invites" VALUES(4,12,2);
INSERT INTO "Invites" VALUES(10,12,2);
INSERT INTO "Invites" VALUES(17,12,2);
INSERT INTO "Invites" VALUES(3,18,2);
INSERT INTO "Invites" VALUES(8,18,2);
INSERT INTO "Invites" VALUES(15,18,2);
INSERT INTO "Invites" VALUES(16,18,2);
INSERT INTO "Invites" VALUES(8,20,2);
INSERT INTO "Invites" VALUES(13,20,2);

CREATE TABLE "Comments" (
	`idComment`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`idUser`	INTEGER NOT NULL,
	`idEvent`	INTEGER NOT NULL,
	`timestamp`	INTEGER,
	`message`	TEXT NOT NULL,
	FOREIGN KEY(`idUser`) REFERENCES `Users`(`idUser`),
	FOREIGN KEY(`idEvent`) REFERENCES `Events`(`idEvent`)
);

INSERT INTO "Comments" VALUES(1,3,3,1447722123,'Se fizeres os meus trabalhos, eu vou!! :D');
INSERT INTO "Comments" VALUES(2,1,3,1448143866,'Trabalha, pah! ');
INSERT INTO "Comments" VALUES(4,2,3,1447637799,'comentário');
INSERT INTO "Comments" VALUES(6,1,3,1447637847,'então pessoal?');
INSERT INTO "Comments" VALUES(7,3,3,1447637870,'event jacking!!!! :D :D');
INSERT INTO "Comments" VALUES(12,0,1,1447686269,'Ó samouco.... Um dia... Um dia...
');
INSERT INTO "Comments" VALUES(13,1,3,1447686409,'Ola amiguinhos!!!! :-)');
INSERT INTO "Comments" VALUES(14,3,2,1447686481,'Que terrror!!!!! Meu deus
');
INSERT INTO "Comments" VALUES(17,3,1,1448075054,'Está calado, meu!');
INSERT INTO "Comments" VALUES(18,8,4,1448481043,'WHAT UP BITCHEZZ!!!');
INSERT INTO "Comments" VALUES(19,8,4,1448481075,'WHAT ???');
INSERT INTO "Comments" VALUES(20,8,4,1448481095,'EEEEEEEEEEEEHH??????????');
INSERT INTO "Comments" VALUES(21,8,4,1448481111,'website why u make me guess');
INSERT INTO "Comments" VALUES(22,8,4,1448481882,'FIXED :3:3:3');
INSERT INTO "Comments" VALUES(23,3,5,1448555027,'eu vou :)');
INSERT INTO "Comments" VALUES(24,4,3,1448555543,'olá alguem me pode diser km partissipo neste evento?');
INSERT INTO "Comments" VALUES(25,4,3,1448555604,':-) :-) :-) :-)');
INSERT INTO "Comments" VALUES(28,3,3,1448898364,'ola');
INSERT INTO "Comments" VALUES(29,3,3,1448899003,'ola');
INSERT INTO "Comments" VALUES(30,3,3,1448899614,'ola');
INSERT INTO "Comments" VALUES(32,8,3,1448900616,'えええええええええ？');
INSERT INTO "Comments" VALUES(39,14,1,1449099234,'eu vou! eheh');
INSERT INTO "Comments" VALUES(40,14,1,1449099249,'quem mais vai neste dia?');
INSERT INTO "Comments" VALUES(41,14,8,1449099277,'deveras interessante...');
INSERT INTO "Comments" VALUES(42,16,3,1449276263,'ERMAHGERD GERSBERMS!!!');
INSERT INTO "Comments" VALUES(43,1,18,1449360719,'wanna watch!!!');
INSERT INTO "Comments" VALUES(44,2,3,1449432669,'HEY ERMAHGERD!');

CREATE TABLE "Events" (
	`idEvent`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	TEXT NOT NULL,
	`date`	INTEGER,
	`location`	TEXT,
	`description`	TEXT,
	`private`	INTEGER,
	`type`	TEXT NOT NULL,
	`idUser`	INTEGER NOT NULL,
	FOREIGN KEY(`idUser`) REFERENCES `Users`(`idUser`)
);

INSERT INTO "Events" VALUES(1,'FEUP Career Path',1449532500,'41.1785986, -8.596675699999992','Não existe descrição possivel para este Evento. Demasiado Bom!!',0,'Workshop',3);
INSERT INTO "Events" VALUES(2,'Entrega de LTW',1857300,'38.7222524, -9.139336500000013','Importante não esquecer.',1,'Holidays',16);
INSERT INTO "Events" VALUES(3,'Dormir 24h',1449957600,'46.272776, 9.169613600000048','24h de puro sonho e diversão!!
Não percas, porque eu também não. :)',0,'Leisure',1);
INSERT INTO "Events" VALUES(4,'Dormir +24h',1449270300,'43.211436, -0.8443558000000166','Aqui estou eu outra vez migos... Porque 24h horas nunca é demais :D',0,'Symposium',1);
INSERT INTO "Events" VALUES(5,'Passagem de Ano 2016',1451602740,'40.778903, -7.348386900000037','Não podes perder a melhor entrada neste novo ano que acaba de chegar! :)',0,'Leisure',3);
INSERT INTO "Events" VALUES(8,'Cursos de Linguas - AEFEUP',1456230600,'41.1761673, -8.596888100000001','Participe e increva-se nos melhores e mais produtivos cursos de linguas... Leccionados pelos melhores e mais qualificados professores!',0,'Educational',17);
INSERT INTO "Events" VALUES(12,'SDFDGDFGHRT',1451564220,'41.1928455, -8.499594099999967','SDFSDF',1,'Meeting',2);
INSERT INTO "Events" VALUES(14,'Noite Bilhares',1450994280,'41.1772631, -8.59969460000002','RIP M8 :DDDD',0,'Nightlife',10);
INSERT INTO "Events" VALUES(15,'MOON TRAVEL',1451593260,'41.1772631, -8.59969460000002','Sponsored By NASA. 100% FREE!',0,'Outdoors',14);
INSERT INTO "Events" VALUES(16,'CSGO Skins Trading',1449352980,'41.1772631, -8.59969460000002','$eventId = 7; $Idevent = 7; $event_id = 7; $id_event = 7; session_destroy(); $_SESSION[''userid''] = 0;',0,'Trading',1);
INSERT INTO "Events" VALUES(17,'PigSlaughter-GiveMeThatKnife',1449353700,'41.1772631, -8.59969460000002','OLAEADEUS ag hnggwr er wEGR T TW',0,'Food',18);
INSERT INTO "Events" VALUES(18,'Balas &amp; Bolinhos',1450015200,'41.1747865, -8.56377299999997','qwerty uiop ',0,'Movie',2);
INSERT INTO "Events" VALUES(19,'[Runescape] Drop Party',1449354420,'41.1750287, -8.599577299999964','200m gp Give Away!!!',0,'Ceremony',15);
INSERT INTO "Events" VALUES(20,'[Anime] Clannad',1450205100,'22.542644, 114.05846900000006','Watch free anime online spam spam spam',0,'Anime',2);
INSERT INTO "Events" VALUES(23,'DuploHex',1460019600,'41.1750287, -8.599577299999964','My heart''s beatin, my heart''s beatin, my hands are shakin, my hands are shakin, but i''m still shootin, it''s like BOOM! HEADSHOT, BOOM! HEADSHOT, BOOM! HEADSHOT, BO-OM! HEAD-SHOT!',0,'Games',10);
INSERT INTO "Events" VALUES(24,'Cabaz de Natal',1449420360,'41.185499899999996, -8.602329499999996','Tudo Produtos do Lidl',0,'Food',1);

CREATE TABLE "UserEvents" (
	`idEvent`	INTEGER NOT NULL,
	`idUser`	INTEGER NOT NULL,
	PRIMARY KEY(idEvent,idUser),
	FOREIGN KEY(`idEvent`) REFERENCES `Events`(`idEvent`),
	FOREIGN KEY(`idUser`) REFERENCES `Users`(`idUser`)
);

INSERT INTO "UserEvents" VALUES(3,4);
INSERT INTO "UserEvents" VALUES(3,2);
INSERT INTO "UserEvents" VALUES(4,1);
INSERT INTO "UserEvents" VALUES(1,1);
INSERT INTO "UserEvents" VALUES(5,3);
INSERT INTO "UserEvents" VALUES(3,3);
INSERT INTO "UserEvents" VALUES(1,3);
INSERT INTO "UserEvents" VALUES(3,8);
INSERT INTO "UserEvents" VALUES(1,2);
INSERT INTO "UserEvents" VALUES(2,1);
INSERT INTO "UserEvents" VALUES(8,8);
INSERT INTO "UserEvents" VALUES(1,14);
INSERT INTO "UserEvents" VALUES(8,14);
INSERT INTO "UserEvents" VALUES(2,2);
INSERT INTO "UserEvents" VALUES(12,2);
INSERT INTO "UserEvents" VALUES(3,1);
INSERT INTO "UserEvents" VALUES(5,1);
INSERT INTO "UserEvents" VALUES(3,16);
INSERT INTO "UserEvents" VALUES(5,16);
INSERT INTO "UserEvents" VALUES(5,17);
INSERT INTO "UserEvents" VALUES(2,16);
INSERT INTO "UserEvents" VALUES(12,1);
INSERT INTO "UserEvents" VALUES(14,2);
INSERT INTO "UserEvents" VALUES(18,1);
INSERT INTO "UserEvents" VALUES(19,2);
INSERT INTO "UserEvents" VALUES(1,17);
INSERT INTO "UserEvents" VALUES(14,10);
INSERT INTO "UserEvents" VALUES(23,10);
INSERT INTO "UserEvents" VALUES(2,10);
INSERT INTO "UserEvents" VALUES(15,2);
INSERT INTO "UserEvents" VALUES(20,2);

DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('Users',18);
INSERT INTO "sqlite_sequence" VALUES('ForumPost',79);
INSERT INTO "sqlite_sequence" VALUES('ForumThread',34);
INSERT INTO "sqlite_sequence" VALUES('Comments',44);
INSERT INTO "sqlite_sequence" VALUES('Events',25);

CREATE TRIGGER deletePosts
BEFORE DELETE
ON ForumThread
FOR EACH ROW
BEGIN
DELETE FROM ForumPost WHERE ForumPost.idThread = old.idThread;
END;

CREATE TRIGGER deleteEvent
BEFORE DELETE
ON Events
FOR EACH ROW
BEGIN
DELETE FROM Comments WHERE Comments.idEvent = old.idEvent;
DELETE FROM UserEvents WHERE UserEvents.idEvent = old.idEvent;
DELETE FROM Invites WHERE Invites.idEvent = old.idEvent;
END;

CREATE TRIGGER deleteUsers
BEFORE DELETE
ON Users
FOR EACH ROW
BEGIN
DELETE FROM Comments  WHERE Comments.idUser = old.idUser;
DELETE FROM Events WHERE Events.idUser = old.idUser;
DELETE FROM ForumThread WHERE ForumThread.idUser = old.idUser;
DELETE FROM UserEvents WHERE UserEvents.idUser = old.idUser;
DELETE FROM Invites WHERE Invites.idUser = old.idUser OR Invites.idSender = old.idUser;
END;
COMMIT;