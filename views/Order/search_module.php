<?php
function searchOrders($conn, $tables, $period = '', $year = '', $keyword = '', $offset = 0, $itemsPerPage = 10) {
    $period = mysqli_real_escape_string($conn, $period);
    $year = mysqli_real_escape_string($conn, $year);
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $offset = max(0, $offset); // offset이 음수인 경우 0으로 설정

    $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . implode(' ', $tables);

    $conditions = [];

    if (!empty($period)) {
        $currentDate = date('Y-m-d');
        switch ($period) {
            case '1year':
                $startDate = date('Y-m-d', strtotime('-1 year'));
                break;
            case '3years':
                $startDate = date('Y-m-d', strtotime('-3 years'));
                break;
            default:
                $startDate = ''; // 특정 기간을 처리하는 로직 추가 필요
                break;
        }

        if (!empty($startDate)) {
            $conditions[] = "o.order_date BETWEEN '$startDate' AND '$currentDate'";
        }
    }

    if (!empty($year)) {
        $conditions[] = "YEAR(o.order_date) = '$year'";
    }

    if (!empty($keyword)) {
        $conditions[] = "(o.order_no LIKE '%$keyword%' OR od.product_na LIKE '%$keyword%')";
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY o.order_date DESC, o.o_no ASC';
    $sql .= " LIMIT $offset, $itemsPerPage";

    $result = mysqli_query($conn, $sql);
    $totalResult = mysqli_query($conn, "SELECT FOUND_ROWS() as total");
    $totalRows = mysqli_fetch_assoc($totalResult)['total'];

    return ['result' => $result, 'total' => $totalRows];
}
?>