<?php
include('../../db.php');

if (isset($_POST['input'])) {
  $input = $_POST['input'];
  $period = $_POST['period'];

  $dateCondition = "";
  if ($period == '1year') {
    $dateCondition = "AND regi_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
  } elseif ($period == '3years') {
    $dateCondition = "AND regi_date >= DATE_SUB(CURDATE(), INTERVAL 3 YEAR)";
  } elseif (is_numeric($period)) {
    $dateCondition = "AND YEAR(regi_date) = $period";
  }

  $sql = "SELECT * FROM jobcode WHERE (model_p LIKE '%{$input}%' OR seri_no LIKE '%{$input}%' OR equip LIKE '%{$input}%'
  OR equip_ver LIKE '%{$input}%' OR regi_date LIKE '%{$input}%' OR pic LIKE '%{$input}%' OR jobcode_specifi LIKE '%{$input}%') $dateCondition";

  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) { ?>

<table class="table table-hover table-striped table-bordered mt-3" style='font-size: .75rem; width: 100%; margin-top: 0px !important;'>
  <thead>
    <tr>
      <th scope="col">코드그룹</th>
      <th scope="col">장비명</th>
      <th scope="col">모델명</th>
      <th scope="col">버젼</th>
      <th scope="col">등록자</th>
      <th scope="col">등록일자</th>
      <th scope="col">비고</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    while ($row = mysqli_fetch_array($result)) {
      $filtered = array(
        'seri_no' => htmlspecialchars($row['seri_no']),
        'equip' => htmlspecialchars($row['equip']),
        'model_p' => htmlspecialchars($row['model_p']),
        'equip_ver' => htmlspecialchars($row['equip_ver']),
        'pic' => htmlspecialchars($row['pic']),
        'regi_date' => htmlspecialchars($row['regi_date']),
        'jobcode_specifi' => htmlspecialchars($row['jobcode_specifi']),
      );
    ?>
      <tr>
        <td><?= $filtered['seri_no'] ?></td>
        <td><?= $filtered['equip'] ?></td>
        <td><?= $filtered['model_p'] ?></td>
        <td><?= $filtered['equip_ver'] ?></td>
        <td><?= $filtered['pic'] ?></td>
        <td><?= $filtered['regi_date'] ?></td>
        <td><?= $filtered['jobcode_specifi'] ?></td>
        <td><a href="edit_jobcode.php?id='<?= $filtered['seri_no'] ?>'" class="link-primary"><i class="fa-solid fa-pen-to-square fs-6 me-3"></i></a>
          <a href="javascript:void()" onClick="confirmter(<?php echo $row['seri_no'] ?>)" class=" link-secondary"><i class="fa-solid fa-trash fs-6"></i></a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<?php
  } else {
    echo "<h6 class='text-danger text-center mt-3'>찾을 수 없는 검색어 입니다.</h6>";
  }
}
?>