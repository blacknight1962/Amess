<?php
ob_start();
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include(__DIR__ . '/../../db.php');

// var_dump($_POST);
// exit();
$action = $_POST['action'] ?? 'none';  //'none'이 기본값
// print_r($action);

if ($action == 'save') {
    
    $e_no = isset($_POST['e_no']) ? mysqli_real_escape_string($conn, $_POST['e_no']) : null;


    if (isset($_POST['sub_no']) && is_array($_POST['sub_no'])) {
        $conn->begin_transaction();
        try {
            foreach ($_POST['sub_no'] as $i => $sub_no) {
                $sub_no = mysqli_real_escape_string($conn, $sub_no);
                $seri_no = mysqli_real_escape_string($conn, $_POST['seri_no'][$i]);
                $date_supply = mysqli_real_escape_string($conn, $_POST['date_supply'][$i]);
                $place_fac = mysqli_real_escape_string($conn, $_POST['place_fac'][$i]);
                $line_no = mysqli_real_escape_string($conn, $_POST['line_no'][$i]);
                $custo_nick = mysqli_real_escape_string($conn, $_POST['custo_nick'][$i]);
                $sw_ver = mysqli_real_escape_string($conn, $_POST['sw_ver'][$i]);
                $manage_stat = mysqli_real_escape_string($conn, $_POST['manage_stat'][$i]);
                $specif = mysqli_real_escape_string($conn, $_POST['specif'][$i]);
            
                // 데이터 존재 여부 확인
                $checkQuery = "SELECT COUNT(*) FROM facility WHERE sub_no = ? AND e_no = ?";
                $stmt = $conn->prepare($checkQuery);
                $stmt->bind_param("ss", $sub_no, $e_no);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();

                if ($count > 0) {
                    // Update existing record
                    $updateQuery = "UPDATE facility SET e_no = ?, seri_no = ?, date_supply = ?, place_fac = ?, line_no = ?, custo_nick = ?, sw_ver = ?, manage_stat = ?, specif = ? WHERE sub_no = ? AND e_no = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("sssssssssss", $e_no, $seri_no, $date_supply, $place_fac, $line_no, $custo_nick, $sw_ver, $manage_stat, $specif, $sub_no, $e_no);
                    $updateStmt->execute();
                    $updateStmt->close();
                } else {
                    // Insert new record
                    $insertQuery = "INSERT INTO facility (e_no, sub_no, seri_no, date_supply, place_fac, line_no, custo_nick, sw_ver, manage_stat, specif) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    $insertStmt->bind_param("ssssssssss", $e_no, $sub_no, $seri_no, $date_supply, $place_fac, $line_no, $custo_nick, $sw_ver, $manage_stat, $specif);
                    $insertStmt->execute();
                    $insertStmt->close();
                }
            }
            $conn->commit();
            $_SESSION['message'] = '저장이 완료되었습니다.';
            header("Location: facility_index.php?id=$e_no&status=saved");
            } catch (mysqli_sql_exception $exception) {
                $conn->rollback();
                echo "Error occurred: " . $exception->getMessage();
            }
            } else {
            $_SESSION['message'] = '저장이 완료되었습니다.';
            header("Location: facility_index.php");
            }
} elseif ($action == 'delete') {
    $e_no = $_POST['e_no'];

    $sub_no = $_POST['sub_no'];

    $query = "DELETE FROM facility WHERE e_no = ? AND sub_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $e_no, $sub_no);
    $executeResult = $stmt->execute();

    if ($executeResult) {
        http_response_code(200);
        $response = ['success' => true, 'message' => 'Row deleted successfully'];
    } else {
        http_response_code(500);
        $response = ['success' => false, 'message' => 'Failed to delete row'];
    }

    echo json_encode($response);
    $stmt->close();
    $conn->close();
}

