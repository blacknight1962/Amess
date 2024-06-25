<!-- inputFileName은 파일을 선택하는 부분-->
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
?>
<!DOCTYPE html>
<html lang="ko-KR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>엑셀 파일 읽기</title>

  <!-- 업로드된 파일의 임시 이름을 변수에 저장 합니다 -->
  $server_inputFileName = $_FILES['inputFileName']['tmp_name'];

</head>

<body>
  <header class="entry-header">
    <h1>엑셀 파일 선택</h1>
  </header>
  <form name="add_form_entry" id="add_form_entry" method="post" action="excel_file_read.php" enctype="multipart/form-data">
    <label for="inputFileName">파일 선택:</label>
    <input type="file" name="inputFileName" size="40">
    <input type="submit" value="확인">
  </form>
  <!-- 엑셀 파일의 확장자에 따라 Reader를 설정하는 부분-->
  <?php
  if ($file_type == 'xls') {
    $reader = new PhpOffice\PhpSpreadsheet\Reader\Xls();
  } elseif ($file_type == 'xlsx') {
    $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  } else {
    echo '처리할 수 있는 엑셀 파일이 아닙니다';
    exit;
  }
  ?>
</body>

</html>