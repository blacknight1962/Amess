<?php
require __DIR__ . '/vendor/autoload.php';

// .env 파일이 있는 디렉토리를 명시적으로 지정
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 디버깅: 환경 변수 출력
echo 'Current Directory: ' . __DIR__ . '<br>';
echo 'DB_HOST: ' . getenv('DB_HOST') . '<br>';
echo 'DB_USER: ' . getenv('DB_USER') . '<br>';
echo 'DB_PASS: ' . getenv('DB_PASS') . '<br>';
echo 'DB_NAME: ' . getenv('DB_NAME') . '<br>';

// 추가 디버깅: $_ENV 배열 출력
echo '<pre>';
print_r($_ENV);
echo '</pre>';
?>