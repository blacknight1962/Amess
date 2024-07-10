<?php
function performSalesSearch($conn, $tables, $dateColumn, $period = '', $year = '', $input = '') {
    $period = mysqli_real_escape_string($conn, $period);
    $year = mysqli_real_escape_string($conn, $year);
    $input = mysqli_real_escape_string($conn, $input);

    $sql = 'SELECT o.*, od.*, s.*
            FROM ' . implode(' ', $tables);

    $conditions = [];

    if (!empty($period) && empty($year)) {
        $currentDate = date('Y-m-d');
        if ($period == '1year') {
            $startDate = date('Y-m-d', strtotime('-1 year'));
        } elseif ($period == '3years') {
            $startDate = date('Y-m-d', strtotime('-3 years'));
        } else {
            $startDate = '';
        }

        if (!empty($startDate)) {
            $conditions[] = "$dateColumn BETWEEN '$startDate' AND '$currentDate'";
        }
    }
    if (!empty($year)) {
        $conditions[] = "YEAR($dateColumn) = '$year'";
    }

    if (!empty($input)) {
        $conditions[] = "(od.product_na LIKE '%$input%' OR od.product_sp LIKE '%$input%' OR od.parts_code LIKE '%$input%' OR od.order_no LIKE '%$input%' OR od.o_no LIKE '%$input%' OR o.order_custo LIKE '%$input%' OR o.customer LIKE '%$input%' OR o.custo_name LIKE '%$input%' OR o.order_date LIKE '%$input%' OR od.condit LIKE '%$input%' OR od.specifi LIKE '%$input%')";
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY ' . $dateColumn . ' DESC, s.serial_no ASC';

    return mysqli_query($conn, $sql);
}
?>