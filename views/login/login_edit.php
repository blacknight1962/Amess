<?php
include(__DIR__ . '/../../db.php');
include("include/header.php")
?>
<title>AMESS 사용자 등록</title>

<body class='bg-primary bg-opacity-10'>
  <div class='container mt-1'>
    <?php if (isset($_GET['msg'])) {
      $msg = $_GET['msg'];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    } ?>

    <div class='row justify-content-center'>
      <div class='col-md-12'>
        <div class="container-fluid justify-content-center">
          <h4 class='bg-primary bg-opacity-10 mt-1 mb-1 p-2' style='text-align: center'>사용자 관리</h4>
          <section class="w-100 m-auto shadow-lg p-2 my-4 rounded-3 container text-center">
            <div>
              <a href="login_add_new.php" class="btn btn-outline-success btn-sm mb-2 justify-content-start" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">신규등록</a>
              <table class="table table-hover text-center" style='font-size: .75rem'>
                <thead class="table-secondary" style='background-color: gray;'>
                  <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">부서</th>
                    <th scope="col">성 명</th>
                    <th scope="col">직위</th>

                    <th scope="col">성별</th>
                    <th scope="col">연락처</th>
                    <th scope="col">주소</th>


                    <th scope="col">권한</th>
                    <th scope="col">Edit</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM employee";
                  $result = mysqli_query($conn, $sql);

                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                      $filtered = array(
                        'ep_id' => htmlspecialchars($row["ep_id"]),
                        'division' => htmlspecialchars($row["division"]),
                        'ep_name' => htmlspecialchars($row["ep_name"]),
                        'J_level' => htmlspecialchars($row["J_level"]),

                        'gender' => htmlspecialchars($row["gender"]),
                        'Mphone' => htmlspecialchars($row["Mphone"]),
                        'ep_addr' => htmlspecialchars($row["ep_addr"]),
                        'acc_right' => htmlspecialchars($row["acc_right"])
                      );
                  ?>
                      <tr>
                        <td><?php echo $filtered["ep_id"] ?></td>
                        <td><?php echo $filtered["division"] ?></td>
                        <td><?php echo $filtered["ep_name"] ?></td>
                        <td><?php echo $filtered["J_level"] ?></td>

                        <td><?php echo $filtered["gender"] ?></td>
                        <td><?php echo $filtered["Mphone"] ?></td>
                        <td><?php echo $filtered["ep_addr"] ?></td>
                        <td><?php echo $filtered["acc_right"] ?></td>
                        <td>
                          <a href="login_update.php?id=<?php echo $filtered["ep_id"] ?>" class="link-outline-primary"><i class="fa-solid fa-pen-to-square fs-6 me-1 ms-2"></i></a>
                          <button class="btn btn-outline-secondary btn-sm" style='border: none;' ><i class="fa-solid fa-trash fs-6" style="padding: 0px 2px;"></i></button>
                        </td>
                      </tr>
                    <?php
                    }
                  } else {
                    ?>
                    <tr colspan="4">No More Data</tr>
                  <?php
                  } ?>
                </tbody>
              </table>
            </div>

        </div>
      </div>
    </div>
  </div>
</body>
<?php
include("include/footer.php");
?>
<!-- delete warning Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="#delete_e">!~ Warning ~!!</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method='POST' action='login_process.php?id=<?= $filtered["ep_id"]  ?>'>
        <div class="modal-body">
          <p>정말 삭제 하시겠습니까? 삭제 후에는 복구가 어렵습니다.</p>

          <input type='hidden' name='ep_id' id='ep_id'>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">취소</button>
          <button type="submit" class="btn btn-danger" name='del_btn'>삭제</button>
        </div>
      </form>
    </div>
  </div>
</div>

</html>