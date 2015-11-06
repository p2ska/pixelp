<?php

session_name("pixelp");
session_start();

$path = $_GET["path"];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="fonts/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<title>piXelp</title>
</head>
<body>
<div id="pixelp_path"></div>
<div id="pixelp_tags"><span class="pixelp_tag" data-tag="nature">Nature</span></div>
<div id="pixelp_content" data-path="<?php echo $path; ?>"></div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.history.js"></script>
<script type="text/javascript" src="js/store.min.js"></script>
<script type="text/javascript" src="js/pixelp.js"></script>
</body>
</html>
