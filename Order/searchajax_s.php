<?php
include('../db.php');

if (isset($_POST['input'])) {
$input = mysqli_real_escape_string($conn, $_POST['input']);

$sql = "SELECT o.*, od.*, s.* FROM `order` o 
INNER JOIN order_data od ON o.order_no = od.order_no 
INNER JOIN sales_data s ON od.order_no = s.order_no ";
if (!empty($input)) {
  $sql .= " WHERE o.order_no LIKE '%$input%' OR
            od.picb LIKE '%$input%' OR
            o.order_custo LIKE '%$input%' OR
            o.custo_name LIKE '%$input%' OR
            od.parts_code LIKE '%$input%' OR
            od.product_na LIKE '%$input%' OR
            od.product_sp LIKE '%$input%' OR
            od.condit LIKE '%$input%'";
}
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) { ?>
<table class="table table-bordered mt-2 table-xl  table-hover" style='font-size: .65rem'>
            <thead class="table-secondary" style='background-color: gray;'>
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
                  'amt' => htmlspecialchars($row['amt'])
                );
                $totalAmt += $filtered['amt'];
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
                <td style="text-align: right;"><?= number_format($filtered['price']) ?></td>
                <td style="text-align: center;"><?= $filtered['currency'] ?></td>
                <td style="text-align: center;"><?= $filtered['qty'] ?></td>
                <td style="text-align: right;"><?= number_format($filtered['amt']) ?></td>

                <td style="text-align: center;"><?= $filtered['curency_rate'] ?></td>
                <td style="text-align: center;"><?= $filtered['condit'] ?></td>
                <td style="text-align: right;"><?= number_format($filtered['sales_amt']) ?></td>
                
              </tr>
      <?php } 
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

    <?php   }
    }?>


