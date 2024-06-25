<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="amess_logo.ico" rel="shortcut icon" type="image/x-icon">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="style_jobcode.css">
  <script src="https://kit.fontawesome.com/49f96f1a0f.js" crossorigin="anonymous"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <script src="main.js" defer></script>

  <?php
  include('../db.php');
  ?>

  <title>AMESS-작업코드 검색</title>

  <!-- 코드그룹 번호 부여 -->

</head>

<body>
  <header>
    <div class='title'><a href='../index.php'>Amess Management System</a></div>
    <div id='menu'>
      <ul>
        <li><a href='../quote/quot_view'>견적관리</a>
          <ul>
            <li><a href='../quote/quot_new.php'>신규등록</a></li>
            <li><a href='../quote/quot_search.php'>검 색</a></li>
            <li><a href='../quote/quot_view.php'>편 집</a></li>
            <li><a href='../quote/regi_customer.php'>고객등록</a></li>
            <li><a href='#'>상세정보</a></li>
          </ul>
        </li>
        <li><a href='jobcode_view.php'>작업코드</a>
          <ul>
            <li><a href='jobcode_new.php'>그룹등록</a></li>
            <li><a href='jobcode_search.php'>검 색</a></li>
            <li><a href='jobcode_view.php'>편 집</a></li>
            <li><a href='jobcode_view.php'>삭 제</a></li>
            <li><a href='#'>상세정보</a></li>
          </ul>
        </li>
        <li><a href='#'>영업관리</a>
          <ul>
            <li><a href='../Order/order_new.php'>발주관리</a></li>
            <li><a href='#'>매출관리</a></li>
            <li><a href='#'>생산관리</a></li>
          </ul>
        </li>
        <li><a href='#'>장비관리</a>
          <ul>
            <li><a href='#'>신규등록</a></li>
            <li><a href='#'>검 색</a></li>
            <li><a href='#'>편 집</a></li>
            <li><a href='#'>삭 제</a></li>
            <li><a href='#'>상세정보</a></li>
          </ul>
        </li>
        <li><a href='#'>설정</a>
          <ul>
            <li><a href='../login/register_form.php'>사용자 관리</a></li>
            <li><a href='#'>편 집</a></li>
            <li><a href='#'>삭 제</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </header>
  <div id='jobcode'>
    <form action='job_se_process.php' method="POST">

      <table id='job_se_code'>
        <h3>작업코드 관리 - 검색</h3>
        <tr>
          <th>검색 코드</th>
          <td><input type='text' style='border:none' placeholder="검색할 코드 입력" name="code"></td>
        </tr>
      </table>
      <table id='jobcode_view_contents'>
        <tr>
          <th style='width: 9%'>코드그룹</th>
          <th style='width: 28%'>장비명</th>
          <th style='width: 28%'>모델명</th>
          <th>버젼</th>
          <th>등록자</th>
          <th>등록일자</th>
          <th style='width: 18%'>비고</th>
        </tr>

        <?php
        function nullable_htmlspecialchars($input)
        {
          return htmlspecialchars($input ?? "");
        } ?>
        <?php
        $sql = "SELECT * FROM jobcode LEFT JOIN employee ON jobcode.pic=employee.ep_id";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($result)) {
          $filtered = array(
            'codeg' => htmlspecialchars($row['codeg']),
            'q_system' => nullable_htmlspecialchars($row['q_system']),
            'model' => nullable_htmlspecialchars($row['model']),
            'ver' => htmlspecialchars($row['eqip_ver']),
            'pic' => htmlspecialchars($row['ep_name']),
            'regist_date' => htmlspecialchars($row['reg_date']),
            'specification' => nullable_htmlspecialchars($row['jobcode_specifi']),
          );
        ?>
          <tr>
            <td><?= $filtered['codeg'] ?></td>
            <td><?= $filtered['q_system'] ?></td>
            <td><?= $filtered['model'] ?></td>
            <td><?= $filtered['ver'] ?></td>
            <td><?= $filtered['pic'] ?></td>
            <td><?= $filtered['regist_date'] ?></td>
            <td><?= $filtered['specification'] ?></td>
          </tr>
        <?php } ?>
      </table>
  </div>
</body>

</html>