<?php

define("ROOT_PATH",		"c:/xampp/htdocs/pixelp/");
define("IMPORT_PATH",	"import/");
define("STORAGE_PATH",	"storage/");

define("ID_TRIES",		10);										// mitu korda proovitakse uusi elemente luua, enne loobumist
define("ID_LENGTH",		6);											// id'i pikkus
define("ID_CHARS",		"0123456789abcdefghijklmnopqrstuvwxyz");	// milliste karakterite hulgast genereeritakse id

define("QUEUE_ADDED",	0);
define("QUEUE_WAITING",	1);
define("QUEUE_SUCCESS",	2);
define("QUEUE_FAILURE",	3);

define("SQL_DATETIME",	"Y-m-d H:i:s");

?>
