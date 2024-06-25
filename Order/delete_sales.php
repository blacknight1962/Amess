<?php
include "../db.php";

$id = $_GET["id"];

$sql = "DELETE FROM saleslist WHERE order_no = '$id'";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: sales_view.php?msg=Data가 삭제되었습니다");
} else {
  echo "Failed: " . mysqli_error($conn);
}
