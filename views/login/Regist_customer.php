<?php
include('include/header.php');
include(__DIR__ . '/../../public/Selection_kit.php');
include(__DIR__ . '/../../db.php');
?>

<!--insert customer's data by Modal -->
<div class="modal fade" id="insertdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="insertdata" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="insertdata">신규고객 등록</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action='process_custo.php' method="POST">
        <div class="modal-body">

          <div class='form-group mb-1'>
            <label for="" name='custo_id' id='custo_id'>No : </label>
            <?php echo $cus_num ?>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='customer_na' placeholder="고객명">
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

          <div class='form-group mb-2'>
            <label for="" name='regi-date'>등록일자 :</label>
            <?php echo date('Y-m-d') ?>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='specification' placeholder="특기사항">
            <label for="floatingInput">특기사항</label>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name='save_data' class="btn btn-primary">SAVE</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class='container mt-1'>
  <div class='row justify-content-center'>
    <div class='col-md-12'>
      <?php
      if (isset($_SESSION['status']) && $_SESSION['status'] != "") {
      ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Wow~!!!</strong><?php echo $_SESSION['status']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php
        unset($_SESSION['status']);
      } ?>
      <!-- regist_customer main screen -->
      <div class="bg-success bg-opacity-10">
        <h4 class="bg-primary bg-opacity-10 mb-2 p-2" style='text-align: center'>고객관리</h4>
        <section class="w-100 m-auto shadow-lg p-2 my-4 rounded-3 container text-center justify-content-center ms-0" style=' width:1280px'>
          <div class='container-fluid'>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mt-3 mb-2 float-end" name="submit" style="--bs-btn-padding-y: .4rem; --bs-btn-padding-x: .15rem; --bs-btn-font-size: .65rem;" data-bs-toggle="modal" data-bs-target="#insertdata">
              신규고객 등록
            </button>
            <div class='card-body'>
              <table class="table table-striped table-bordered mt-3 table-sm" style='font-size: .65rem'>
                <thead>
                  <tr>
                    <th scope="col">관리번호</th>
                    <th scope="col">고객명</th>
                    <th scope="col">고객타입</th>
                    <th scope="col">등록일자</th>
                    <th scope="col">비고</th>
                    <th scope="col">편집</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM customers LEFT JOIN customer_type ON customers.custo_type=customer_type.type_cu";
                  $result = mysqli_query($conn, $sql);

                  while ($row = mysqli_fetch_array($result)) {
                    $filtered = array(
                      'custo_id' => htmlspecialchars($row['custo_id']),
                      'custo_name' => htmlspecialchars($row['customer_na']),
                      'type_name' => htmlspecialchars($row['type_name']),
                      'regi_date' => htmlspecialchars($row['regi_date']),
                      'specification' => htmlspecialchars($row['specification'])
                    );
                  ?>
                    <tr>
                      <td><?= $filtered['custo_id'] ?></td>
                      <td><?= $filtered['custo_name'] ?></td>
                      <td><?= $filtered['type_name'] ?></td>
                      <td><?= $filtered['regi_date'] ?></td>
                      <td><?= $filtered['specification'] ?></td>
                      <td>
                        <a href="regi_cus_update.php?id=<?php echo $row["custo_id"] ?>" class="link-primary"><i class="fa-solid fa-pen-to-square fs-6 me-3"></i></a>
                        <a href="javascript:void()" onClick="confirmter(<?php echo $row['custo_id'] ?>)" class="link-secondary"><i class="fa-solid fa-trash fs-6"></i></a>
                      </td>
                    </tr>
                  <?php
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
</body>
<!-- delete warning message -->
<script type="text/javascript">
  function confirmter(custo_id) {
    surer = confirm("정말로 삭제 하시겠습니까?")
    if (surer) {
      document.location.href = "process_custo.php?id=<?php echo $row["custo_id"] ?>";
      return true;
    } else {
      return false;
    }
  }
</script>

<?php include('include/footer.php'); ?>