<?php
session_start();

include('include/header.php');
include(__DIR__ . '/../../public/Selection_kit.php');
include(__DIR__ . '/../../db.php'); 

if (!isset($_SESSION['ss_id']) or $_SESSION['ss_id'] == '') {
  echo "<script> 
  alert('로그인 후 사용가능합니다');
  self.location.href='/practice/AMESystem/views/login/login.php';
  </script>";
  exit();
}

// 세션 메시지 확인 및 출력
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info" id="sessionMessage">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']); // 메시지 출력 후 세션에서 제거
}

$quote_no = isset($_GET['quote_no']) ? $_GET['quote_no'] : null;

$quote_info = array();
if ($quote_no) {
    // 데이터베이스에서 견적번호에 해당하는 정보 조회
    $quote_no = mysqli_real_escape_string($conn, $_GET['quote_no']);
    $sql = "SELECT * FROM quote WHERE quote_no = '$quote_no'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $quote_info = mysqli_fetch_assoc($result);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'saved') {
        alert('저장되었습니다.');
        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.textContent = '저장됨';
        }
    }

    // 성공 메시지를 일정 시간 후에 숨기기
    const sessionMessage = document.getElementById('sessionMessage');
    if (sessionMessage) {
        setTimeout(() => {
            sessionMessage.style.display = 'none';
        }, 5000); // 5초 후에 메시지 숨기기
    }
});
</script>
<!--저장실패시 입력하던 데이터 유지-->
<?php
if (isset($_GET['data'])) {
    $data = unserialize(urldecode($_GET['data']));
    // 이제 $data 배열을 사용하여 폼 필드를 채울 수 있습니다.
}
?>
  <!-- 견적 기본정보 입력 -->
<div class='basic_info_container mt-1'>
  <div class='container mt-1'>
    <div class='row justify-content-center'>
      <div class='bg-warning bg-opacity-10'>
        <h4 class='bg-primary bg-opacity-10 justify-content-center text-center mb-2 p-2'>견적관리 - UPDATE</h4>
          <section class="shadow-lg p-2 my-4 rounded-3 container text-center">
            <h6 class='mt-1'>기본정보</h6>
            <div class='container-fluid' style='width: 1500px me-1 ms-1'>
              <form action="quot1_process.php" method='post'>
                <table class='table table-bordered  mt-1 table-sm' style="font-size: .75rem">
                  <thead>
                      <tr class='table table-secondary'>
                        <th class='col-xl-2'>견적번호</th>
                        <th class='col-xl-2'>견적일자</th>
                        <th class='col-xl-2'>고 객 명</th>
                        <th class='col-xl-2'>담 당 자</th>
                        <th class='col-xl-2'>작 성 자</th>
                        <th class='col-xl-1'>부 서</th>
                        <th class='col-xl-1'></th>
                      </tr>
                    </thead>
                    <tbody style="text-align: center;">
                    <?php
                    if (isset($_GET['id'])) {
                      $quote_no = mysqli_real_escape_string($conn, $_GET['quote_no']);
                    }
                    $sql = "SELECT * FROM quote WHERE quote_no = '$quote_no'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                      $filtered = array(
                        'quote_no' => htmlspecialchars($row["quote_no"]),
                        'quote_date' => htmlspecialchars($row["quote_date"]),
                        'customer' => htmlspecialchars($row["customer"]),
                        'customer_name' => htmlspecialchars($row["customer_name"]),
                        'user_name' => htmlspecialchars($row["pic"]),
                        'picb' => htmlspecialchars($row["picb"]),
                      );
                      ?>
                      <tr>
                        <td><?php echo $filtered["quote_no"] ?></td>
                        <td><?php echo $filtered["quote_date"] ?></td>
                        <td><?php echo $filtered["customer"] ?></td>
                        <td><input type="text" class="form-control customer_name" style="font-size: .65rem; border: none; text-align: center;" value="<?php echo $filtered["customer_name"]; ?>"></td>
                        <td><?php echo $filtered["user_name"] ?></td>
                        <td><?php echo $filtered["picb"] ?></td>
                        <td><button type="submit" id="update" class="btn btn-success" style="font-size: .65rem">Update</button></td>
                      </tr>
                    <?php }
                  } else {
                    echo "0 results";
                  }
                  ?>
                  </tbody>
                </table>
              </form>
            </div>
          </section>
      </div>
    </div>
  </div>
</div>                        

<!-- 상세정보 입력 -->
<div class='bg-primary bg-opacity-10'>
  <div class='row justify-content-center' style="max-width: 1920px; margin: 0 auto;">
    <h6 class='mt-1' style="text-align: center;">상세정보</h6>
      <section class="shadow-lg mt-1 p-2 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
        <div class='container-fluid' style='width: 1900px me-1 ms-1'>
          <form action="quot1_process.php" method="post">
            <input type="hidden" name="quote_no" value="<?php echo $quote_no; ?>">
              <table class='table table-bordered mt-1' style="font-size: .75rem; width: 100%;">
                <thead style="max-width: 1920px; text-align: center;">
                  <tr class='table table-secondary'>
                    <th style="width: 3%;">No</th>
                    <th style="width: 4%;">Group</th>
                    <th style="width: 8%;">설비명</th>
                    <th style="width: 8%;">모델명</th>
                    <th style="width: 6%;">구분</th>

                    <th style="width: 12%;">품명</th>
                    <th style="width: 13%;">사양</th>
                    <th style="width: 6%;">자재코드</th>
                    <th style="width: 7%;">단가</th>
                    <th style="width: 3%;">수량</th>

                    <th style="width: 8%;">합계</th>
                    <th style="width: 6%;">진행</th>
                    <th style="width: 6%;">관련견적</th>
                    <th style="width: 7%;">특기사항</th>
                    <th style="width: 3%;"><button type="button" class="btn btn-success btn-sm" style="font-size: .65rem" onclick="BtnAdd()">+</button></th>
                  </tr>
                </thead>
                <tbody id='TBody' style="width: 1920px; font-size: .75rem;">
                  <?php
                  if (isset($_GET['quote_no'])) {
                    $quote_no = mysqli_real_escape_string($conn, $_GET['quote_no']);
                    $sql_data = "SELECT * FROM quote_data WHERE quote_no = '$quote_no'";
                    $result_data = mysqli_query($conn, $sql_data);

                    if (mysqli_num_rows($result_data) > 0) {
                      while ($row_data = mysqli_fetch_array($result_data)) {
                        $filtered = array(
                          'sub_no' => htmlspecialchars($row_data["sub_no"]),
                          'group_p' => htmlspecialchars($row_data["group_p"]),
                          'sulbi' => htmlspecialchars($row_data["sulbi"]),
                          'model' => htmlspecialchars($row_data["model"]),
                          'apart' => htmlspecialchars($row_data["apart"]),
                          'product_na' => htmlspecialchars($row_data["product_na"]),
                          'product_sp' => htmlspecialchars($row_data["product_sp"]),
                          'p_code' => htmlspecialchars($row_data["p_code"]),
                          'price' => htmlspecialchars($row_data["price"]),
                          'qty' => htmlspecialchars($row_data["qty"]),
                          'amt' => htmlspecialchars($row_data["amt"]),
                          'progress' => htmlspecialchars($row_data["progress"]),
                          'r_quot' => htmlspecialchars($row_data["r_quot"]),
                          'specif' => htmlspecialchars($row_data["specif"]),
                        );
                  ?>
                      <tr id='TRow'>
                        <td><input type="text" class="form-control sub_no" style="text-align: center; border: none;" name="sub_no[]" value="<?php echo $filtered["sub_no"]; ?>"></td>
                        <td><input type="text" class="form-control group_p" style="font-size: .75rem; border: none;" name="group_p[]" value="<?php echo $filtered["group_p"]; ?>"></td>
                        <td><input type="text" class="form-control sulbi" style="font-size: .75rem; border: none;" name="sulbi[]" value="<?php echo $filtered["sulbi"]; ?>"></td>
                        <td><input type="text" class="form-control model" style="font-size: .75rem; border: none;" name="model[]" value="<?php echo $filtered["model"]; ?>"></td>
                        <td><select name="apart[]" class="form-select" style="font-size: .75rem">
                              <option value="개조" <?php echo ($filtered['apart'] == '개조') ? 'selected' : ''; ?>>개조</option>
                              <option value="부품" <?php echo ($filtered['apart'] == '부품') ? 'selected' : ''; ?>>부품</option>
                              <option value="설비" <?php echo ($filtered['apart'] == '설비') ? 'selected' : ''; ?>>설비</option>
                              <option value="SW" <?php echo ($filtered['apart'] == 'SW') ? 'selected' : ''; ?>>SW</option>
                              <option value="기타" <?php echo ($filtered['apart'] == '기타') ? 'selected' : ''; ?>>기타</option>
                            </select></td>
                        <td><input type="text" class="form-control product_na" style="font-size: .75rem; border: none;" name="product_na[]" value="<?php echo $filtered["product_na"]; ?>"></td>
                        <td><input type="text" class="form-control product_sp" style="font-size: .75rem; border: none;" name="product_sp[]" value="<?php echo $filtered["product_sp"]; ?>"></td>
                        <td><input type="text" class="form-control p_code" style="font-size: .75rem; border: none;" name="p_code[]" value="<?php echo $filtered["p_code"]; ?>"></td>
                        <td><input type="text" class="form-control price small-input" style="text-align: right; border: none;" name="price[]" value="<?php echo number_format($filtered["price"]); ?>" oninput="updatePrice(this)"></td>
                        <td><input type="number" class="form-control qty small-input" style="text-align: right; border: none;" name="qty[]" value="<?php echo $filtered["qty"]; ?>" oninput="updateLineTotal(this)"></td>
                        <td><input type="text" class="form-control amt small-input" style="text-align: right; border: none;" name="amt[]" value="<?php echo number_format($filtered["amt"]); ?>" readonly></td>
                        <td><select name="progress[]" class="form-select" style="font-size: .75rem; border: none;">
                              <option value="진행" <?php echo ($filtered['progress'] == '진행') ? 'selected' : ''; ?>>진행</option>
                              <option value="대기" <?php echo ($filtered['progress'] == '대기') ? 'selected' : ''; ?>>대기</option>
                              <option value="변경" <?php echo ($filtered['progress'] == '변경') ? 'selected' : ''; ?>>변경</option>
                              <option value="취소" <?php echo ($filtered['progress'] == '취소') ? 'selected' : ''; ?>>취소</option>
                              <option value="기타" <?php echo ($filtered['progress'] == '기타') ? 'selected' : ''; ?>>기타</option>
                            </select></td>
                        <td><input type="text" class="form-control r_quot" style="font-size: .75rem; border: none;" name="r_quot[]" value="<?php echo $filtered["r_quot"]; ?>"></td>
                                                <td><input type="text" class="form-control specif" style="font-size: .75rem; border: none;" name="specif[]" value="<?php echo $filtered["specif"]; ?>"></td>
                        <td><button type="button" class="btn link-danger small-btn" style="font-size: .75rem" onclick="IcoDel(this)"><i class="fa-solid fa-trash fs-6"></i></button></td>
                      </tr> 
                    <?php
                      }
                    } else {
                      // quote_data 테이블에 데이터가 없는 경우 빈 입력 필드 생성
                      ?>
                      <tr id='TRow'>
                        <td><input type="text" class="form-control sub_no" style="text-align: center; border: none;" name="sub_no[]" value=""></td>
                        <td><input type="text" class="form-control group_p" style="font-size: .75rem; border: none;" name="group_p[]" value=""></td>
                        <td><input type="text" class="form-control sulbi" style="font-size: .75rem; border: none;" name="sulbi[]" value=""></td>
                        <td><input type="text" class="form-control model" style="font-size: .75rem; border: none;" name="model[]" value=""></td>
                        <td><select name="apart[]" class="form-select" style="font-size: .75rem">
                              <option value="개조">개조</option>
                              <option value="부품">부품</option>
                              <option value="설비">설비</option>
                              <option value="SW">SW</option>
                              <option value="기타">기타</option>
                            </select></td>
                        <td><input type="text" class="form-control product_na" style="font-size: .75rem; border: none;" name="product_na[]" value=""></td>
                        <td><input type="text" class="form-control product_sp" style="font-size: .75rem; border: none;" name="product_sp[]" value=""></td>
                        <td><input type="text" class="form-control p_code" style="font-size: .75rem; border: none;" name="p_code[]" value=""></td>
                        <td><input type="text" class="form-control price small-input" style="text-align: right; border: none;" name="price[]" value="" oninput="updatePrice(this)"></td>
                        <td><input type="number" class="form-control qty small-input" style="text-align: right; border: none;" name="qty[]" value="" oninput="updateLineTotal(this)"></td>
                        <td><input type="text" class="form-control amt small-input" style="text-align: right; border: none;" name="amt[]" value="" readonly></td>
                        <td><select name="progress[]" class="form-select" style="font-size: .75rem; border: none;">
                              <option value="진행">진행</option>
                              <option value="대기">대기</option>
                              <option value="변경">변경</option>
                              <option value="취소">취소</option>
                              <option value="기타">기타</option>
                            </select></td>
                        <td><input type="text" class="form-control r_quot" style="font-size: .75rem; border: none;" name="r_quot[]" value=""></td>
                        <td><input type="text" class="form-control specif" style="font-size: .75rem; border: none;" name="specif[]" value=""></td>
                        <td><button type="button" class="btn link-danger small-btn" style="font-size: .75rem" onclick="IcoDel(this)"><i class="fa-solid fa-trash fs-6"></i></button></td>
                      </tr>
                    <?php
                    }
                  } else {
                    echo "0 results";
                  }
                
              ?> 
                </tbody>
              </table>
                <div class="row">
                  <div class="col-1">
                  <button type="submit" name='update_btn' class="btn btn-outline-success" style="font-size: .65rem">UPDATE</button>
                  </div>
                    <script>
                      document.addEventListener('DOMContentLoaded', function() {
                        updateTotal();
                      });
                    </script>
                  <div class='col-7'></div>
                    <div class="col-3">
                      <div class="input-group mb-1">
                        <span class="input-group-tex">Total  </span>
                        <input type="text" class="form-control text-end" style="font-size: .85rem;" id='FTotal' name='FTotal' disabled=""/>
                      </div>
                    </div>
                </div>
          </form>
        </div>
      </section>
  </div>
</div>
  <!-- Bootstrap Bundle JS -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
  crossorigin="anonymous"></script>
<script src="/practice/AMESystem/views/quote/js/quot.js"></script>
</body>
