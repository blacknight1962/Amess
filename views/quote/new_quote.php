<?php
session_start();

include('include/header.php');
include(__DIR__ . '/../../public/Selection_kit.php');
include(__DIR__ . '/../../db.php'); // 이곳에서 $conn이 정의될 것입니다.

if (!isset($_SESSION['ss_id']) or $_SESSION['ss_id'] == '') {
  echo "<script> 
  alert('로그인 후 사용가능합니다');
  self.location.href='/practice/AMESystem/views/login/login.php';
  </script>";
  exit();
}

// PHP 함수 정의
function fetchQuoteInfo($quote_no) {
    global $conn;
    // 쿼리에서 변수를 직접 삽입하는 대신 플레이스홀더 사용
$query = "SELECT q.*, s.* FROM quote q LEFT JOIN quote_data s ON q.quote_no = s.quote_no WHERE q.quote_no = $quote_no";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('쿼리 준비 실패: ' . $conn->error);
}

$stmt->bind_param("s", $quote_no);
$stmt->execute();
$result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>
<script src="/practice/AMESystem/views/quote/js/quot.js"></script>

<?php
if (isset($_GET['id'])) {
    $quote_no = $_GET['id'];
    // 데이터베이스에서 견적 정보를 조회
    $quote_info = fetchQuoteInfo($quote_no);
}
?>

<!--신규데이터 저장 후 메세지 표시-->
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
});
</script>
<!--저장실패시 입력하던 데이터 유지-->
<?php
if (isset($_GET['data'])) {
    $data = unserialize(urldecode($_GET['data']));
    // 이제 $data 배열을 사용하여 폼 필드를 채울 수 있습니다.
}?>

<?php
$quote_no = isset($_GET['quote_no']) ? $_GET['quote_no'] : null;

$quote_info = array();
if ($quote_no) {
    // 데이터베이스에서 견적번호에 해당하는 정보 조회
    $query = "SELECT * FROM quote WHERE quote_no = '$quote_no'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $quote_info = mysqli_fetch_assoc($result);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // 새 견적번호 생성 로직
// 새 견적번호 생성 로직
$currentYearMonth = date('ym');  // 현재 연도와 월 (예: 2405)
$sql = "SELECT quote_no FROM quote WHERE quote_no LIKE 'ASQ-$currentYearMonth%' ORDER BY quote_no DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($row) {
    $lastQuoteNo = $row['quote_no'];
    $lastSequence = (int)substr($lastQuoteNo, -3);  // 마지막 순번 추출
    $newSequence = sprintf('%03d', $lastSequence + 1);
} else {
    $newSequence = '001';  // 현재 연도와 월에 대한 첫 번째 견적
}

$quot_num = "ASQ-" . $currentYearMonth . '-' . $newSequence;
}
?>
  <!-- 견적 기본정보 입력 -->
<div class='basic_info_container mt-1'>
  <div class='container mt-1' style="max-width: 1520px; margin: 0 auto;">
    <div class='row justify-content-center'>
      <div class='bg-warning bg-opacity-10'>
        <h4 class='bg-primary bg-opacity-10 justify-content-center text-center mb-2 p-2'>견적관리 - 신규등록</h4>
          <section class="shadow-lg p-2 my-4 rounded-3 container text-center">
            <h6 class='mt-1'>기본정보</h6>
            <div class='container-fluid' style='width: 1420px me-1 ms-1'>
              <form action="quot1_process.php" method='post'>
                <table class='table table-bordered  mt-1' style="font-size: .75rem">
                  <thead style="font-size: .75rem;">
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
                    <tbody>
                      <tr>
                        <td><input type='text' name='quot_num' style='border:none; font-size: .75rem;' value="<?php echo isset($quote_info['quote_no']) ? $quote_info['quote_no'] : $quot_num; ?>"></td>
                        <td><input type='text' name='quot_date' style='border:none; font-size: .75rem;' value="<?php echo isset($quote_info['quote_date']) ? $quote_info['quote_date'] : date('Y-m-d'); ?>"></td>
                        <td>
                        <select class="form-select" name='customer' aria-label="" style="font-size: .75rem">
                            <option value="<?php echo isset($quote_info['customer']) ? $quote_info['customer'] : ''; ?>" selected><?php echo isset($quote_info['customer']) ? $quote_info['customer'] : '선택'; ?></option>
                            <option value="삼성전자">삼성전자</option>
                            <option value="SK 하이닉스">SK 하이닉스</option>
                            <option value="기타">기타</option>
                        </select>
                        </td>
                        <td><input type='text' name='customer_name' style='border:none; font-size: .75rem;' value="<?php echo isset($quote_info['customer_name']) ? $quote_info['customer_name'] : ''; ?>"></td>
                        <td><input type='text' name='user_name' style='border:none; font-size: .75rem;' value="<?php echo isset($quote_info['user_name']) ? $quote_info['user_name'] : $user_name; ?>"></td>
                        <td>
                        <select class="form-select" name='picb' aria-label="" style="font-size: .75rem">
                            <option value="<?php echo isset($quote_info['picb']) ? $quote_info['picb'] : ''; ?>" selected><?php echo isset($quote_info['picb']) ? $quote_info['picb'] : '선택'; ?></option>
                            <option value="1팀">1팀</option>
                            <option value="2팀">2팀</option>
                            <option value="기타">기타</option>
                        </select>
                        </td>
                        <td><button type="submit" id="saveButton" class="btn btn-outline-success" style="font-size: .65rem">저장</button></td>
                      </tr>
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
  <h6 class='mt-1' style="text-align: center;">상세정보 입력</h6>
  <section class="shadow-lg mt-1 p-2 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
    <div class='container-fluid' style='width: 1900px me-1 ms-1'>
    <form action="quot1_process.php" method='post'>
      <table class='table table-bordered mt-1' style="font-size: .75rem; width: 100%;">
        <thead style="max-width: 1920px; text-align: center;">
          <tr class='table table-secondary'>
            <th style="width: 3%;">No</th>
            <th style="width: 4%;">Group</th>
            <th style="width: 10%;">설비명</th>
            <th style="width: 10%;">모델명</th>
            <th style="width: 6%;">구분</th>

            <th style="width: 10%;">품명</th>
            <th style="width: 10%;">사양</th>
            <th style="width: 5%;">자재코드</th>
            <th style="width: 6%;">단가</th>
            <th style="width: 5%;">수량</th>

            <th style="width: 8%;">합계</th>
            <th style="width: 6%;">진행</th>
            <th style="width: 6%;">관련견적</th>
            <th style="width: 8%;">특기사항</th>
            <th style="width: 3%;"><button type="button" class="btn btn-success" style="font-size: .65rem"
          onclick="BtnAdd()">+</button></th>
        </tr>
      </thead>
      <tbody id='TBody' style="width: 1920px; font-size: .75rem;">
        <tr id='TRow'>
          <td><input type="text" class="form-control sub_no" style="border:none" name="sub_no[]" id="sub_no" value="<?php echo isset($row['sub_no']) ? $row['sub_no'] : '1'; ?>" readonly=""/></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='그룹' name='group_p[]' value="<?php echo isset($quote_info['group_p']) ? $quote_info['group_p'] : ''; ?>"></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='설비명' name='sulbi[]' value="<?php echo isset($quote_info['sulbi']) ? $quote_info['sulbi'] : ''; ?>"></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='모델명' name='model[]' value="<?php echo isset($quote_info['model']) ? $quote_info['model'] : ''; ?>"></td>
          <td><select class="form-select" name='apart[]' aria-label="" style="font-size: .75rem" value="<?php echo isset($quote_info['apart']) ? $quote_info['apart'] : ''; ?>">
            <option selected>선택</option>
            <option value="개조">개조</option>
            <option value="부품">부품</option>
            <option value="설비">설비</option>
            <option value="SW">SW</option>
            <option value="기타">기타</option>
          </select></td>

          <td><input type='text' class="form-control" style='border:none' placeholder='품명' name='product_na[]' value="<?php echo isset($quote_info['product_na']) ? $quote_info['product_na'] : ''; ?>"></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='사양' name='product_sp[]' value="<?php echo isset($quote_info['product_sp']) ? $quote_info['product_sp'] : ''; ?>"></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='자재코드' name='p_code[]' value="<?php echo isset($quote_info['p_code']) ? $quote_info['p_code'] : ''; ?>"></td>
          <td><input type='text' class="form-control" style='border:none; font-size:14px' name='price[]' onchange='Calc(this);' value="<?php echo isset($quote_info['price']) ? $quote_info['price'] : ''; ?>"></td>
          <td><input type='number' class="form-control" style='border:none; font-size:14px' name='qty[]' onchange='Calc(this);' value="<?php echo isset($quote_info['qty']) ? $quote_info['qty'] : ''; ?>"></td>

          <td><input type='text' class="form-control" style='border:none; font-size:14px' name='amt[]' value="<?php echo isset($quote_info['amt']) ? $quote_info['amt'] : ''; ?>"></td>
          <td><select class="form-select" name='progress[]' aria-label="" style="font-size: .75rem">
            <option selected>선택</option>
            <option value="진행">진행</option>
            <option value="대기">대기</option>
            <option value="변경">변경</option>
            <option value="취소">취소</option>
            <option value="기타">기타</option>
          </select></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='관련견적' name='r_quot[]' value="<?php echo isset($quote_info['r_quot']) ? $quote_info['r_quot'] : ''; ?>"></td>
          <td><input type='text' class="form-control" style='border:none' placeholder='특기사항' name='specif[]' value="<?php echo isset($quote_info['specif']) ? $quote_info['specif'] : ''; ?>"></td>
          <td><button type="button" class="btn btn-danger btn-sm" style="font-size: .65rem" onclick="BtnDel(this)">X</button></td>
        </tr> 
          <input type="hidden" name="quote_no" value="<?php echo $quote_no; ?>">       
      </tbody>
    </table>
    <div class="row">
      <div class="col-1">
        <button type="submit" class="btn btn-outline-success btn-sm" style="font-size: .65rem;">저장</button>
      </div>
      <div class='col-7'></div>
        <div class="col-3">
          <div class="input-group mb-1">
            <span class="input-group-tex">Total  </span>
            <input type="text" class="form-control text-end" style="font-size: .85rem;" id='FTotal' name='FTotal' disabled=""/>
          </div>
        </div>
      </div>
    </div>
  </form>
  </div>
              <!-- Bootstrap Bundle JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"></script>

</body>
