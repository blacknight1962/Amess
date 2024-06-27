<?php
include('../../db.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task_part'])) {
    $taskpart = $_POST['taskpart'];
    $tp_no = $_POST['tp_no'];

    // 데이터베이스에 삽입
    $sql = "INSERT INTO task_part (tp_no, hangmok) VALUES ('$tp_no', '$taskpart')";
    if (mysqli_query($conn, $sql)) {
        $response['success'] = true;
        $response['tp_no'] = $tp_no;
        $response['taskpart'] = $taskpart;
    } else {
        $response['success'] = false;
        $response['error'] = '데이터베이스 삽입 중 오류가 발생했습니다.';
    }
} else {
    $response['success'] = false;
    $response['error'] = '잘못된 요청입니다.';
}

// JSON 응답 반환
header('Content-Type: application/json');
echo json_encode($response);
?>