<?php
$pageTitle = "AMESS 생산관리";
include('include/header.php');

?>
    <!-- equipment main screen -->
<div class='"bg-success bg-opacity-10"'>
  <h4 class='bg-primary bg-opacity-10 mb-2 p-2' style='text-align: center'>영업관리 - 생산관리</h4>
  <section class="shadow-lg p-2 my-1 rounded-3 container text-center justify-content-center ms-0" style='width:14400px'>
    <div class='container'>
      <div class='card-body'>
        <table class="table table-striped table-bordered table-hover mt-3 table-xl" style='font-size: .65rem'>
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">부서</th>
              <th scope="col">생산코드</th>
              <th scope="col">생산지시</th>
              <th scope="col">발주번호</th>

              <th scope="col">발주일자</th>
              <th scope="col">발주사</th>
              <th scope="col">특기</th>
              <th scope="col">구분</th>
              <th scope="col">담당</th>

              <th scope="col">자재코드</th>
              <th scope="col">품명</th>
              <th scope="col">사양</th>
              <th scope="col">요청납기</th>
              <th scope="col">수량</th>

              <th scope="col">매출일자</th>
              <th scope="col">진행</th>
            </tr>
          </thead>
          <tbody>
              <?php
              $sql = 'SELECT o.*, od.*, s.*
              FROM `order` o
              LEFT JOIN `order_data` od ON o.order_no = od.order_no AND o.o_no = od.o_no
              LEFT JOIN `sales_data` s ON od.order_no = s.order_no AND od.o_no = s.serial_no
              ORDER BY o.order_no DESC, o.o_no ASC';
              $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
              $filtered = array(
                    'picb' => htmlspecialchars($row['picb'] ?? ''),
                    'production_code' => htmlspecialchars($row['production_code'] ??''),
                    'production_start' => htmlspecialchars($row['production_start'] ?? ''),
                    'order_no' => htmlspecialchars($row['order_no'] ?? ''),

                    'order_date' => htmlspecialchars($row['order_date'] ?? ''),
                    'order_custo' => htmlspecialchars($row['order_custo']?? ''),
                    'specifi' => htmlspecialchars($row['specifi']?? ''),
                    'aparts' => htmlspecialchars($row['aparts'] ?? ''),
                    'custo_name' => htmlspecialchars($row['custo_name'] ?? ''),

                    'parts_code' => htmlspecialchars($row['parts_code'] ?? ''),
                    'product_na' => htmlspecialchars($row['product_na'] ?? ''),
                    'product_sp' => htmlspecialchars($row['product_sp'] ?? ''),
                    'requi_date' => htmlspecialchars($row['requi_date'] ?? ''),
                    'qty' => htmlspecialchars($row['qty'] ?? ''),

                    'sales_date' => htmlspecialchars($row['sales_date'] ?? ''),
                    'condit' => htmlspecialchars($row['condit'] ?? ''),

                  );
                ?>
                    <tr>
                      <td class="center-checkbox"><input type="checkbox" class="row-checkbox" value="<?= $row['order_no'] ?>"></td>
                      <td><?= $filtered['picb'] ?></td>
                      <td><?= $filtered['production_code'] ?></td>
                      <td><?= $filtered['production_start'] ?></td>
                      <td><?= $filtered["order_no"] ?></td>

                      <td><?= $filtered["order_date"] ?></td>
                      <td><?= $filtered['order_custo'] ?></td>
                      <td><?= $filtered['specifi'] ?></td>
                      <td><?= $filtered["aparts"] ?></td>
                      <td><?= $filtered['custo_name'] ?></td>

                      <td><?= $filtered['parts_code'] ?></td>
                      <td><?= $filtered['product_na'] ?></td>
                      <td><?= $filtered["product_sp"] ?></td>
                      <td><?= $filtered['requi_date'] ?></td>
                      <td><?= $filtered['qty'] ?></td>

                      <td><?= $filtered['sales_date'] ?></td>
                      <td><?= $filtered['condit'] ?></td>
                      <td>
                        <a href="order_update.php?order_no=<?= $filtered['order_no'] ?>" class="link-primary" target="_blank">
                          <i class="fa-solid fa-pen-to-square fs-6 me-3"></i>
                        </a>
                        <a href="javascript:void()" onClick="deleteSelectedManufact(<?php echo $row['order_no'] ?? 'null' ?>)" class="link-secondary">
                          <i class="fa-solid fa-trash fs-6"></i>
                        </a>
                      </td>
                    </tr>
                  <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
      </section>
    </div>
  </div>
</div>
</div>
<script src='js/manufact.js'></script>
<?php include('include/footer.php'); ?>
