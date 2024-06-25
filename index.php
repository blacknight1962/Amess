<?php
// 부트스트래핑 코드
require 'db.php';
require 'router.php';

// 라우팅 로직
$uri = trim($_SERVER['REQUEST_URI'], '/');
require route($uri);
?>