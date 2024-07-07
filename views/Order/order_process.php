<?php
ob_start(); // 출력 버퍼링 시작
session_start();
header('Content-Type: application/json');
include(__DIR__ . '/../../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action_type = isset($_POST['action_type']) ? $_POST['action_type'] : '';

    switch ($action_type) {
        case 'save_basic':
            $order_no = isset($_POST['order_no']) ? mysqli_real_escape_string($conn, $_POST['order_no']) : '';   
            $order_custo = mysqli_real_escape_string($conn, $_POST['order_custo']);
            $customer = mysqli_real_escape_string($conn, $_POST['customer_na']);
            $order_date = mysqli_real_escape_string($conn, $_POST['order_date']);
            $custo_name = mysqli_real_escape_string($conn, $_POST['custo_name']);
            
            $production_code = mysqli_real_escape_string($conn, $_POST['production_code']);
            $production_start = mysqli_real_escape_string($conn, $_POST['production_start']);

            if (empty($order_no) || empty($order_date)) {
                $_SESSION['message'] = '필수 데이터가 누락되었습니다';
                header('Location: order_new.php');
                exit();
            }

            // 발주번호 중복체크
            $Duple_check = "SELECT * FROM `order` WHERE order_no = '$order_no'";
            $result = mysqli_query($conn, $Duple_check);

            if (mysqli_num_rows($result) > 0) {
                // Update
                $sql = "UPDATE `order` SET order_custo = '$order_custo', customer = '$customer', order_date = '$order_date', custo_name = '$custo_name', production_code = '$production_code', production_start = '$production_start' WHERE order_no = '$order_no'";
            } else {
                // Insert
                $sql = "INSERT INTO `order` (order_no, order_custo, customer, order_date, custo_name, production_code, production_start) VALUES ('$order_no', '$order_custo', '$customer', '$order_date', '$custo_name', '$production_code', '$production_start')";
            }

            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = '발주가 성공적으로 저장되었습니다.';
                header('Location: order_index.php?status=saved');
                exit();
            } else {
                $_SESSION['message'] = '발주 저장에 실패했습니다.';
                header('Location: order_index.php?status=error');
                exit();
            }
            break;

        case 'save_detail':
            $conn->begin_transaction();
            try {
                foreach ($_POST['o_no'] as $i => $o_no) {
                    $order_no = mysqli_real_escape_string($conn, $_POST['order_no']);
                    $o_no = mysqli_real_escape_string($conn, $_POST['o_no'][$i]);
                    $picb = mysqli_real_escape_string($conn, $_POST['picb'][$i]);
                    $specifi = mysqli_real_escape_string($conn, $_POST['specifi'][$i]);
                    $parts_code = mysqli_real_escape_string($conn, $_POST['parts_code'][$i]);
                    $product_na = mysqli_real_escape_string($conn, $_POST['product_na'][$i]);
                    $product_sp = mysqli_real_escape_string($conn, $_POST['product_sp'][$i]);
                    $requi_date = mysqli_real_escape_string($conn, $_POST['requi_date'][$i]);
                    $price = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['price'][$i]));
                    $qty = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['qty'][$i]));
                    $amt = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['amt'][$i]));
                    $curency_rate = mysqli_real_escape_string($conn, $_POST['curency_rate'][$i]);
                    $sales_date = mysqli_real_escape_string($conn, $_POST['sales_date'][$i]);
                    $condit = mysqli_real_escape_string($conn, $_POST['condit'][$i]);

                    if (empty($price) || empty($qty)) {
                        continue;
                    }

                    // order_data에 데이터 삽입 또는 업데이트
                    $sql_check = "SELECT * FROM `order_data` WHERE order_no = '$order_no' AND o_no = '$o_no'";
                    $result_check = mysqli_query($conn, $sql_check);

                    if (mysqli_num_rows($result_check) > 0) {
                        $sql = "UPDATE `order_data` SET picb = '$picb', specifi = '$specifi', parts_code = '$parts_code', product_na = '$product_na', product_sp = '$product_sp', requi_date = '$requi_date', price = '$price', currency = '$currency', qty = '$qty', amt = '$amt', curency_rate = '$curency_rate', sales_date = '$sales_date', condit = '$condit' WHERE order_no = '$order_no' AND o_no = '$o_no'";
                    } else {
                        $sql = "INSERT INTO `order_data` (order_no, o_no, picb, specifi, parts_code, product_na, product_sp, requi_date, price, currency, qty, amt, curency_rate, sales_date, condit) VALUES ('$order_no', '$o_no', '$picb', '$specifi', '$parts_code', '$product_na', '$product_sp', '$requi_date', '$price', '$currency', '$qty', '$amt', '$curency_rate', '$sales_date', '$condit')";
                    }

                    if (!mysqli_query($conn, $sql)) {
                        throw new Exception('발주 저장에 실패했습니다.');
                    }
                }

                // sales_data에 데이터 삽입
                $sales_remark = ''; // 비고 정보가 없으므로 빈 문자열 사용
                $stmt = $conn->prepare("INSERT INTO sales_data (order_no, serial_no, sales_date, sales_rate, sales_amt, sales_remark) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $order_no, $o_no, $sales_date, $condit, $amt, $sales_remark);
                if (!$stmt->execute()) {
                    throw new Exception("Sales data insertion failed: " . $stmt->error);
                }

                // 모든 작업이 성공적으로 완료되면 커밋
                $conn->commit();
                $_SESSION['message'] = '저장되었습니다';
                header("Location: order_index.php?order_no=$order_no&status=saved");
            } catch (Exception $e) {
                // 오류 발생 시 롤백
                $conn->rollback();
                $_SESSION['message'] = '저장에 문제가 있습니다. 관리자에게 연락하십시오: ' . $e->getMessage();
                header("Location: order_new.php");
            }
            exit();
            break;

        case 'nonePO':
            $conn->begin_transaction(); // 트랜잭션 시작
            try {
                $order_no = $_POST['order_no'] ?? null; // PHP 7.0+ null coalescing operator 사용
                $customer = $_POST['customer_na'] ?? 'Unknown'; // 고객 이름 처리, 기본값 'Unknown'

                // o_no를 null로 설정하여 order 테이블에 저장하지 않음
                $stmt = $conn->prepare("INSERT INTO `order` (order_no, customer) VALUES(?, ?)");
                $stmt->bind_param("ss", $order_no, $customer);
                $stmt->execute();

                for ($i = 0; $i < count($_POST['o_no']); $i++) {
                    $o_no = mysqli_real_escape_string($conn, $_POST['o_no'][$i]);
                    $picb = mysqli_real_escape_string($conn, $_POST['picb'][$i]);
                    $parts_code = mysqli_real_escape_string($conn, $_POST['parts_code'][$i]);
                    $product_na = mysqli_real_escape_string($conn, $_POST['product_na'][$i]);
                    $product_sp = mysqli_real_escape_string($conn, $_POST['product_sp'][$i]);
                    $requi_date = mysqli_real_escape_string($conn, $_POST['requi_date'][$i]);
                    $price = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['price'][$i]));
                    $currency = mysqli_real_escape_string($conn, $_POST['currency'][$i]);
                    $qty = mysqli_real_escape_string($conn, $_POST['qty'][$i]);
                    $amt = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['amt'][$i]));
                    $curency_rate = mysqli_real_escape_string($conn, $_POST['curency_rate'][$i]);
                    $sales_date = mysqli_real_escape_string($conn, $_POST['sales_date'][$i]);
                    $condit = mysqli_real_escape_string($conn, $_POST['condit'][$i]);

                    if (empty($price) || empty($qty)) {
                        continue;
                    }

                    $sql = "INSERT INTO order_data (order_no, o_no, picb, parts_code, product_na, product_sp, requi_date, price, currency, qty, amt, curency_rate, sales_date, condit) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssssssssss", $order_no, $o_no, $picb, $parts_code, $product_na, $product_sp, $requi_date, $price, $currency, $qty, $amt, $curency_rate, $sales_date, $condit);
                    $stmt->execute();
                }

                for ($i = 0; $i < count($_POST['serial_no']); $i++) {
                    $serial_no = mysqli_real_escape_string($conn, $_POST['serial_no'][$i]);
                    $order_price = mysqli_real_escape_string($conn, str_replace(',', '', $_POST['order_price'][$i]));
                    $order_sales_rate = mysqli_real_escape_string($conn, $_POST['order_sales_rate'][$i]);
                    $order_sales_date = mysqli_real_escape_string($conn, $_POST['order_sales_date'][$i]);
                    $order_sales_remark = mysqli_real_escape_string($conn, $_POST['order_sales_remark'][$i]);
                    $sales_amt = mysqli_real_escape_string($conn, $_POST['order_price'][$i]);
                    $sales_remark = mysqli_real_escape_string($conn, $_POST['order_sales_remark'][$i]);

                    $sql_sales = "INSERT INTO sales_data (order_no, serial_no, sales_date, sales_rate, sales_amt, sales_remark) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_sales = $conn->prepare($sql_sales);
                    $stmt_sales->bind_param("ssssss", $order_no, $serial_no, $order_sales_date, $order_sales_rate, $order_price, $order_sales_remark);
                    $stmt_sales->execute();
                }
                $conn->commit(); // 모든 작업이 성공적으로 완료되면 커밋
                $_SESSION['message'] = '저장되었습니다';
                header("Location: order_index.php?order_no=$order_no&status=saved");
            } catch (Exception $e) {
                $conn->rollback(); // 오류 발생 시 롤백
                $_SESSION['message'] = '저장에 문제가 있습니다. 관리자에게 연락하십시오: ' . $e->getMessage();
                header("Location: order_index.php?order_no=$order_no&status=error&message=" . urlencode($e->getMessage()));
            }
            exit();
            break;
    }
}
?>