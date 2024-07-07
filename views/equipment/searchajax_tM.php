<?php
include(__DIR__ . '/../../db.php');

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $sql = "SELECT 
        t.* 
    FROM 
        task_manage t 
    WHERE 
        t.task_title LIKE '%{$input}%' 
        OR t.task_person LIKE '%{$input}%' 
        OR t.task_aparts LIKE '%{$input}%' 
        OR t.hangmok LIKE '%{$input}%' 
        OR t.specification LIKE '%{$input}%' 
    ORDER BY 
        t.date_task DESC";
    $result = mysqli_query($conn, $sql); ?>

    <div style="text-align: center;"> <!-- 테이블을 감싸는 컨테이너 -->
      <table class='table table-bordered mt-1' style="font-size: .75rem; width: 1550px; margin: 0 auto;"> <!-- 테이블에 margin 추가 -->
        <thead style="max-width: 1550px; text-align: center;">
          <tr class="table-warning">
            <th style="width: 4%;">No</th>
            <th style="width: 8%;">작업일자</th>
            <th style="width: 8%;">작업담당</th>
            <th style="width: 25%;">작업내용</th>
            <th style="width: 7%;">구분</th>
            <th style="width: 13%;">항목</th>
            <th style="width: 27%;">특기사항</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['t_no']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_task']) . "</td>";
            echo "<td>" . htmlspecialchars($row['task_person']) . "</td>";
            echo "<td>" . htmlspecialchars($row['task_title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['task_aparts']) . "</td>";
            echo "<td>" . htmlspecialchars($row['hangmok']) . "</td>";
            echo "<td>" . htmlspecialchars($row['specification']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>검색 결과가 없습니다.</td></tr>";
    }
}
?>