<?php
include('../db.php');


if (isset($_POST['input'])) {
  $input = $_POST['input'];

  $sql = "SELECT q.*, qd.* 
FROM quote q
JOIN quote_data qd ON q.quote_no = qd.quote_no
WHERE qd.quote_no LIKE '%{$input}%' OR qd.product_na LIKE '%{$input}%' OR qd.product_sp LIKE '%{$input}%'
OR qd.p_code LIKE '%{$input}%' OR qd.group_p LIKE '%{$input}%' OR qd.sulbi LIKE '%{$input}%'";

  $result = mysqli_query($conn, $sql);
  
  if (mysqli_num_rows($result) > 0) { ?>
      <table class="table table-striped table table-hover tatable-bordered mt-3 table-xl" style='font-size: .65rem'>
        <thead>
          <tr>
            <th style="width: 2%;">#</th>
            <th style="width: 3%;">부서</th>
            <th style="width: 6%;">견적번호</th>
            <th style="width: 6%;">견적일자</th>
            <th style="width: 6%;">고객명</th>

            <th style="width: 5%;">담당자</th>
            <th style="width: 5%;">작성자</th>
            <th style="width: 4%;">구분</th>
            <th style="width: 14%;">품명</th>
            <th style="width: 15%;">사양</th>

            <th style="width: 7%;">자재코드</th>
            <th style="width: 6%;">단가</th>
            <th style="width: 4%;">수량</th>
            <th style="width: 7%;">합계</th>
            <th style="width: 4%;">진행</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($row = mysqli_fetch_array($result)) {
            $filtered = array(
              'picb' => htmlspecialchars($row['picb']),
              'quote_no' => htmlspecialchars($row['quote_no']),
              'quote_date' => htmlspecialchars($row['quote_date']),
              'customer' => htmlspecialchars($row['customer']),

              'customer_name' => htmlspecialchars($row['customer_name']),
              'pic' => htmlspecialchars($row['pic']),
              'group_p' => htmlspecialchars($row['group_p']),
              'sulbi' => htmlspecialchars($row['sulbi']),
              'model' => htmlspecialchars($row['model']),

              'apart' => htmlspecialchars($row['apart']),
              'product_na' => htmlspecialchars($row['product_na']),
              'product_sp' => htmlspecialchars($row['product_sp']),
              'p_code' => htmlspecialchars($row['p_code']),
              'price' => htmlspecialchars($row['price']),

              'qty' => htmlspecialchars($row['qty']),
              'amt' => htmlspecialchars($row['amt']),
              'progress' => htmlspecialchars($row['progress'])
            );
        ?>
          <tr class="table table-hover">
            <td><input type="checkbox" class="row-checkbox" value="${row.quote_no}"></td>
            <td><?= $filtered['picb'] ?></td>
            <td><?= $filtered['quote_no'] ?></td>
            <td><?= $filtered["quote_date"] ?></td>
            <td><?= $filtered['customer'] ?></td>
            <td><?= $filtered['customer_name'] ?></td>

            <td><?= $filtered['pic'] ?></td>
            <td><?= $filtered['apart'] ?></td>
            <td class="left-align"><?= $filtered['product_na'] ?></td>
            <td class="left-align"><?= $filtered['product_sp'] ?></td>
            <td><?= $filtered['p_code'] ?></td>
            <td class="right-align"><?= number_format($filtered['price']) ?></td>
            <td class="right-align"><?= number_format($filtered['qty']) ?></td>
            <td class="right-align"><?= number_format($filtered['amt']) ?></td>
            <td><?= $filtered['progress'] ?></td>
          </tr>
    <?php }
}
}
      ?>  
      
        </tbody>
          