<?php
ob_start(); // 출력 버퍼링 시작
session_start();
header('Content-Type: application/json');
include('../../db.php');

if (isset($_POST['quote_no'])) {
    $quoteNo = $_POST['quote_no'];
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("DELETE FROM quote_data WHERE quote_no = ?");
        $stmt->bind_param("s", $quoteNo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            throw new Exception("상세 정보 삭제 실패");
        }

        $stmt = $conn->prepare("DELETE FROM quote WHERE quote_no = ?");
        $stmt->bind_param("s", $quoteNo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            throw new Exception("기본 정보 삭제 실패");
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => '삭제 성공']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
}
   ob_end_flush(); // 버퍼의 내용을 출력하고 버퍼를 종료


//견적 신규등록을 위한 저장 
if (isset($_POST['quot_num'])) {
    $quote_no = mysqli_real_escape_string($conn, $_POST['quot_num']);
    $quot_date = mysqli_real_escape_string($conn, $_POST['quot_date']);
    $quot_cust = mysqli_real_escape_string($conn, $_POST['customer']);
    $quot_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $quot_pic = mysqli_real_escape_string($conn, $_POST['user_name']);
    $quot_picb = mysqli_real_escape_string($conn, $_POST['picb']);

    if (empty($quote_no) || empty($quot_date)) {
        header('location:new_quote.php?error=필수 데이터가 누락되었습니다');
        exit();
    } 

    //견적번호 중복체크
    $Duple_check = "SELECT * FROM quote WHERE quote_no = '$quote_no'";
    $order = mysqli_query($conn, $Duple_check);

    if (mysqli_num_rows($order) > 0) {
        header('location:new_quote.php?error=같은 견적번호가 DB에 이미 존재합니다');
        exit();
    } 
        $sql_save = "INSERT INTO quote (quote_no, quote_date, customer, customer_name, pic, picb)
            values('$quote_no', '$quot_date','$quot_cust', '$quot_name', '$quot_pic','$quot_picb')";

        $result = mysqli_query($conn, $sql_save);
        if ($result) {
        header("Location: new_quote.php?quote_no=$quote_no&status=saved");
        } else {
        echo "저장에 문제가 있습니다. 관리자에게 연락하십시오" . mysqli_error($conn);
        exit();
        }
}
// 견적 상세정보 Update
if (isset($_POST['update_btn'])) {
    $quote_no = trim($_POST['quote_no'], "'"); // 양쪽 끝의 따옴표 제거
    $quote_no = mysqli_real_escape_string($conn, $quote_no);
    $updateSuccess = false;
    $updateFailed = false;

    if (isset($_POST['sub_no'])) {
        for ($i = 0; $i < count($_POST['sub_no']); $i++) {
            $sub_no = mysqli_real_escape_string($conn, $_POST['sub_no'][$i]);
            $group_p = mysqli_real_escape_string($conn, $_POST['group_p'][$i]);
            $sulbi = mysqli_real_escape_string($conn, $_POST['sulbi'][$i]);
            $model = mysqli_real_escape_string($conn, $_POST['model'][$i]);
            $apart = mysqli_real_escape_string($conn, $_POST['apart'][$i]);
            $product_na = mysqli_real_escape_string($conn, $_POST['product_na'][$i]);
            $product_sp = mysqli_real_escape_string($conn, $_POST['product_sp'][$i]);
            $p_code = mysqli_real_escape_string($conn, $_POST['p_code'][$i]);
            $price = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['price'][$i]));
            $qty = mysqli_real_escape_string($conn, $_POST['qty'][$i]);
            $amt = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['amt'][$i]));
            $progress = mysqli_real_escape_string($conn, $_POST['progress'][$i]);
            $r_quot = mysqli_real_escape_string($conn, $_POST['r_quot'][$i]);
            $specif = mysqli_real_escape_string($conn, $_POST['specif'][$i]);

            // 로그 추가
            error_log("sub_no: $sub_no, group_p: $group_p, sulbi: $sulbi, model: $model, apart: $apart, product_na: $product_na, product_sp: $product_sp, p_code: $p_code, price: $price, qty: $qty, amt: $amt, progress: $progress, r_quot: $r_quot, specif: $specif, quote_no: $quote_no");

            if (empty($price) || empty($qty)) {
                continue;
            }

            $sql = "INSERT INTO quote_data (quote_no, sub_no, group_p, sulbi, model, apart, product_na, product_sp, p_code, price, qty, amt, progress, r_quot, specif)
                    VALUES ('$quote_no', '$sub_no', '$group_p', '$sulbi', '$model', '$apart', '$product_na', '$product_sp', '$p_code', '$price', '$qty', '$amt', '$progress', '$r_quot', '$specif')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $updateSuccess = true;
            } else {
                // 로그 추가
                error_log("MySQL Error: " . mysqli_error($conn));
                $updateFailed = true;
            }
        }
    }

    if ($updateSuccess && !$updateFailed) {
        $_SESSION['message'] = '상세 정보 저장 성공';
        header("Location: quote_index.php?quote_no=$quote_no&status=saved");
    } else {
        $_SESSION['message'] = '상세 정보 저장 실패';
        header("Location: edit_quot.php?quote_no=$quote_no&status=error");
    }
    exit;
}

// 견적 상세 삭제
// POST 요청으로 받은 sub_no 값 확인

if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['sub_no'])) {
    $subNo = mysqli_real_escape_string($conn, $_POST['sub_no']);
    $sql = "DELETE FROM quote_data WHERE sub_no = '$subNo'";
    if (mysqli_query($conn, $sql)) {
        $response = ['status' => 'success', 'message' => '삭제 성공'];
    } else {
        $response = ['status' => 'error', 'message' => '삭제 실패: ' . mysqli_error($conn)];
    }
    // JSON 응답을 반환하기 전에 출력 버퍼를 비웁니다.
    ob_end_clean();
    echo json_encode($response);
    exit();
}


// 견적관리 메인화면으로 DB에 요청하는 기간만큼 데이터 불어오기
if (isset($_GET['period'])) {
    $period = $_GET['period'];
    $currentYear = date("Y");

    if ($period == '1year') {
        $startDate = date('Y-m-d', strtotime('-1 year'));
    } elseif ($period == '3years') {
        $startDate = date('Y-m-d', strtotime('-3 years'));
    } else {
        $startDate = $period . '-01-01';
        $endDate = $period . '-12-31';
    }
        // 데이터베이스에서 데이터 조회
    $sql = "SELECT q.*, qd.* FROM quote q JOIN quote_data qd ON q.quote_no = qd.quote_no WHERE q.quote BETWEEN '$startDate' AND '$endDate'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // 데이터가 있는 경우, 데이터를 출력
        while ($row = mysqli_fetch_assoc($result)) {
            // 데이터 출력 로직
        }
    } else {
        // 데이터가 없는 경우, 빈 행을 출력하거나 메시지 출력
        echo "<tr><td colspan='15'>데이터가 없습니다.</td></tr>";
    }
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // JSON으로 변환 전에 헤더 설정
    header('Content-Type: application/json');
    $json_data = json_encode($data);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON encoding error: " . json_last_error_msg();
        exit;
    }
    echo $json_data;
        echo json_encode($data);
        exit;
    }
?>
