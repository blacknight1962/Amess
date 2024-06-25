<?php
include('../db.php');  // 데이터베이스 연결 포함
// var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_no = $_POST['t_no'];
    $seri_no = $_POST['seri_no'];

    $query = "DELETE FROM task_manage WHERE t_no = ? AND seri_no = ?";
    $stmt = $conn->prepare($query);
    if ($stmt->execute([$t_no, $seri_no])) {
        echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete task']);
    }
    $stmt->close();
    $conn->close();
}
?>