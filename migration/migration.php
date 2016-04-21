<?php
require_once __DIR__ . "/../src/model/ViewUser.php";
$sqlite = new SQLite3("../db/project.sqlite");
$sqlite->exec("CREATE TABLE IF NOT EXISTS Users (username varchar(25) not null, password varchar(50) not null, roles varchar(10) not null, PRIMARY KEY (username))");
$sqlite->exec("DELETE FROM Users");
$sqlite->exec("INSERT INTO Users (username, password, roles) values ('user1', '".md5('password1')."', '".ViewUser::PAGE_1."')");
$sqlite->exec("INSERT INTO Users (username, password, roles) values ('user2', '".md5('password2')."', '".ViewUser::PAGE_2.",".ViewUser::PAGE_3."')");
$sqlite->exec("INSERT INTO Users (username, password, roles) values ('user3', '".md5('password3')."', '".ViewUser::PAGE_3.",".ViewUser::PAGE_1."')");
$sqlite->exec("INSERT INTO Users (username, password, roles) values ('admin', '".md5('password4')."', '".ViewUser::ADMIN."')");

?>
