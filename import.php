<?php

require "c:/xampp/security/pixelp/_connector.php";
require "include/init.php";
require "classes/db.php";
require "classes/pixelp.php";

$db = new DATABASE();
$db->connect(DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHARSET, DB_COLLATION);

$import = new PIXELP_IMPORT($db);

//$import->add_queue();
$import->process_queue();

?>
