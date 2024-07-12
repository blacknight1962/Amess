<?php
session_start();
include(__DIR__ . '/../../db.php');

if (isset($_POST['quote_no'])) {
    $quoteNo = $_POST['quote_no'];
    error_log("Deleting quote number: $quoteNo"); // 로그 추가
    $conn->begin_transaction();
    try {
        // 상세 정보 삭제 시도
        $stmt = $conn->prepare("DELETE FROM quote_data WHERE quote_no = ?");
        if (!$stmt) {
            throw new Exception("쿼리 준비 실패: " . $conn->error);
        }
        $stmt->bind_param("s", $quoteNo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            error_log("상세 정보가 없습니다. 계속 진행합니다."); // 로그 추가
        }        
        // 기본 정보 삭제 시도
        $stmt = $conn->prepare("DELETE FROM quote WHERE quote_no = ?");
        if (!$stmt) {
            throw new Exception("쿼리 준비 실패: " . $conn->error);
        }
        $stmt->bind_param("s", $quoteNo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            throw new Exception("기본 정보 삭제 실패: " . $stmt->error);
        }

        $conn->commit();
        $_SESSION['message'] = '삭제 성공';
        $_SESSION['message_type'] = 'success';
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error: " . $e->getMessage()); // 로그 추가
        $_SESSION['message'] = '삭제 실패: ' . $e->getMessage();
        $_SESSION['message_type'] = 'error';
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    $stmt->close();
} else {
    error_log("잘못된 요청입니다."); // 로그 추가
    $_SESSION['message'] = '잘못된 요청입니다.';
    $_SESSION['message_type'] = 'error';
    echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
}
exit();

// 견적 상세 삭제

if (isset($_POST["sub_no"])) {
    $sub_no = $_POST["sub_no"];
    $sql = "DELETE FROM quote_data WHERE sub_no = '$sub_no'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $response = ['status' => 'success', 'message' => '성공적으로 데이터를 삭제했습니다.'];
    } else {
        $response = ['status' => 'error', 'message' => '데이터 삭제 하지 못했습니다.'];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    $response = ['status' => 'error', 'message' => '데이터가 정상적이지 않아서 처리되지 못했습니다'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>