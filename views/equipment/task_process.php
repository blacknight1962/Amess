<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include(__DIR__ . '/../../db.php');


ob_start(); // 출력 버퍼링 시작

// var_dump($_POST);
// exit;   
// 액션 타입을 확인
$action = $_POST['action'] ?? 'none';

// 작업 추가 화면에서 작업 저장
// if (empty($_POST['seri_no'])) {
//     echo json_encode(['error' => 'seri_no cannot be null']);
//     exit;
// }

// 액션에 따라 처리
switch ($action) {
    case 'saveTask':
        handleSave($conn);
        break;
    case 'delete':
        handleDelete($conn);
        break;
    default:
        $_SESSION['message'] = 'No valid action provided';
        header('Location: task_manage.php');
        exit();
}

// 작업 저장 함수
function handleSave($conn) {
    $seri_no = mysqli_real_escape_string($conn, $_POST['seri_no']);
    $hangmok = is_array($_POST['hangmok']) ? mysqli_real_escape_string($conn, implode(',', $_POST['hangmok'])) : mysqli_real_escape_string($conn, $_POST['hangmok']);

    error_log("handleSave: 시작");

    if (isset($_POST['t_no']) && is_array($_POST['t_no'])) {
        $conn->begin_transaction();
        try {
            processTasks($conn, $seri_no);
            $conn->commit();
            $_SESSION['message'] = '저장이 완료되었습니다.';
            $_SESSION['seri_no'] = $seri_no; // 세션에 seri_no 저장
            error_log("handleSave: 저장 성공, 리다이렉트 준비");
            ob_end_clean(); // 출력 버퍼 비우기
            header('Location: task_index.php');
            exit();
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $_SESSION['error'] = 'Error occurred: ' . $exception->getMessage();
            error_log("handleSave: 예외 발생 - " . $exception->getMessage());
            header('Location: task_index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Task numbers are missing or invalid';
        error_log("handleSave: Task numbers are missing or invalid");
        header('Location: task_manage.php');
        exit();
    }
}

// 작업 삭제 함수
function handleDelete($conn) {
    $t_no = $_POST['t_no'];
    $seri_no = $_POST['seri_no'];
    error_log("Deleting task with t_no: $t_no and seri_no: $seri_no");

    $query = "DELETE FROM task_manage WHERE t_no = ? AND seri_no = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => "Prepare failed: (" . $conn->errno . ") " . $conn->error]);
        return;
    }
    $stmt->bind_param("ss", $t_no, $seri_no);
    $executeResult = $stmt->execute();
    if ($executeResult) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Row deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete row']);
    }
    $stmt->close();
}

// 작업 처리 함수
function processTasks($conn, $seri_no) {
    error_log("processTasks: 시작");
    foreach ($_POST['t_no'] as $i => $t_no) {
        $t_no = is_array($t_no) ? $t_no[0] : $t_no;
        $t_no = mysqli_real_escape_string($conn, $t_no);

        $date_task = is_array($_POST['date_task'][$i]) ? $_POST['date_task'][$i][0] : $_POST['date_task'][$i];
        $date_task = mysqli_real_escape_string($conn, $date_task);

        $task_person = is_array($_POST['task_person'][$i]) ? $_POST['task_person'][$i][0] : $_POST['task_person'][$i];
        $task_person = mysqli_real_escape_string($conn, $task_person);

        $task_aparts = is_array($_POST['task_aparts'][$i]) ? $_POST['task_aparts'][$i][0] : $_POST['task_aparts'][$i];
        $task_aparts = mysqli_real_escape_string($conn, $task_aparts);

        $hangmok = is_array($_POST['hangmok'][$i]) ? $_POST['hangmok'][$i][0] : $_POST['hangmok'][$i];
        $hangmok = mysqli_real_escape_string($conn, $hangmok);

        $task_title = is_array($_POST['task_title'][$i]) ? $_POST['task_title'][$i][0] : $_POST['task_title'][$i];
        $task_title = mysqli_real_escape_string($conn, $task_title);

        $task_content = is_array($_POST['task_content'][$i]) ? $_POST['task_content'][$i][0] : $_POST['task_content'][$i];
        $task_content = mysqli_real_escape_string($conn, $task_content);

        $specification = is_array($_POST['specification'][$i]) ? $_POST['specification'][$i][0] : $_POST['specification'][$i];
        $specification = mysqli_real_escape_string($conn, $specification);

        $manage_stat = is_array($_POST['manage_stat'][$i]) ? $_POST['manage_stat'][$i][0] : $_POST['manage_stat'][$i];
        $manage_stat = mysqli_real_escape_string($conn, $manage_stat);

        $exists = checkTaskExists($conn, $t_no, $seri_no);
        if ($exists) {
            error_log("processTasks: 업데이트 시작 - t_no: $t_no, seri_no: $seri_no");
            updateTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat);
        } else {
            error_log("processTasks: 삽입 시작 - t_no: $t_no, seri_no: $seri_no");
            insertTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat);
        }
    }
    error_log("processTasks: 완료");
}

// 작업 존재 여부 확인 함수
function checkTaskExists($conn, $t_no, $seri_no) {
    $query = "SELECT COUNT(*) FROM task_manage WHERE t_no = ? AND seri_no = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        return false;
    }
    $stmt->bind_param("ss", $t_no, $seri_no);
    $stmt->execute();
    $count = 0;
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

// 작업 업데이트 함수
function updateTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat) {
    $query = "UPDATE task_manage SET date_task = ?, task_person = ?, task_aparts = ?, hangmok = ?, task_title = ?, task_content = ?, specification = ?, manage_stat = ? WHERE t_no = ? AND seri_no = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        return;
    }

    $stmt->bind_param("ssssssssss", $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat, $t_no, $seri_no);

    error_log("Executing update with parameters: date_task=$date_task, task_person=$task_person, task_aparts=$task_aparts, hangmok=$hangmok, task_title=$task_title, task_content=$task_content, specification=$specification, manage_stat=$manage_stat, t_no=$t_no, seri_no=$seri_no");

    if ($stmt->execute()) {
        error_log("Update successful for t_no: $t_no and seri_no: $seri_no");
        updateFacilityManageStat($conn, $seri_no, $manage_stat);
    } else {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
}

// 작업 삽입 함수
function insertTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat) {
    $query = "INSERT INTO task_manage (t_no, seri_no, date_task, task_person, task_aparts, hangmok, task_title, task_content, specification, manage_stat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn -> errno . ") " . $conn->error;
        return;
    }
    $stmt->bind_param("ssssssssss", $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat);
    if ($stmt->execute()) {
        updateFacilityManageStat($conn, $seri_no, $manage_stat);
    } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
}

// 시설 관리 상태 업데이트 함수
function updateFacilityManageStat($conn, $seri_no, $manage_stat) {
    $query = "UPDATE facility SET manage_stat = ? WHERE seri_no = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        return;
    }
    $stmt->bind_param("ss", $manage_stat, $seri_no);
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
}

// 스크립트의 마지막에서 $conn을 닫습니다.
$conn->close();
?>
