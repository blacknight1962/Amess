<?php
include(__DIR__ . '/../../db.php');
include('search_modules_order.php'); // 발주 검색 모듈 포함

// POST 데이터 출력 (디버깅용)
error_log("POST 데이터: " . print_r($_POST, true));

$period = isset($_POST['period']) ? mysqli_real_escape_string($conn, $_POST['period']) : '';
$year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : '';
$input = isset($_POST['input']) ? mysqli_real_escape_string($conn, $_POST['input']) : '';

error_log("Period: $period, Year: $year, Input: $input");

$tables = [
    '`order` o',
    'LEFT JOIN `order_data` od ON o.order_no = od.order_no'
];

$result = performOrderSearch($conn, $tables, 'o.order_date', $period, $year, $input);

if (!$result) {
    error_log("SQL 에러: " . mysqli_error($conn));
}

$totalRows = mysqli_num_rows($result);

if ($totalRows > 0) { ?>
<table class="table table-striped table-bordered table-hover mt-2" style='font-size: .75rem'>
  <thead>
    <tr>
      <th style="width: 2%;">#</th>
      <th style="width: 2%;">번호</th>
      <th style="width: 3%;">부서</th>
      <th style="width: 7%;">발주일자</th>
      <th style="width: 6%;">발주번호</th>
      <th style="width: 5%;">발주사</th>
      <th style="width: 5%;">고객사</th>
      <th style="width: 3%;">특기</th>
      <th style="width: 4%;">구분</th>
      <th style="width: 5%;">담당자</th>
      <th style="width: 7%;">자재코드</th>
      <th style="width: 9%;">품명</th>
      <th style="width: 8%;">사양</th>
      <th style="width: 5%;">요청납기</th>
      <th style="width: 4%;">단가</th>
      <th style="width: 3%;">단위</th>
      <th style="width: 3%;">수량</th>
      <th style="width: 6%;">합 계</th>
      <th style="width: 3%;">환율</th>
      <th style="width: 5%;">매출일자</th>
      <th style="width: 5%;">진행</th>
    </tr>
  </thead>
  <tbody style="font-size: .75rem;">
    <?php
    while ($row = mysqli_fetch_array($result)) {
      $filtered = array(
        'o_no' => htmlspecialchars($row['o_no'] ?? ''),
        'picb' => htmlspecialchars($row['picb'] ?? ''),
        'order_date' => htmlspecialchars($row['order_date'] ?? ''),
        'order_no' => htmlspecialchars($row['order_no'] ?? ''),
        'order_custo' => htmlspecialchars($row['order_custo'] ?? ''),
        'customer' => htmlspecialchars($row['customer'] ?? ''),
        'specifi' => htmlspecialchars($row['specifi'] ?? ''),
        'aparts' => htmlspecialchars($row['aparts'] ?? ''),
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
        'sales_date' => htmlspecialchars($row['sales_date'] ?? ''),
        'condit' => htmlspecialchars($row['condit'] ?? ''),
      );
    ?>
    <tr class="table table-hover">
      <td class="center-checkbox"><input type="checkbox" class="row-checkbox" value="<?= $row['order_no'] ?>"></td>
      <td><?= $filtered['o_no'] ?></td>
      <td><?= $filtered['picb'] ?></td>
      <td><?= $filtered['order_date'] ?></td>
      <td><?= $filtered['order_no'] ?></td>
      <td><?= $filtered['order_custo'] ?></td>
      <td><?= $filtered['customer'] ?></td>
      <td><?= $filtered['specifi'] ?></td>
      <td><?= $filtered['aparts'] ?></td>
      <td><?= $filtered['custo_name'] ?></td>
      <td><?= $filtered['parts_code'] ?></td>
      <td><?= $filtered['product_na'] ?></td>
      <td><?= $filtered['product_sp'] ?></td>
      <td><?= $filtered['requi_date'] ?></td>
      <td style="text-align: right;"><?= number_format((float)$filtered['price']) ?></td>
      <td><?= $filtered['currency'] ?></td>
      <td><?= $filtered['qty'] ?></td>
      <td style="text-align: right;"><?= number_format((float)$filtered['amt']) ?></td>
      <td><?= $filtered['curency_rate'] ?></td>
      <td><?= $filtered['sales_date'] ?></td>
      <td><?= $filtered['condit'] ?></td>              
        </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-6">
            <p>총 검색 결과: <?= $totalRows ?>건</p>
        </div>
        <div class="col-md-6">

        </div>
    </div>
<?php 
} else {
    echo "<p>검색 결과가 없습니다.</p>";
}
?>