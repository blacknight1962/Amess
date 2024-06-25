<?php

include('include/header.php');
include('../../db.php')
?>

<!--edit data -->
<div class='"bg-success bg-opacity-20"'>
  <div class='row justify-content-center'>
    <section class="shadow-lg p-0 my-4 rounded-3 container text-center justify-content-center">
      <div class="container-fluid" style='width: 1900px, me-0, ms-0'>
        <div class="card bg-secondary bg-opacity-10">
          <h4 class="fs-5" style="width:1900px" id="insertdata">발주 정보 편집</h4>
          <div class='card-body' style='width: 1900px'>
            <table class="table table-bordered mt-2 me-0 table-hover" style='font-size: .65rem'>
              <thead class="table-secondary" style='background-color: gray;'>
                <tr>
                  <th scope="col">부서</th>
                  <th scope="col">발주일자</th>
                  <th scope="col">발주번호</th>
                  <th scope="col">발주사</th>
                  <th scope="col">고객사</th>

                  <th scope="col">특기사항</th>
                  <th scope="col">구분</th>
                  <th scope="col">담당자</th>
                  <th scope="col">자재코드</th>
                  <th scope="col">품명</th>

                  <th scope="col">사양</th>
                  <th scope="col">요청납기</th>
                  <th scope="col">단가</th>
                  <th scope="col">단위</th>
                  <th scope="col">수량</th>

                  <th scope="col">합 계</th>
                  <th scope="col">환율</th>
                  <th scope="col">매출일자</th>
                  <th scope="col">진행</th>
                </tr>
              </thead>
              <tbody class='table table-bordered table-striped text-center' style='font-size: .65rem, m-0, p-0'>
                <?php if (isset($_GET['id'])) {
                  $id = $_GET['id'];
                  $sql = "SELECT * FROM saleslist WHERE order_no = $id";
                  $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) > 0) {

                    foreach ($result as $row) { ?>
                      <form action='process_equip.php' method='POST'>
                        <tr class='text-center justify-content-center' style="margin: 0;">
                          <td style='m-0, p-0'><input style='width:50px' type='text' style='border: none, m-0, p-0' name='picb' value='<?php echo $row['picb'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='order_date' value='<?php echo $row['order_date'] ?>'></td>
                          <td><input style='width:100px' type='text' style='border: none' name='order_no' value='<?php echo $row['order_no'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='order_custo' value='<?php echo $row['order_custo'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='customer' value='<?php echo $row['customer'] ?>'> </td>
                          <td><input style='width:90px' type='text' style='border: none' name='specifica' value='<?php echo $row['specifica'] ?>'> </td>
                          <td><input style='width:50px' type='text' style='border: none' name='apart' value='<?php echo $row['apart'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='custo_name' value='<?php echo $row['custo_name'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='parts_code' value='<?php echo $row['parts_code'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='product_na' value='<?php echo $row['product_na'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='product_sp' value='<?php echo $row['product_sp'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='requi_date' value='<?php echo $row['requi_date'] ?>'></td>
                          <td><input style='width:60px' type='number' style='border: none' name='price' value='<?php echo $row['price'] ?>'></td>
                          <td><input style='width:50px' type='text' style='border: none' name='currency' value='<?php echo $row['currency'] ?>'></td>
                          <td><input style='width:30px' type='number' style='border: none' name='qty' value='<?php echo $row['qty'] ?>'></td>
                          <td><input style='width:80px' type='number' style='border: none' name='order_sum' value='<?php echo $row['order_sum'] ?>'></td>
                          <td><input style='width:50px' type='text' style='border: none' name='ex_rate' value='<?php echo $row['ex_rate'] ?>'></td>
                          <td><input style='width:90px' type='text' style='border: none' name='sales_date' value='<?php echo $row['sales_date'] ?>'></td>
                          <td><input style='width:50px' type='text' style='border: none' name='condt' value='<?php echo $row['condt'] ?>'></td>
                        </tr>
                      </form>
              </tbody>
              <a href="update_sales.php?id='<?php echo $row['order_no'] ?>'" class="link-primary"><i class="fa-solid fa-pen-to-square fs-6 me-1" title='save'></i></a>
              <a class='btn btn-outline-danger ms-0 me-0' style='font-size: 12px; font-weight:300;' href="order_index.php" title='취소'></a>
        <?php }
                  } else {
                    echo "데이터베이스에서 해당 데이터를 찾지 못했습니다.";
                  }
                } else {
                  echo "시스템에 문제가 있습니다. 관리자에게 신고하십시오~ㅠ";
                }
        ?>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<div class="footer">
</div>