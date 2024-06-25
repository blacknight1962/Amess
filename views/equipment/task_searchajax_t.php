<?php
include('../db.php');

$sql = "SELECT e.*, f.* FROM equipment e INNER JOIN facility f ON e.e_no = f.e_no WHERE 1=1";

if (isset($_POST['period'])) {
    $period = $_POST['period'];
    $dateFrom = date('Y-m-d', strtotime("-$period years"));
    $sql .= " AND e.regi_date >= '{$dateFrom}'";
}
if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $sql = "SELECT 
        e.*, 
        f.*, 
        t.* 
    FROM 
        equipment e
    INNER JOIN 
        facility f ON e.e_no = f.e_no
    INNER JOIN 
        task_manage t ON f.seri_no = t.seri_no
    WHERE 
        e.equip LIKE '%{$input}%' 
        OR e.model_p LIKE '%{$input}%' 
        OR e.e_no LIKE '%{$input}%' 
        OR f.sub_no LIKE '%{$input}%' 
        OR e.picb LIKE '%{$input}%' 
        OR f.seri_no LIKE '%{$input}%'
        OR f.sw_ver LIKE '%{$input}%'
        OR f.manage_stat LIKE '%{$input}%'
        OR t.date_task LIKE '%{$input}%'
        OR e.customer LIKE '%{$input}%'
        OR e.process_p LIKE '%{$input}%' 
        OR e.specif LIKE '%{$input}%'
        OR f.place_fac LIKE '%{$input}%'
        OR f.line_no LIKE '%{$input}%'
        OR f.custo_nick LIKE '%{$input}%'
        OR t.task_person LIKE '%{$input}%'
        OR t.task_aparts LIKE '%{$input}%'
        OR t.hangmok LIKE '%{$input}%'
        OR t.task_title LIKE '%{$input}%'
    ORDER BY 
        t.date_task DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row_number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr data-e_no='{$row['e_no']}' data-sub_no='{$row['sub_no']}' data-seri_no='{$row['seri_no']}'>
                    <td>{$row_number}</td>
                    <td>{$row['picb']}</td>
                    <td>{$row['equip']}</td>
                    <td>{$row['model_p']}</td>
                    <td>{$row['seri_no']}</td>
                    <td>{$row['sw_ver']}</td>
                    <td>{$row['task_person']}</td>
                    <td>{$row['date_task']}</td>
                    <td>{$row['manage_stat']}</td>
                    <td>{$row['customer']}</td>
                    <td>{$row['place_fac']}</td>
                    <td>{$row['line_no']}</td>
                    <td>{$row['process_p']}</td>
                    <td>{$row['custo_nick']}</td>
                    <td>{$row['task_aparts']}</td>
                    <td>{$row['hangmok']}</td>
                    <td>{$row['task_title']}</td>
                </tr>";
            $row_number++;
        }
    } else {
        echo "<tr><td colspan='17' class='text-center'>검색 결과가 없습니다.</td></tr>";
    }
}
?>
