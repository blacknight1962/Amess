<?php
$pageTitle = "AMESS 매출관리";
include('include/header.php');
include(__DIR__ . '/../../db.php');
include('sales_modal.php');
$currentYear = date("Y");
?>
<body>
<div class="bg-success bg-opacity-10" style="width: 1920px;">
  <h4 class='bg-primary bg-opacity-10 mb-1 p-2' style='text-align: center'>매출 관리</h4>
    <section class="shadow-lg mt-1 my-1 rounded-3 container-fluid text-center justify-content-center ms-0" style='width:1920px'>
      <div class='container-fluid' style='width: 1900px; padding: 0 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: nowrap; margin: 2px 2px;'>
        <!-- 좌측 그룹: 연도 선택 및 검색란 -->
        <div style="display: flex; align-items: center;">
          <button type="button" id="oneYearBtn" class="btn btn-outline-primary btn-sm" style="font-size: .65rem; padding: .2rem .4rem; margin-right: 10px;">최근 1년</button>
          <button type="button" id="threeYearsBtn" class="btn btn-outline-primary btn-sm" style="font-size: .65rem; padding: .2rem .4rem; margin-right: 10px;">최근 3년</button>
          <select id="yearSelect" class="form-select form-select-sm" style="font-size: .65rem; width: auto; margin-right: 10px;">
              <option value="">선택</option>
              <?php
              $currentYear = date("Y");
              for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                  echo "<option value='$year'>$year</option>";
              }
              ?>
          </select>
          <input type='text' class='form-control form-control-sm' style="font-size: .65rem; width: 300px;" name="searchInput" id="searchInput" autocomplete="off" placeholder="Search....">
        </div>
        <!-- 우측 그룹: 매출 입력 버튼 -->
        <div style="display: flex; align-items: center;">
          <button type="button" id="salesRegisterBtn" class="btn btn-outline-primary mt-1 mb-0" style="font-size: .65rem; margin-right: 10px;">매출등록</button>
          <button type="button" class="btn btn-outline-primary mt-1 mb-0" style="--bs-btn-padding-y: .4rem; --bs-btn-padding-x: .15rem; --bs-btn-font-size: .65rem;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            매출등록(no PO)
          </button>
        </div>
      </div>
    </div>
      <div class='card-body'>
        <div id="searchResultContainer_s"></div>
          <table class="table table-bordered mt-2 table-xl  table-hover" style='font-size: .75rem; vertical-align: middle;'>
            <thead class="table-secondary" style='background-color: gray;'>
              <tr style="text-align: center;">
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
                <th style="width: 6%;">단가</th>
                <th style="width: 3%;">단위</th>
                <th style="width: 3%;">수량</th>
                <th style="width: 6%;">합계</th>

                <th style="width: 4%;">환율</th>
                <th style="width: 4%;">조건</th>
                <th style="width: 6%;">매출액</th>                  
              </tr>
            </thead>
            <?php
            $sql = 'SELECT o.*, od.*, s.*
                    FROM `order` o
                    INNER JOIN `order_data` od ON o.order_no = od.order_no
                    INNER JOIN `sales_data` s ON od.order_no = s.order_no AND od.o_no = s.serial_no
                    ORDER BY o.order_date DESC';
              $result = mysqli_query($conn, $sql);

              while ($row = mysqli_fetch_array($result)) {
                $filtered = array(
                  'picb' => htmlspecialchars($row['picb']),
                  'sales_date' => htmlspecialchars($row['sales_date']),
                  'order_no' => htmlspecialchars($row['order_no']),
                  'order_custo' => htmlspecialchars($row['order_custo']),

                  'order_date' => htmlspecialchars($row['order_date']),
                  'custo_name' => htmlspecialchars($row['custo_name']),
                  'parts_code' => htmlspecialchars($row['parts_code']),
                  'product_na' => htmlspecialchars($row['product_na']),
                  'product_sp' => htmlspecialchars($row['product_sp']),

                  'requi_date' => htmlspecialchars($row['requi_date']),
                  'price' => htmlspecialchars($row['price']),
                  'currency' => htmlspecialchars($row['currency']),
                  'qty' => htmlspecialchars($row['qty']),
                  'amt' => htmlspecialchars($row['amt']),

                  'curency_rate' => htmlspecialchars($row['curency_rate']),
                  'condit' => htmlspecialchars($row['condit']),
                  'sales_amt' => htmlspecialchars($row['sales_amt']),
                );
              ?>
            <tbody class='table table-bordered table-striped'>
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
                <td style="text-align: right;"><?= number_format($filtered['price']) ?></td>
                <td style="text-align: center;"><?= $filtered['currency'] ?></td>
                <td style="text-align: center;"><?= $filtered['qty'] ?></td>
                <td style="text-align: right;"><?= number_format($filtered['amt']) ?></td>

                <td style="text-align: center;"><?= $filtered['curency_rate'] ?></td>
                <td style="text-align: center;"><?= $filtered['condit'] ?></td>
                <td style="text-align: right;"><?= number_format($filtered['sales_amt']) ?></td>
              </tr>
      <?php } ?>
            </tbody>
          </table>
          <!-- 편집, 삭제 버튼 -->
          <div style="position: fixed; bottom: 0; width: 100%; text-align: center; padding: 10px 0; background-color: transparent;">
            <button id="sales_edit-button" style="margin-right: 10px; background-color: #007bff; color: white; border: none; padding: 6px 12px; font-size: 14px;">편집</button>
            <button id="sales_delete-button" onclick="deleteSelectedQuotes()" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; font-size: 14px;">삭제</button>
          </div>
        </div>
      </section>
    </div>
    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/sales.js"></script>
<script src="js/sales_search.js"></script>
</body>
</html>