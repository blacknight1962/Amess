<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
include('../db.php');

ob_start(); // 출력 버퍼링 시작
// var_dump($_POST);
// exit(); 
$action = $_POST['action'] ?? 'none';

switch ($action) {
    case 'saveTask':  // 이 부분을 추가
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
//작업추가 화면에서 작업 저장
if (empty($_POST['seri_no'])) {
    echo json_encode(['error' => 'seri_no cannot be null']);
    exit;
}

$action = $_POST['action'] ?? 'none';  // 기본값 설정
echo json_encode(['action' => $action]);  // 액션 로깅

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

function handleSave($conn) {
    $seri_no = mysqli_real_escape_string($conn, $_POST['seri_no']);

    // 배열 데이터를 문자열로 처리
    $hangmok = is_array($_POST['hangmok']) ? mysqli_real_escape_string($conn, implode(',', $_POST['hangmok'])) : mysqli_real_escape_string($conn, $_POST['hangmok']);
    // var_dump($hangmok);
    // exit();
    // // 항상 addTaskPart 함수 호출
    addTaskPart($conn, $hangmok); // 새로운 task_part 저장

    if (isset($_POST['t_no']) && is_array($_POST['t_no'])) {
        // var_dump($_POST);
        // exit();
        $conn->begin_transaction();
        try {
            processTasks($conn, $seri_no);
            $conn->commit();
            $_SESSION['message'] = '저장이 완료되었습니다.';
            header('Location: task_manage.php');
            exit();
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            $_SESSION['error'] = 'Error occurred: ' . $exception->getMessage();
            header('Location: task_manage.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Task numbers are missing or invalid';
        header('Location: task_manage.php');
        exit();
    }
}

function addTaskPart($conn, $hangmok) {
    $hangmokItems = explode(',', $hangmok); // 쉼표로 분리
    foreach ($hangmokItems as $item) {
        $item = trim($item); // 공백 제거
        error_log("Processing item: " . $item); // 로깅 추가

        $checkQuery = "SELECT * FROM task_part WHERE hangmok = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $item);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = '이미 존재하는 작업 항목입니다: ' . $item;
            error_log("Item already exists: " . $item); // 로깅 추가
        } else {
            $insertQuery = "INSERT INTO task_part (hangmok) VALUES (?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("s", $item);
            if ($stmt->execute()) {
                $_SESSION['message'] = '새 작업 항목이 추가되었습니다: ' . $item;
                error_log("New item added: " . $item); // 로깅 추가
            } else {
                $_SESSION['message'] = "추가 실패: (" . $stmt->errno . ") " . $stmt->error;
                error_log("Failed to add item: " . $stmt->error); // 로깅 추가
            }
        }
        $stmt->close();
    }
}

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

// 스크립트의 마지막에서 $conn을 닫습니다.
$conn->close();


function processTasks($conn, $seri_no) {
    foreach ($_POST['t_no'] as $i => $t_no) {
        $t_no = is_array($t_no) ? $t_no[0] : $t_no; // 배열이면 첫 번째 요소 사용
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
            updateTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat);  // 업데이트 로직
        } else {
            insertTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat);  // 삽입 로직
        }
    }
}

function checkTaskExists($conn, $t_no, $seri_no) {
    $query = "SELECT COUNT(*) FROM task_manage WHERE t_no = ? AND seri_no = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        return false;
    }
    $stmt->bind_param("ss", $t_no, $seri_no);
    $stmt->execute();
    $count = 0;  // $count 변수를 초기화
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

function updateTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat) {
    $query = "UPDATE task_manage SET date_task = ?, task_person = ?, task_aparts = ?, hangmok = ?, task_title = ?, task_content = ?, specification = ?, manage_stat = ? WHERE t_no = ? AND seri_no = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        return;
    }

    $stmt->bind_param("ssssssssss", $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat, $t_no, $seri_no);

    // 로그에 바인딩된 파라미터 값 기록
    error_log("Executing update with parameters: date_task=$date_task, task_person=$task_person, task_aparts=$task_aparts, hangmok=$hangmok, task_title=$task_title, task_content=$task_content, specification=$specification, manage_stat=$manage_stat, t_no=$t_no, seri_no=$seri_no");

    if ($stmt->execute()) {
        // 로그에 성공 메시지 기록
        error_log("Update successful for t_no: $t_no and seri_no: $seri_no");
        // task_manage 테이블 업데이트 성공 후 facility 테이블 업데이트
        updateFacilityManageStat($conn, $seri_no, $manage_stat);
    } else {
        // 로그에 실패 메시지 기록
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
}

function insertTask($conn, $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat) {
    $query = "INSERT INTO task_manage (t_no, seri_no, date_task, task_person, task_aparts, hangmok, task_title, task_content, specification, manage_stat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn -> errno . ") " . $conn->error;
        return;
    }
    $stmt->bind_param("ssssssssss", $t_no, $seri_no, $date_task, $task_person, $task_aparts, $hangmok, $task_title, $task_content, $specification, $manage_stat);
    if ($stmt->execute()) {
        // task_manage 테이블 삽입 성공 후 facility 테이블 업데이트
        updateFacilityManageStat($conn, $seri_no, $manage_stat);
    } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->close();
}

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


