<?php
include('../db.php');

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $sql = "SELECT * FROM equipment WHERE equip LIKE '%{$input}%' OR model_p LIKE '%{$input}%' OR supplyer LIKE '%{$input}%'
    OR regi_date LIKE '%{$input}%' OR process_p LIKE '%{$input}%' OR specif LIKE '%{$input}%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $filtered = array(
                'e_no' => htmlspecialchars($row['e_no']),
                'picb' => htmlspecialchars($row['picb']),
                'equip' => htmlspecialchars($row['equip']),
                'model_p' => htmlspecialchars($row['model_p']),
                'regi_date' => htmlspecialchars($row['regi_date']),
                'customer' => htmlspecialchars($row['customer']),
                'supplyer' => htmlspecialchars($row['supplyer']),
                'process_p' => htmlspecialchars($row['process_p']),
                'specif' => htmlspecialchars($row['specif'])
            );
            echo "<tr class='table-hover'>
                <td>{$filtered['e_no']}</td>
                <td>{$filtered['picb']}</td>
                <td>{$filtered['equip']}</td>
                <td>{$filtered['model_p']}</td>
                <td>{$filtered['regi_date']}</td>
                <td>{$filtered['customer']}</td>
                <td>{$filtered['supplyer']}</td>
                <td>{$filtered['process_p']}</td>
                <td>{$filtered['specif']}</td>
                <td>
                    <button type='button' class='btn btn-link p-0 border-0 background-transparent' data-bs-toggle='modal' data-bs-target='#editEquipData' data-e_no='{$filtered['e_no']}' title='편집' style='background: none; border: none;'>
                        <i class='fa-solid fa-pen-to-square fs-6'></i>
                    </button>
                    <button type='button' class='btn btn-link delete-button btn-minimal-padding' data-equip-id='{$filtered['e_no']}' title='삭제'>
                        <i class='fa-solid fa-trash fs-6'></i>
                    </button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='10' class='text-center'>검색 결과가 없습니다.</td></tr>";
    }
}
?>