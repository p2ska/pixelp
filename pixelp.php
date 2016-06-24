<?php

session_name("pixelp");
session_start();

if (!isset($_GET["pixelp"]))
	return false;

require "c:/xampp/security/pixelp/_connector.php";
require "classes/db.php";
require "classes/pixelp.php";
require "classes/translations.php";

//$db = new DATABASE();
//$db->connect(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHARSET, DB_COLLATION);

//$tr = new TRANSLATIONS();
//$l = $tr->import("classes/translations.lang");

//$pixelp = new PIXELP($_GET["pixelp"]);

//echo $pixelp->content;

// we are done here

function dump($this, $die = false) {
	echo "<pre>";
	print_r($this);
	echo "</pre>";

	if ($die)
		die();
}

?>
