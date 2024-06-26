<?php
include_once '../../db.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style_equip.css" rel="stylesheet">

<!--update equipment  -->
<div class="card border-success custom-margin" style="width: 1780px; max-width: 1780px;">
  <div class="card-header bg-transparent border-success">장비 정보 편집</div>

      <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
        <div class='container-fluid' style='width: 1800px me-0 ms-0'>
          <form action='equip_process.php' method='POST'>
            <body>
              <table class='table table-bordered mt-1' style="font-size: .65rem; width: 100%;">
                <thead style="max-width: 1800px; text-align: center;">
                  <tr class='table table-secondary'>
                    <th style="width: 5%;">부서</th>
                    <th style="width: 13%;">장비명</th>
                    <th style="width: 20%;">모델명</th>
                    <th style="width: 10%;">등록일자</th>
                    <th style="width: 10%;">고객명</th>
                    <th style="width: 10%;">공급사</th>
                    <th style="width: 13%;">공정</th>
                    <th style="width: 14%;">특기사항</th>
                    <th style="width: 5%;">삭제</th>
                  </tr>
                </thead>
                <tbody id='editEquipData' data-e_no="" style="width: 1800px; font-size: .65rem !important;">   
                  <?php
                    $equipmentId = isset($_GET['id']) ? $_GET['id'] : null;
                    $error_message = '';
                    if ($equipmentId === null) {

                        $error_message = "Equipment ID is not provided or incorrect.";
                    } else {
                        // 데이터베이스 조회 등의 처리
                        $sql = "SELECT * FROM equipment WHERE e_no = ?";
                        $stmt = $conn->prepare($sql);
                      
                        $stmt->bind_param("i", $equipmentId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if (mysqli_num_rows($result) > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $filtered = array(
                              'e_no' => htmlspecialchars($row["e_no"]),
                              'picb' => htmlspecialchars($row["picb"]),
                              'equip' => htmlspecialchars($row["equip"]),
                              'model_p' => htmlspecialchars($row["model_p"]),

                              'regi_date' => htmlspecialchars($row["regi_date"]),
                              'customer' => htmlspecialchars($row["customer"]),
                              'supplyer' => htmlspecialchars($row["supplyer"]),
                              'process_p' => htmlspecialchars($row["process_p"]),
                              'specif' => htmlspecialchars($row["specif"]),
                          );
                    ?>
                  <tr data-equip-id="<?= $filtered["e_no"]; ?>" style="font-size: .65rem !important;">
                    <input type="hidden" id="modal-e_no" name="e_no" value="<?= $filtered["e_no"]; ?>">
                    <td><input type="text" class="form-control" style="text-align: center;" id="picb" name="picb" value="<?= $filtered["picb"]; ?>"></td>
                    <td><input type="text" class="form-control" id="equip" name="equip" value="<?= $filtered["equip"]; ?>"></td>
                    <td><input type="text" class="form-control" id="model_p" name="model_p" value="<?= $filtered["model_p"]; ?>"></td>

                    <td><input type="date" class="form-control" id="regi_date" name="regi_date" value="<?= $filtered["regi_date"]; ?>"></td>
                    <td><input type="text" class="form-control" id="customer" name="customer" value="<?= $filtered["customer"]; ?>"></td>
                    <td><input type="text" class="form-control" id="supplyer" name="supplyer" value="<?= $filtered["supplyer"]; ?>"></td>
                    <td><input type="text" class="form-control" id="process_p" name="process_p" value="<?= $filtered["process_p"]; ?>"></td>
                    <td><input type="text" class="form-control" id="specif" name="specif" value="<?= $filtered["specif"]; ?>"></td>

                    <td><button type="button" class="btn link-danger small-btn delete-button" data-equip-id="<?= $filtered["e_no"]; ?>">
                      <i class="fa-solid fa-trash fs-6"></i>
                    </button></td>
                    </td>
                    </tr> 
                    <?php }
                    } else {
                        $error_message = "No records found.";
                    }
                    $stmt->close();
                }
                ?>
                
              </table>
              </body>
            </div>
          <div class="card-footer bg-transparent border-success">
            <button type="submit" name='update_btn' class="btn btn-info btn-sm" style="font-size: .65rem;">UPDATE</button>
            <button type="button" class="btn btn-secondary" onclick="window.close();" style="font-size: .65rem;">취소</button>
          </div>
        </form>
      </div>
    </section>
    </div>
  </div>
</div>