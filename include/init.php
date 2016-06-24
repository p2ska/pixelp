<?php

define("ROOT_PATH",		"c:/xampp/htdocs/pixelp/");
define("IMPORT_PATH",	"import/");
define("STORAGE_PATH",	"storage/");

define("UID_TRIES",		10);										// mitu korda proovitakse uusi elemente luua, enne loobumist
define("UID_LENGTH",	6);											// uid'i pikkus
define("UID_CHARS",		"0123456789abcdefghijklmnopqrstuvwxyz");	// milliste karakterite hulgast genereeritakse uid

define("QUEUE_ADDED",	0);
define("QUEUE_WAITING",	1);
define("QUEUE_SUCCESS",	2);
define("QUEUE_FAILURE",	3);

?>
