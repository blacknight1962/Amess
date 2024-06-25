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
//상세정보 저장
//var_dump($_POST);
//exit(); 
if(isset($_POST['slno'])){
//Array에 저장된 입력값들을 DB로~
    for($i=0;$i<count($_POST['sub_no']);$i++){
    $sub_no = mysqli_real_escape_string($conn, $_POST['sub_no'][$i]);
    $group_p = mysqli_real_escape_string($conn, $_POST['group_p'][$i]);
    $sulbi = mysqli_real_escape_string($conn, $_POST['sulbi'][$i]);
    $model = mysqli_real_escape_string($conn, $_POST['model'][$i]);
    $apart = mysqli_real_escape_string($conn, $_POST['apart'][$i]);
    $product_na = mysqli_real_escape_string($conn, $_POST['product_na'][$i]);
    $product_sp = mysqli_real_escape_string($conn, $_POST['product_sp'][$i]);
    $p_code = mysqli_real_escape_string($conn, $_POST['p_code'][$i]);
    $price = mysqli_real_escape_string($conn, $_POST['price'][$i]);
    $qty = mysqli_real_escape_string($conn, $_POST['qty'][$i]);
    $amt = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['amt'][$i]));
    $progress = mysqli_real_escape_string($conn, $_POST['progress'][$i]);
    $r_quot = mysqli_real_escape_string($conn, $_POST['r_quot'][$i]);
    $specif = mysqli_real_escape_string($conn, $_POST['specif'][$i]);
    $quote_no = isset($_POST['quote_no']) ? mysqli_real_escape_string($conn, $_POST['quote_no']) : null;
    if(empty($price) || empty($qty)){
        continue;
    }

$sql="INSERT INTO quote_data(quote_no,sub_no,group_p,sulbi,model,apart,product_na,product_sp,p_code,price,qty,amt,progress,r_quot,specif)
        VALUES('$quote_no','$sub_no','$group_p','$sulbi','$model','$apart','$product_na','$product_sp','$p_code','$price','$qty','$amt','$progress','$r_quot','$specif')";
$result = mysqli_query($conn, $sql);

    if (!$result) {
      // 폼을 제출한 후, 데이터 처리 페이지에서
    // 실패 메시지와 함께 원래 페이지로 리다이렉트
    header('Location: new_quote.php?error=failed&data=' . urlencode(serialize($_POST)));
    exit;
}
    header("Location: quote_index.php?quote_no=$quote_no&status=saved");
}
}


// var_dump($_POST);
// exit();
/* 견적 상세정보 Update */
if (isset($_POST['update_btn'])) {
    $quote_no = trim($_POST['quote_no'], "'"); // 양쪽 끝의 따옴표 제거
    $quote_no = mysqli_real_escape_string($conn, $quote_no);
    $updateSuccess = false;
    $updateFailed = false;

    for ($i = 0; $i < count($_POST['sub_no']); $i++) {
        $sub_no = mysqli_real_escape_string($conn, $_POST['sub_no'][$i]);
        $group_p = mysqli_real_escape_string($conn, $_POST['group_p'][$i]);
        $sulbi = mysqli_real_escape_string($conn, $_POST['sulbi'][$i]);
        $model = mysqli_real_escape_string($conn, $_POST['model'][$i]);
        $apart = mysqli_real_escape_string($conn, $_POST['apart'][$i]);
        $product_na = mysqli_real_escape_string($conn, $_POST['product_na'][$i]);
        $product_sp = mysqli_real_escape_string($conn, $_POST['product_sp'][$i]);
        $p_code = mysqli_real_escape_string($conn, $_POST['p_code'][$i]);
        $price = mysqli_real_escape_string($conn, $_POST['price'][$i]);
        $qty = mysqli_real_escape_string($conn, $_POST['qty'][$i]);
        $amt = mysqli_real_escape_string($conn, $_POST['amt'][$i]);
        $progress = mysqli_real_escape_string($conn, $_POST['progress'][$i]);
        $r_quot = mysqli_real_escape_string($conn, $_POST['r_quot'][$i]);
        $specif = mysqli_real_escape_string($conn, $_POST['specif'][$i]);
        
        $price = str_replace(',', '', $price); // 콤마 제거
        $amt = str_replace(',', '', $amt); // 콤마 제거

        $stmt = $conn->prepare("UPDATE quote_data SET group_p=?, sulbi=?, model=?, apart=?, product_na=?, product_sp=?, p_code=?, price=?, qty=?, amt=?, progress=?, r_quot=?, specif=? WHERE quote_no=? AND sub_no=?");
        $stmt->bind_param("sssssssssssssss", $group_p, $sulbi, $model, $apart, $product_na, $product_sp, $p_code, $price, $qty, $amt, $progress, $r_quot, $specif, $quote_no, $sub_no);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $updateSuccess = true;
            }
        } else {
            $updateFailed = true;
            $_SESSION['status'] = "데이터 저장에 실패했습니다: " . $stmt->error;
            break; // 에러 발생 시 루프 중단
        }
    }
    $stmt->close();

    if ($updateFailed) {
        header("Location: quot_index.php?quote_no=$quote_no&status=error");
    } else if ($updateSuccess) {
        $_SESSION['status'] = "성공적으로 데이터를 저장했습니다.";
        header("Location: quot_index.php?quote_no=$quote_no&status=saved");
    } else {
        $_SESSION['status'] = "변경된 데이터가 없습니다.";
        header("Location: quot_index.php?quote_no=$quote_no&status=unchanged");
    }
    exit;
}

//견적 삭제 처리


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
