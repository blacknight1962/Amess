<?php
include(__DIR__ . '/../../db.php');

// POST 데이터 출력 (디버깅용)
error_log("POST 데이터: " . print_r($_POST, true));

$period = isset($_POST['period']) ? mysqli_real_escape_string($conn, $_POST['period']) : '';
$year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : '';
$input = isset($_POST['input']) ? mysqli_real_escape_string($conn, $_POST['input']) : '';

error_log("Period: $period, Year: $year, Input: $input");

$sql = 'SELECT o.*, od.*, s.*
        FROM `order` o
        LEFT JOIN `order_data` od ON o.order_no = od.order_no AND o.o_no = od.o_no
        LEFT JOIN `sales_data` s ON od.order_no = s.order_no AND od.o_no = s.serial_no';

$conditions = [];

if (!empty($period) && empty($year)) { // period가 설정되고 year가 설정되지 않은 경우
    $currentDate = date('Y-m-d');
    if ($period == '1year') {
        $startDate = date('Y-m-d', strtotime('-1 year'));
    } elseif ($period == '3years') {
        $startDate = date('Y-m-d', strtotime('-3 years'));
    } else {
        $startDate = ''; // 특정 기간을 처리하는 로직 추가 필요
    }

    if (!empty($startDate)) {
        $conditions[] = "s.sales_date BETWEEN '$startDate' AND '$currentDate'";
    }
}
if (!empty($year)) { // year가 설정된 경우
    $conditions[] = "YEAR(s.sales_date) = '$year'";
}

if (!empty($input)) { // input이 설정된 경우
    $conditions[] = "(od.product_na LIKE '%$input%' OR od.product_sp LIKE '%$input%' OR od.parts_code LIKE '%$input%')";
}

if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= ' ORDER BY s.sales_date DESC, s.serial_no ASC';

error_log("SQL 쿼리: $sql");

$result = mysqli_query($conn, $sql);

if (!$result) {
    error_log("SQL 에러: " . mysqli_error($conn));
}


    if (mysqli_num_rows($result) > 0) { ?>
        <table class="table table-bordered mt-2 table-xl table-hover" style='font-size: .75rem'>
            <thead class="table-secondary" style='background-color: gray; text-align: center;'>
                <tr>
                    <th style="width: 3%;">#</th>
                    <th style="width: 3%;">부서</th>
                    <th style="width: 5%;">매출일자</th>
                    <th style="width: 6%;">발주번호</th>
                    <th style="width: 5%;">발주사</th>
                    <th style="width: 5%;">발주일자</th>
                    <th style="width: 5%;">담당자</th>
                    <th style="width: 7%;">자재코드</th>
                    <th style="width: 12%;">품명</th>
                    <th style="width: 12%;">사양</th>
                    <th style="width: 5%;">요청납기</th>
                    <th style="width: 4%;">단가</th>
                    <th style="width: 5%;">단위</th>
                    <th style="width: 3%;">수량</th>
                    <th style="width: 6%;">합계</th>
                    <th style="width: 4%;">환율</th>
                    <th style="width: 4%;">조건</th>
                    <th style="width: 6%;">매출액</th>                  
                </tr>
            </thead>
            <tbody class='table table-bordered table-striped'>
            <?php
            $totalAmt = 0;
            while ($row = mysqli_fetch_array($result)) {
                $filtered = array(
                    'picb' => htmlspecialchars($row['picb'] ?? ''),
                    'sales_date' => htmlspecialchars($row['sales_date'] ?? ''),
                    'order_no' => htmlspecialchars($row['order_no'] ?? ''),
                    'order_custo' => htmlspecialchars($row['order_custo'] ?? ''),
                    'order_date' => htmlspecialchars($row['order_date'] ?? ''),
                    'custo_name' => htmlspecialchars($row['custo_name'] ?? ''),
                    'parts_code' => htmlspecialchars($row['parts_code'] ?? ''),
                    'product_na' => htmlspecialchars($row['product_na'] ?? ''),
                    'product_sp' => htmlspecialchars($row['product_sp'] ?? ''),
                    'requi_date' => htmlspecialchars($row['requi_date'] ?? ''),
                    'price' => htmlspecialchars($row['price'] ?? ''),
                    'currency' => htmlspecialchars($row['currency'] ?? ''),
                    'qty' => htmlspecialchars($row['qty'] ?? ''),
                    'amt' => htmlspecialchars($row['amt'] ?? ''),
                    'curency_rate' => htmlspecialchars($row['curency_rate'] ?? ''),
                    'condit' => htmlspecialchars($row['condit'] ?? ''),
                    'sales_amt' => htmlspecialchars($row['sales_amt'] ?? ''),
                    'amt' => htmlspecialchars($row['amt'] ?? '')
                );
                $totalAmt += (float)$filtered['sales_amt'];
            ?>
                <tr>
                    <td class="center-checkbox"><input type="checkbox" class="row-checkbox" value="<?= $row['order_no'] ?>"></td>
                    <td style="text-align: center;"><?= $filtered['picb'] ?></td>
                    <td style="text-align: center;"><?= $filtered['sales_date'] ?></td>
                    <td style="text-align: center;"><?= $filtered["order_no"] ?></td>
                    <td style="text-align: center;"><?= $filtered['order_custo'] ?></td>
                    <td style="text-align: center;"><?= $filtered['order_date'] ?></td>
                    <td style="text-align: center;"><?= $filtered['custo_name'] ?></td>
                    <td><?= $filtered['parts_code'] ?></td>
                    <td><?= $filtered['product_na'] ?></td>
                    <td><?= $filtered['product_sp'] ?></td>
                    <td style="text-align: center;"><?= $filtered['requi_date'] ?></td>
                    <td style="text-align: right;"><?= number_format((float)$filtered['price']) ?></td>
                    <td style="text-align: center;"><?= $filtered['currency'] ?></td>
                    <td style="text-align: center;"><?= $filtered['qty'] ?></td>
                    <td style="text-align: right;"><?= number_format((float)$filtered['amt']) ?></td>
                    <td style="text-align: center;"><?= $filtered['curency_rate'] ?></td>
                    <td style="text-align: center;"><?= $filtered['condit'] ?></td>
                    <td style="text-align: right;"><?= number_format((float)$filtered['sales_amt']) ?></td>
                </tr>
            <?php 
            } 
            ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-1">
                <button type="submit" class="btn btn-secondary" style="font-size: .65rem; margin-left: 10px;">검색 결과</button>
            </div>
            <div class='col-8'></div>
            <div class='col-2'>
                <div class="input-group mb-1">
                    <span class="input-group-tex">매출 합계</span>
                    <input type="text" class="form-control text-end" id='FTotal' name='FTotal' value='<?= number_format($totalAmt) ?>' disabled=''/>
                </div>
            </div>
        </div>
    <?php 
    } 

?>


