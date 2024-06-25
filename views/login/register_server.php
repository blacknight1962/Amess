<?php
include('../../db.php');

//security code
if (isset($_POST['ep_id']) && isset($_POST['pw']) && isset($_POST['ep_name']) && isset($_POST['division']) && isset($_POST['Mphone']) && isset($_POST['ep_addr'])) {
  $user_id = mysqli_real_escape_string($conn, $_POST['ep_id']);
  $user_pw = mysqli_real_escape_string($conn, $_POST['pw']);
  $user_pw1 = mysqli_real_escape_string($conn, $_POST['pw1']);
  $user_name = mysqli_real_escape_string($conn, $_POST['ep_name']);
  $user_div = mysqli_real_escape_string($conn, $_POST['division']);
  $user_pho = mysqli_real_escape_string($conn, $_POST['Mphone']);
  $user_add = mysqli_real_escape_string($conn, $_POST['ep_addr']);

  $user_info = "user_id=" . $user_id;

  if (empty($user_id)) {
    header('location:register_form.php?error=아이디가 비어 있어요&$user_info');
    exit();
  } else if (empty($user_pw)) {
    header('location:register_form.php?error=비밀번호가 비어 있어요&$user_info');
    exit();
  } else if (empty($user_pw1)) {
    header('location:register_form.php?error=비밀번호 확인해주세요&$user_info');
    exit();
  } else if ($user_pw !== $user_pw1) {
    header('location:register_form.php?error=비밀번호가 일치하지 않아요&$user_info');
    exit();
  } else if (empty($user_name)) {
    header('location:register_form.php?error=이름이 비어있어요&$user_info');
    exit();
  } else if (empty($user_div)) {
    header('location:register_form.php?error=부서가 비어있어요&$user_info');
    exit();
  } else if (empty($user_pho)) {
    header('location:register_form.php?error=전화번호가 비어있어요&$user_info');
    exit();
  } else if (empty($user_add)) {
    header('location:register_form.php?error=주소가 비어있어요&$user_info');
    exit();
  } else {
    //암호화
    $pw_s = password_hash($user_pw, PASSWORD_DEFAULT);
    //아이디 또는 이름 중복여부 체크
    $Duple_check = "SELECT * FROM employee WHERE ep_id = '$user_id' or ep_name = '$user_name'";
    $order = mysqli_query($conn, $Duple_check);

    if (mysqli_num_rows($order) > 0) {
      header('location:edit_login.php?error=아이디 또는 이름이 이미 있어요');
      exit();
    } else {
      $sql_save = "INSERT INTO employee (ep_id, ep_name, division, Mphone, ep_addr) values('$user_id', '$user_name','$user_div', '$user_pho', '$user_add')";
      $result = mysqli_query($conn, $sql_save);

      $sql_save = "INSERT INTO pw_board (id, pw) values('$user_id', '$pw_s')";
      $result = mysqli_query($conn, $sql_save);


      if ($result) {
        header('location: register_form.php?success=등록이 완료되었습니다');
        exit();
      } else {
        header('location: register_form.php?error=문제가 발생했습니다, 관리자에게 문의 하십시오');
        exit();
      }
    }
  }
} else {
  header("location:register_form.php?error=알 수 없는 오류 발생");
  exit();
}
