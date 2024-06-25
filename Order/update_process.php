<?php
ob_start(); // 출력 버퍼링 시작
session_start();
header('Content-Type: application/json');
include('../db.php');
$_SESSION['condit'] = $condit;

var_dump($_POST);
exit(); 
$action_type = isset($_POST['action_type']) ? $_POST['action_type'] : 'default';

switch ($action_type) {
    case 'delete_order':
        error_log("Entered delete_order case");
        if (isset($_POST['order_no'])) {
            $orderNo = $_POST['order_no'];
            error_log($orderNo);
              $conn->begin_transaction();
            try {
                $stmt = $conn->prepare("DELETE FROM order_data WHERE order_no = ?");
                $stmt->bind_param("s", $orderNo);
                $stmt->execute();
                if ($stmt->affected_rows == 0) {
                    throw new Exception("상세 정보 삭제 실패");
                }

                $stmt = $conn->prepare("DELETE FROM `order` WHERE order_no = ?");
                $stmt->bind_param("s", $orderNo);
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
        break;
    case 'update_basic':   
        error_log("Entered update_basic case");      // 기본 정보 저장 로직
        if (isset($_POST['order_no'])) {
            $order_no = mysqli_real_escape_string($conn, $_POST['order_no']);
            $order_custo = mysqli_real_escape_string($conn, $_POST['order_custo']);
            $customer = mysqli_real_escape_string($conn, $_POST['customer_na']);
            $order_date = mysqli_real_escape_string($conn, $_POST['order_date']);
            $custo_name = mysqli_real_escape_string($conn, $_POST['custo_name']);
            $specifi = mysqli_real_escape_string($conn, $_POST['specifi']);
            $production_code = mysqli_real_escape_string($conn, $_POST['production_code']);
            $production_start = mysqli_real_escape_string($conn, $_POST['production_start']);

            if (empty($order_no) || empty($order_date)) {
                header('location:order_new.php?error=필수 데이터가 누락되었습니다');
                exit();
            } 
            //발주번호 중복체크
            $Duple_check = "SELECT * FROM `order` WHERE order_no = '$order_no'";
            $sql_save = "UPDATE `order` SET order_no = '$order_no', order_custo = '$order_custo', customer = '$customer', order_date = '$order_date', custo_name = '$custo_name', specifi = '$specifi', production_code = '$production_code', production_start = '$production_start' WHERE order_no = '$order_no'";
            $result = mysqli_query($conn, $sql_save);
                    if ($result) {
                    header("Location: order_update.php?order_no=$order_no&status=saved");
                    } else {
                    echo "저장에 문제가 있습니다. 관리자에게 연락하십시오" . mysqli_error($conn);
                    exit();
                    }
            }
        
            break;
        
    case 'update_installment':
        $conn->begin_transaction();
        try {
            $order_no = $_POST['order_no'];
            $serial_no = $_POST['serial_no']; // o_no를 serial_no로 사용
            $condit = $_POST['condit'];

            // 데이터 존재 여부 확인
            $check_sql = "SELECT COUNT(*) FROM sales_data WHERE order_no = ? AND serial_no = ? AND condit = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("sss", $order_no, $serial_no, $condit);
            $stmt->execute();
            $result = $stmt->get_result();
            $exists = $result->fetch_array()[0] > 0;

            if ($exists) {
                // 데이터 업데이트
                $update_sql = "UPDATE sales_data SET sales_rate = ?, sales_date = ?, sales_amt = ?, sales_remark = ? WHERE order_no = ? AND serial_no = ? AND condit = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sssssss", $_POST['sales_rate'], $_POST['sales_date'], $_POST['sales_amt'], $_POST['sales_remark'], $order_no, $serial_no, $condit);
            } else {
                // 데이터 삽입
                $insert_sql = "INSERT INTO sales_data (order_no, serial_no, condit, sales_rate, sales_date, sales_amt, sales_remark) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("sssssss", $order_no, $serial_no, $condit, $_POST['sales_rate'], $_POST['sales_date'], $_POST['sales_amt'], $_POST['sales_remark']);
            }
            $stmt->execute();
            $conn->commit();
            echo "Operation successful";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    
    break;
}