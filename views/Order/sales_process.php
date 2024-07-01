<?php
ob_start();
session_start();
header('Content-Type: application/json');
include(__DIR__ . '/../../db.php');

// var_dump($_POST);
// ob_end_flush();
// exit;
error_log('Request received with data: ' . print_r($_POST, true));

// $order_no 초기화를 if 블록 밖으로 이동
$order_no = $_POST['order_no'] ?? '';
error_log('Processed data for order_no: ' . $order_no);

if (isset($_POST['action_type'])) {
    $action_type = $_POST['action_type'];
    $serial_no = $_POST['serial_no'][0] ?? '';
    // $_POST['sales_rate'] 배열을 $sales_rates 변수에 할당
    $sales_rates = $_POST['sales_rate'] ?? [];  // 이렇게 수정하면 $sales_rates는 항상 배열입니다.


    switch ($action_type) {
        case 'delete_sales':
            $conn->begin_transaction();
            try {
                $stmt = $conn->prepare("DELETE FROM sales_data WHERE order_no = ? AND serial_no = ?");
                $stmt->bind_param("ss", $order_no, $serial_no);
                $stmt->execute();
                if ($stmt->affected_rows == 0) {
                    throw new Exception("No rows affected");
                }
                $conn->commit();
                echo json_encode(['status' => 'success', 'message' => '삭제 성공']);
            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            } finally {
                $stmt->close();
            }
            break;

        //입금확인 절차로 2개 테이블에 필드 업데이트
        case 'save_sales':
            function processInstallmentSale($conn, $order_no, $o_no, $sales_date, $amt, $condit, $sales_remark) {
                // order_data 테이블 업데이트: sales_remark 제거
                $stmt = $conn->prepare("UPDATE order_data SET sales_date = ?, amt = ?, condit = ? WHERE o_no = ? AND order_no = ?");
                $stmt->bind_param("sssss", $sales_date, $amt, $condit, $o_no, $order_no);
                if (!$stmt->execute()) {
                    throw new Exception("데이터베이스 업데이트 실패: " . $stmt->error);
                }
                $stmt->close();

                // sales_data 테이블 업데이트
                $sales_rate = ($condit === '완료') ? $condit : '0'; 
                $stmt = $conn->prepare("UPDATE sales_data SET sales_date = ?, sales_amt = ?, sales_rate = ?, sales_remark = ? WHERE serial_no = ? AND order_no = ?");
                $stmt->bind_param("ssssss", $sales_date, $amt, $sales_rate, $sales_remark, $o_no, $order_no);
                if (!$stmt->execute()) {
                    throw new Exception("데이터베이스 업데이트 실패: " . $stmt->error);
                }
                $stmt->close();
            }

            function processLumpSumSale($conn, $order_no, $o_no, $sales_date, $amt, $condit, $sales_remark) {
                $stmt = $conn->prepare("UPDATE order_data SET sales_date = ?, amt = ?, condit = ? WHERE o_no = ? AND order_no = ?");
                $stmt->bind_param("sssss", $sales_date, $amt, $condit, $o_no, $order_no);
                $stmt = $conn->execute("UPDATE 성공");
                if ($stmt) {
                    $conn->commit();
                    $_SESSION['message'] = '업데이트 성공';
                    echo json_encode(['status' => 'success', 'message' => '업데이트 성공']);
                } else {
                    throw new Exception("데이터베이스 업데이트 실패");
                }
                $stmt->close();

                $stmt = $conn->prepare("UPDATE sales_data SET sales_date = ?, sales_amt = ?, sales_rate = ?, sales_remark = ? WHERE serial_no = ? AND order_no = ?");
                $stmt->bind_param("ssssss", $sales_date, $amt, $condit, $sales_remark, $o_no, $order_no);
                $stmt->execute();
                $stmt = $conn->execute("UPDATE 성공");
                if ($stmt) {
                    $conn->commit();
                    $_SESSION['message'] = '업데이트 성공';
                    echo json_encode(['status' => 'success', 'message' => '업데이트 성공']);
                } else {
                    throw new Exception("데이터베이스 업데이트 실패");
                }
                $stmt->close();
            }

            // 메인 로직
                $conn->begin_transaction();
                try {
                    $o_no = isset($_POST['o_no']) ? $_POST['o_no'] : [];
                    $order_no = $_POST['order_no'];
                    $sales_dates = $_POST['sales_date'];
                    $amt = $_POST['amt'];
                    $sales_remarks = $_POST['sales_remark'];

                    if (is_array($_POST['o_no'])) {
                        for ($i = 0; $i < count($_POST['o_no']); $i++) {
                            $o_no = $_POST['o_no'][$i] ?? '';
                            $sales_date = $_POST['sales_date'][$i] ?? '';
                            $sales_amt = str_replace(',', '', $_POST['amt'][$i]);
                            $condit = '완료';
                            $sales_remark = $_POST['sales_remark'][$i] ?? '';
                            processInstallmentSale($conn, $order_no, $o_no, $sales_date, $sales_amt, $condit, $sales_remark);
                        }
                    } else {
                        $o_no = $o_no ?? '';
                        $sales_date = $sales_dates ?? '';
                        $amt = str_replace(',', '', $amt);
                        $condit = '완료';
                        $sales_remark = $sales_remarks ?? '';
                        processLumpSumSale($conn, $order_no, $o_no, $sales_date, $amt, $condit, $sales_remark);
                    }
                    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();

    echo $e->getMessage();
    exit;
                }
            }
            }
ob_end_flush();
?>