<?php
include('../../db.php'); // 데이터베이스 연결

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

$query = "SELECT * FROM equipment WHERE regi_date BETWEEN '$startDate' AND '$endDate' ORDER BY e_no DESC";
$result = mysqli_query($conn, $query);

$output = '';

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
              $filtered = array(
                'no' => htmlspecialchars($row['e_no']),
                'picb' => htmlspecialchars($row['picb']),
                'equip' => htmlspecialchars($row['equip']),
                'model_p' => htmlspecialchars($row['model_p']),
                'regi_date' => htmlspecialchars($row['regi_date']),
                'customer' => htmlspecialchars($row['customer']),
                'supplyer' => htmlspecialchars($row['supplyer']),
                'process_p' => htmlspecialchars($row['process_p']),
                'specif' => htmlspecialchars($row['specif'])
              );
                  ?>
                      <tr class="table table-hover">
                        <td><?= $filtered['no'] ?></td>
                        <td><?= $filtered['picb'] ?></td>
                        <td><?= $filtered['equip'] ?></td>
                        <td><?= $filtered["model_p"] ?></td>
                        <td><?= $filtered["regi_date"] ?></td>
                        <td><?= $filtered['customer'] ?></td>
                        <td><?= $filtered['supplyer'] ?></td>
                        <td><?= $filtered["process_p"] ?></td>
                        <td><?= $filtered['specif'] ?></td>
                        <td><a href="edit_equip.php?id='<?= $filtered['no'] ?>'" class="link-primary"><i class="fa-solid fa-pen-to-square fs-6 me-3"></i></a>
                        <a href="javascript:void(0)" onClick="confirmter('<?= $filtered['no'] ?>')" class="link-secondary"><i class="fa-solid fa-trash fs-6"></i></a>
                        </td>
                      </tr>
<?php            }
          } else {
    $output = '<tr style="font-size: 0.65rem;"><td colspan="15" class="text-center">선택한 기간 동안 데이터가 없습니다.</td></tr>';
}

echo $output;
?>