<?php
session_start();
include('include/header.php');
include('../../db.php')
?>

<body class='bg-primary bg-opacity-10'>
  <div class='container mt-1' style='width: 1440px;'>
    <div class='row justify-content-center'>
      <div class='col-lg-12'>
        <div class="container-fluid justify-content-center">
          <h4 class='bg-primary bg-opacity-10 mt-1 mb-1 p-2' style='text-align: center'>작업코드 -검색</h4>
          <section class="shadow-lg p-2 my-4 rounded-3 container text-center" style='width: 1440px' ;>
            <h6 class="mt-1"><b>Search Words</b></h6>
            <div class="input-group mb-1 mt-1">
              <div class="form-outline">
                <input type='text' class='form-control' id="getWords" autocomplete="off" placeholder="Search....">
              </div>
            </div>
            <div id='searchresult'></div>
            <table class="table table-bordered table-striped table-hover table-sm" style='font-size: .65rem'>
              <thead>
                <tr style="text-align: center;">
                  <th scope="col">no</th>
                  <th scope="col">코드그룹</th>
                  <th scope="col">장비명</th>
                  <th scope="col">모델명</th>
                  <th scope="col">버젼</th>

                  <th scope="col">등록자</th>
                  <th scope="col">등록일자</th>
                  <th scope="col">비고</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM jobcode ORDER BY serial_no DESC;";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_array($result)) {
                    $filtered = array(
                      's_no' => htmlspecialchars($row['serial_no']),
                      'codeg' => htmlspecialchars($row['codeg']),
                      'q_system' => nullable_htmlspecialchars($row['q_system']),
                      'model' => nullable_htmlspecialchars($row['model']),
                      'ver' => nullable_htmlspecialchars($row['eqip_ver']),

                      'pic' => nullable_htmlspecialchars($row['pic']),
                      'regist_date' => htmlspecialchars($row['reg_date']),
                      'specification' => nullable_htmlspecialchars($row['jobcode_specifi']),
                    );
                ?>
                    <tr>
                      <td><?= $filtered['s_no'] ?></td>
                      <td><?= $filtered['codeg'] ?></td>
                      <td><?= $filtered['q_system'] ?></td>
                      <td><?= $filtered['model'] ?></td>
                      <td><?= $filtered['ver'] ?></td>

                      <td><?= $filtered['pic'] ?></td>
                      <td><?= $filtered['regist_date'] ?></td>
                      <td><?= $filtered['specification'] ?></td>
                      <td><a href="edit_jobcode.php?id='<?= $filtered['codeg'] ?>'" class="link-primary"><i class="fa-solid fa-pen-to-square fs-6 me-3"></i></a>
                        <a href="javascript:void()" onClick="confirmter(<?php echo $row['codeg'] ?>)" class=" link-secondary"><i class="fa-solid fa-trash fs-6"></i></a>
                      </td>
                    </tr>
                <?php }
                } ?>
              </tbody>
            </table>
          </section>
        </div>
      </div>
    </div>
  </div>
</body>

<script type='text/javascript'>
  $(document).ready(function() {
    $("#getWords").keyup(function() {
      let input = $(this).val();
      /*alert(input);*/
      /*console.log(getwords);*/
      if (input != "") {
        $.ajax({
          method: 'POST',
          url: "searchajax_job.php",
          data: {
            input: input
          },
          success: function(response) {
            $("#searchresult").html(response);
          }
        });
      } else {
        $("searchresult").css("display", "none");
      }
    });
  });
</script>

<?php
include('include/footer.php');
?>