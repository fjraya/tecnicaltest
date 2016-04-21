<?php

$sqlite = new SQLite3("../integration/resources/test.sqlite");
$sqlite->exec("CREATE TABLE IF NOT EXISTS Users (username varchar(25) not null, password varchar(50) not null, roles varchar(10) not null, PRIMARY KEY (username))");
?>