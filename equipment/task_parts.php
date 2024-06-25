<?php

include('../db.php');

header('Content-Type: application/json'); // 응답을 JSON 형식으로 명시

if (!$conn) {
    echo json_encode(['error' => '데이터베이스 연결에 실패했습니다.']);
    exit();
}

if (isset($_POST["add_task_part"])) {
    $taskpart = isset($_POST['taskpart']) ? $_POST['taskpart'] : null;

    if ($taskpart !== null) {
        $taskpart = mysqli_real_escape_string($conn, $taskpart);

        $insertQuery = "INSERT INTO task_part (taskpart) VALUES (?)";
        $insertStmt = $conn->prepare($insertQuery);
        if ($insertStmt) {
            $insertStmt->bind_param("s", $taskpart);
            $insertStmt->execute();

            if ($insertStmt->affected_rows > 0) {
                $newTpNo = $conn->insert_id;  // 새로 추가된 항목의 tp_no
                echo json_encode(['tp_no' => $newTpNo, 'taskpart' => $taskpart]);
            } else {
                echo json_encode(['error' => '데이터베이스에 추가 실패.']);
            }
            $insertStmt->close();
        } else {
            echo json_encode(['error' => '쿼리 준비에 실패했습니다: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8')]);
        }
    } else {
        echo json_encode(['error' => '필수 데이터가 누락되었습니다.']);
    }
} else {
    echo json_encode(['error' => '잘못된 요청입니다.']);
}

exit();
?>