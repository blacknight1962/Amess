<?php
require 'db.php';
require 'router.php';
$uri = trim($_SERVER['REQUEST_URI'], '/');
require route($uri);
?>