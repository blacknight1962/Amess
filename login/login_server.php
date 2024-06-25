<?php
include('../db.php');

if (isset($_POST['id']) && isset($_POST['password'])) {
  $user_id = mysqli_real_escape_string($conn, $_POST['id']);
  $user_pw = mysqli_real_escape_string($conn, $_POST['password']);

  if (empty($user_id)) {
    header('location:../Main_index.php?error=아이디가 비어 있어요');
    exit();
  } else if (empty($user_pw)) {
    header('location:../Main_index.php?error=비밀번호가 비어 있어요');
    exit();
  } else {
    // pw_board 테이블에서 일치하는 아이디의 데이터를 가져오기
    $sql = "SELECT * FROM pw_board where id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
      $row = mysqli_fetch_assoc($result);
      $hash = $row['pw'];

      if (password_verify($user_pw, $hash)) {
        // SESSION 시작
        session_start();
        $_SESSION['ss_id'] = $user_id;  // 사용자 ID를 세션에 저장
        $_SESSION['date'] = date('Y-m-d');
        $_SESSION['loggedin'] = true;  // 로그인 상태를 세션에 저장

        // 사용자 이름을 가져오기 위한 쿼리
        $sql_name = "SELECT * FROM employee WHERE ep_id='$user_id'";
        $result_name = mysqli_query($conn, $sql_name);
        if ($row_name = mysqli_fetch_assoc($result_name)) {
          $_SESSION['username'] = htmlspecialchars($row_name['ep_name']);  // 사용자 이름을 세션에 저장
        }

        // PHP header를 사용하여 리다이렉트
        header('location: ../Main_index.php');
        exit();
      } else {
        header('location: ../Main_index.php?error=비밀번호 에러입니다. 다시 한번 확인 하십시오');
        exit();
      }
    } else {
      header('location: ../Main_index.php?error=아이디 에러입니다. 다시 한번 확인 하십시오');
      exit();
    }
  }
} else {
  header('location: ../Main_index.php?error=로그인 에러입니다. 관리자에게 문의 하십시오');
  exit();
}
?>