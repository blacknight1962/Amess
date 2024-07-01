    <?php
    $servername = getenv('DB_HOST');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $database = getenv('DB_NAME');

    // Debugging: 환경 변수 출력
    error_log("DB_HOST: " . $servername);
    error_log("DB_USER: " . $username);
    error_log("DB_PASS: " . $password);
    error_log("DB_NAME: " . $database);

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("DB 접속 실패: " . mysqli_connect_error());
    }
?>