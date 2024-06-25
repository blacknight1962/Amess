<?php
include('../db.php'); // 데이터베이스 연결
include('style_order.css');


$period = $_GET['period'];
$currentYear = date("Y");

switch ($period) {
  case '1year':
    $startDate = date('Y-m-d', strtotime('-1 year'));
    $endDate = date('Y-m-d'); // 오늘 날짜로 설정
    break;
  case '3years':
    $startDate = date('Y-m-d', strtotime('-3 years'));
    $endDate = date('Y-m-d'); // 오늘 날짜로 설정
    break;
  default:
    $startDate = $period . '-01-01'; // 선택된 연도의 시작
    $endDate = $period . '-12-31'; // 선택된 연도의 끝
    break;
}

$query = "SELECT o.*, od.* FROM `order` o JOIN order_data od ON o.order_no = od.order_no WHERE o.order_date BETWEEN '$startDate' AND '$endDate' ORDER BY o.order_no DESC";
$result = mysqli_query($conn, $query);

$output = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr class="table table-hover" style="font-size: 0.65rem; width: 100%;">' .
                  '<td style="width: 3%;"><input type="checkbox" class="row-checkbox" value="' . htmlspecialchars($row['order_no']) . '"></td>' .
                  '<td style="width: 3%;">' . htmlspecialchars($row['o_no']) . '</td>' .
                  '<td style="width: 3%;">' . htmlspecialchars($row['picb']) . '</td>' .
                  '<td style="width: 5%;">' . htmlspecialchars($row['order_date']) . '</td>' .
                  '<td style="width: 6%;">' . htmlspecialchars($row['order_no']) . '</td>' .
                  '<td style="width: 5%;">' . htmlspecialchars($row['order_custo']) . '</td>' .

                  '<td style="width: 5%;">' . htmlspecialchars($row['customer']) . '</td>' .
                  '<td style="width: 3%;">' . htmlspecialchars($row['specifi']) . '</td>' .
                  '<td style="width: 4%;">' . htmlspecialchars($row['aparts']) . '</td>' .
                  '<td style="width: 5%;">' . htmlspecialchars($row['custo_name']) . '</td>' .
                  '<td style="width: 7%;">' . htmlspecialchars($row['parts_code']) . '</td>' .

                  '<td style="width: 9%;">' . htmlspecialchars($row['product_na']) . '</td>' .
                  '<td style="width: 8%;">' . htmlspecialchars($row['product_sp']) . '</td>' .
                  '<td style="width: 5%;">' . htmlspecialchars($row['requi_date']) . '</td>' .
                  '<td style="width: 4%; class="right-align">' . number_format($row['price']) . '</td>' .
                  '<td style="width: 5%;">' . htmlspecialchars($row['currency']) . '</td>' .

                  '<td style="width: 3%;" class="right-align">' . number_format($row['qty']) . '</td>' .
                  '<td style="width: 6%;" class="right-align">' . number_format($row['amt']) . '</td>' .
                  '<td style="width: 3%;">' . htmlspecialchars($row['curency_rate']) . '</td>' .
                  '<td style="width: 5%;">' . htmlspecialchars($row['sales_date']) . '</td>' .
                  '<td style="width: 3%;">' . htmlspecialchars($row['condit']) . '</td>' .
                  '</tr>';
  }
} else {
    $output = '<tr style="font-size: 0.65rem;"><td colspan="15" class="text-center">선택한 기간 동안 데이터가 없습니다.</td></tr>';
}

echo $output;
?>