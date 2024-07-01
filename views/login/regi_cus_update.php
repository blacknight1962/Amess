<?php
include('include/header.php');
include(__DIR__ . '/../../db.php');
?>
<!--edit data -->
<div class='"bg-success bg-opacity-20"'>
  <section class="w-75 m-auto shadow-lg p-2 my-4 rounded-3 container text-center justify-content-center">
    <div class="row justify-content-center ">

      <div class="col-sm-6 ">
        <div class="card bg-secondary">
          <div class="card header">
            <h4 class="fs-5" style="width:513px" id="insertdata">고객 정보 편집</h4>
          </div>
          <div class='card-body'>
            <?php if (isset($_GET['id'])) {
              $id = $_GET['id'];

              $sql = "SELECT * FROM customers WHERE custo_id =$id";
              $result = mysqli_query($conn, $sql);
              if (mysqli_num_rows($result) > 0) {

                foreach ($result as $row) { ?>
                  <form action="process_custo.php" method='POST'>

                    <div class="form-floating mb-1">
                      <input type='text' class='form-control' name='custo_id' id='custo_id' value='<?php echo $row['custo_id'] ?>' placeholder="No">
                      <label for="floatingInput">No</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='customer_na' value='<?php echo $row['customer_na'] ?>' placeholder="고객명">
                      <label for="floatingInput">고객명</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <select class="form-select form-select-sm" aria-label="Small select example" name='custo_type'>
                        <option selected>고객타입</option>
                        <option value="a">고객사</option>
                        <option value="b">발주사</option>
                        <option value="c">고객+발주사</option>
                        <option value="d">기타</option>
                      </select>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='regi_date' value='<?php echo $row['regi_date'] ?>' placeholder="등록일자">
                      <label for="floatingInput">등록일자</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='specification' value='<?php echo $row['specification'] ?>' placeholder="특기사항">
                      <label for="floatingInput">특기사항</label>
                    </div>
          </div>
          <div class="footer">
            <button type="submit" name='update_btn' class="btn btn-info btn-sm" btn-sm>UPDATE</button>
            <a class='btn btn-outline-danger btn-sm ms-2 me-1' href="regist_customer.php" role="button">취소</a>
          </div>
          </form>
    <?php }
              } else {
                echo "데이터베이스에서 해당 데이터를 찾지 못했습니다.";
              }
            } else {
              echo "시스템에 문제가 있습니다. 관리자에게 신고하십시오~ㅠ";
            }
    ?>
        </div>
      </div>
    </div>
  </section>
</div>