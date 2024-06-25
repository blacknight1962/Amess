
<!-- 발주 고객명 선택 콤보 박스 -->
<?php
function createSelectCustomer($conn) {
    $stmt = $conn->prepare("SELECT * FROM customers");
    $stmt->execute();
    $result = $stmt->get_result();
    $cho_customer = '<select name="customer_na[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $cho_customer .= '<option value="' . htmlspecialchars($row['customer_na']) . '">' . htmlspecialchars($row['customer_na']) . '</option>';
    }
    $cho_customer .= '</select>';
    $stmt->close();
    return $cho_customer;
}
/*-- 고객명 선택 콤보 박스 --*/
function createSelectOrderCustomer($conn, $nameAttribute = 'customer_na') {
    $stmt = $conn->prepare("SELECT * FROM customers");
    $stmt->execute();
    $result = $stmt->get_result();
    $cho_od_customer = "<select name='{$nameAttribute}' style='font-size: .65rem; border: none;'>";
    while ($row = $result->fetch_assoc()) {
        $cho_od_customer .= '<option value="' . htmlspecialchars($row['customer_na']) . '">' . htmlspecialchars($row['customer_na']) . '</option>';
    }
    $cho_od_customer .= '</select>';
    $stmt->close();
    return $cho_od_customer;
}

// 배열이 필요한 경우
//echo createSelectOrderCustomer($conn, true);

// 배열이 필요하지 않은 경우
//echo createSelectOrderCustomer($conn, false);

// function createSelectOrderCustomer($conn, $selectedCustomers = []) {
//     $stmt = $conn->prepare("SELECT * FROM customers");
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $cho_od_customer = "<select name='customer_na[]' multiple style='font-size: .65rem; border: none;'>";

//     while ($row = $result->fetch_assoc()) {
//         $isSelected = in_array($row['customer_na'], $selectedCustomers) ? 'selected' : '';
//         $cho_od_customer .= '<option value="' . htmlspecialchars($row['customer_na']) . "' $isSelected>" . htmlspecialchars($row['customer_na']) . '</option>';
//     }
//     $cho_od_customer .= '</select>';
//     $stmt->close();
//     return $cho_od_customer;
// }

/*-- 부서 선택 콤보 박스 --*/
function createSelectPicb($conn) {
    $conn->begin_transaction();
$stmt = $conn->prepare("SELECT * FROM division");
$stmt->execute();
$result = $stmt->get_result();
$cho_picb = '<select name="picb[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $cho_picb .= '<option value="' . htmlspecialchars($row['picb']) . '">' . htmlspecialchars($row['picb']) . "</option>";
    }
    $cho_picb .= '</select>';
    $stmt->close();
    return $cho_picb;
}

/* nonePO 관리번호 */
function createSelectNoPO($conn) {
    $conn->begin_transaction();
    try {
        $currentYearMonth = date('ym');  // 현재 연도와 월 (예: 2405)
        $sql = "SELECT order_no FROM `order` WHERE order_no LIKE 'NPO-$currentYearMonth%' ORDER BY order_no DESC LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastOrderNo = $row['order_no'];
            $lastSequence = (int)substr($lastOrderNo, -3);  // 마지막 순번 추출
            $newSequence = sprintf('%03d', $lastSequence + 1);
        } else {
            $newSequence = '001';  // 현재 연도와 월에 대한 첫 번째 견적
        }
        return $NoPO_num = "NPO-" . $currentYearMonth . '-' . $newSequence;
    } catch (Exception $e) {
        // 오류 발생 시 롤백
        $conn->rollback();
        return "Error: " . $e->getMessage();
    }
}
/*-- 견적관리 구분 (개조, 부품, 설비, SW, 기타) --*/

function createSelectOptions($conn) {
    $stmt = $conn->prepare("SELECT * FROM apart");
    $stmt->execute();
    $result = $stmt->get_result();
    $choose_ap_form = '<select name="aparts[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $choose_ap_form .= '<option value="' . htmlspecialchars($row['aparts']) . '">' . htmlspecialchars($row['aparts']) . '</option>';
    }
    $choose_ap_form .= '</select>';
    $stmt->close();
    return $choose_ap_form;
}
/*-- 작업관리 구분 (설치 / 점검 / 수리 / 개조 / 이설 / SW / 기타 --*/
function createSelectTask($conn) {
    $stmt = $conn->prepare("SELECT * FROM task_aparts");
    $stmt->execute();
    $result = $stmt->get_result();
    $cho_task_aparts = '<select name="task_aparts[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $cho_task_aparts .= '<option value="' . htmlspecialchars($row['taskparts']) . '">' . htmlspecialchars($row['taskparts']) . '</option>';
    }
    $cho_task_aparts .= '</select>';
    $stmt->close();
    return $cho_task_aparts;
}

/*-- 작업관리 업데이트용 항목 (MZ Conveyer, MZ Tower, PCB Conveyer, Vision, Jig Seperator, 항목추가) --*/
function updateSelectTaskPart($conn) {
    $stmt = $conn->prepare("SELECT * FROM task_part");
    $stmt->execute();
    $result = $stmt->get_result();
    $cho_task_part = '<select name="hangmok[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $cho_task_part .= '<option value="' . htmlspecialchars($row['hangmok']) . '">' . htmlspecialchars($row['hangmok']) . '</option>';
    }
    $cho_task_part .= '</select>';
    $stmt->close();
    return $cho_task_part;
}
/*-- 모달용 항목 (MZ Conveyer, MZ Tower, PCB Conveyer, Vision, Jig Seperator, 항목추가) --*/
function createSelectTaskPart($conn, $task_part, $hangmok, $task_class, $selectedTaskPart = "") {
    $query = "SELECT $hangmok FROM $task_part";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return 'Query preparation failed: ' . htmlspecialchars($conn->error);
    }

    if (!$stmt->execute()) {
        return 'Query execution failed: ' . htmlspecialchars($stmt->error);
    }

    $result = $stmt->get_result();
    $html = '<select class="'.$task_class.'" name="'.$hangmok.'[]" onchange="handleDirectInput(this);">';
    $html .= '<option value="">선택</option>';
    $html .= '<option value="direct_input"'.($selectedTaskPart == "direct_input" ? ' selected' : '').'>직접 입력</option>';

    while ($row = $result->fetch_assoc()) {
        $isSelected = ($row['hangmok'] == $selectedTaskPart) ? 'selected' : '';
        $html .= '<option value="'.htmlspecialchars($row['hangmok']).'" '.$isSelected.'>'.htmlspecialchars($row['hangmok']).'</option>';
    }
    $html .= '</select>';
    $stmt->close();
    return $html;
}
/*-- manage_stat --*/

function createSelectStatus($conn, $name = "manage_stat", $selectedValue = "") {
    $stmt = $conn->prepare("SELECT * FROM manage_stat");
    $stmt->execute();
    $result = $stmt->get_result();
    $cho_mana_stat = '<select name="' . $name . '[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $isSelected = ($row['manage_stat'] == $selectedValue) ? 'selected' : '';
        $cho_mana_stat .= '<option value="' . htmlspecialchars($row['manage_stat']) . '" ' . $isSelected . '>' . htmlspecialchars($row['manage_stat']) . '</option>';
    }
    $cho_mana_stat .= '</select>';
    $stmt->close();
    return $cho_mana_stat;
}
/*-- 화폐단위 콤보박스--*/
function createSelectCurrency($conn) {  
    $stmt = $conn->prepare("SELECT * FROM currency");
    $stmt->execute();
    $result = $stmt->get_result();
    $cho_currency = '<select name="currency[]" style="font-size: .65rem; border: none;">';
    while ($row = $result->fetch_assoc()) {
        $cho_currency .= '<option value="' . htmlspecialchars($row['currency']) . '">' . htmlspecialchars($row['currency']) . '</option>';
    }
    $cho_currency .= '</select>';
    $stmt->close();
    return $cho_currency;
}
// 기간 선택 버튼
function createPeriodButton($buttonText, $onClickFunction) {
    return '<button type="button" id="'.$buttonText.'Btn" class="btn btn-outline-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="'.$onClickFunction.'">'.$buttonText.'</button>';
}
// 연도 선택 드롭다운
function createYearSelect($currentYear, $pastYears = 10) {
    $yearOption = '';
    for ($year = $currentYear; $year >= $currentYear - $pastYears; $year--) {
        $yearOption .= '<option value="' . $year . '">' . $year . '</option>';
    }
    return $yearOption;
}
// 검색 입력 창
function createSearchInput($placeholder = 'Search....') {
    return '<input type="text" class="form-control form-control-sm me-2" style="font-size: .65rem; width: 30%;" name="searchQuery" id="getWords" autocomplete="off" placeholder="' . $placeholder . '">';
};



/*-- 진행상황을 콤보박스에서 선택 (진행, 대기, 변경, 
    취소, 기타) --*/
$sql = "SELECT * FROM progress";
$result = mysqli_query($conn, $sql);
$choose_st_form = '<select name="progress">';
while ($row = mysqli_fetch_array($result)) {
    $choose_st_form .= '<option value="' . $row['step_no'] . '">' . $row['step'] . "</option>";
}
$choose_st_form .= '</select>';

/*-- 장비 등록시 일련번호 생성 --*/

$sql = "SELECT * FROM equipment ORDER BY e_no DESC limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$filtered = array('no' => htmlspecialchars($row['e_no'] + 1));

$equip_num = $filtered['no'];

/*-- 신규 고객 등록시 일련번호 생성 --*/

$sql = "SELECT * FROM customers ORDER BY custo_id DESC limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$filtered = array('no' => htmlspecialchars($row['custo_id'] + 1));

$cus_num = $filtered['no'];


/*-- 작업코드 등록시 일련번호 생성 --*/

$sql = "SELECT * FROM jobcode ORDER BY seri_no DESC limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$filtered = array('seri_no' => htmlspecialchars($row['seri_no'] + 1));

$jobcode_num = $filtered['seri_no'];
?>

