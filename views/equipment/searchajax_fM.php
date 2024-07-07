<?php
include(__DIR__ . '/../../db.php');
include(__DIR__ . '/../../public/Selection_kit.php');

if (isset($_POST['input'])) {
    $input = $_POST['input'];
    $sql = "SELECT 
        f.* 
    FROM 
        facility f 
    WHERE 
        f.place_fac LIKE '%{$input}%' 
        OR f.line_no LIKE '%{$input}%'
        OR f.sub_no LIKE '%{$input}%' 
        OR f.seri_no LIKE '%{$input}%' 
        OR f.custo_nick LIKE '%{$input}%' 
        OR f.sw_ver LIKE '%{$input}%' 
        OR f.manage_stat LIKE '%{$input}%' 
        OR f.specif LIKE '%{$input}%'
    ORDER BY 
        f.e_no DESC";
    $result_data = mysqli_query($conn, $sql); ?>

      <table class='table table-bordered mt-1' style="font-size: .75rem; width: 1500px;">
        <thead style="max-width: 1500px; text-align: center; vertical-align: middle;">
          <tr class='table table-secondary'>
            <th style="width: 4%;">No</th>
            <th style="width: 13%;">S/N</th>
            <th style="width: 10%;">납품일자</th>
            <th style="width: 10%;">사업장</th>
            <th style="width: 7%;">설치장소</th>
            <th style="width: 13%;">고객명칭</th>
            <th style="width: 15%;">S/W ver.</th>
            <th style="width: 9%;">가동상황</th>
            <th style="width: 20%;">특기사항</th>
          </tr>
        </thead>
        <tbody>
          <?php           
            if (mysqli_num_rows($result_data) > 0) {
              while ($row_data = mysqli_fetch_array($result_data)) {
                  $filtered = array(
              'e_no' => htmlspecialchars($row_data["e_no"]),
              'sub_no' => htmlspecialchars($row_data["sub_no"]),
              'seri_no' => htmlspecialchars($row_data["seri_no"]),
              'date_supply' => htmlspecialchars($row_data["date_supply"]),
              'place_fac' => htmlspecialchars($row_data["place_fac"]),
              'line_no' => htmlspecialchars($row_data["line_no"]),
              'custo_nick' => htmlspecialchars($row_data["custo_nick"]),
              'sw_ver' => htmlspecialchars($row_data["sw_ver"]),
              'manage_stat' => htmlspecialchars($row_data["manage_stat"]),
              'specif' => htmlspecialchars($row_data["specif"]),
              ); //array 출력 
        ?>
          <tr id='FT_Row' data-e-no="<?php echo $filtered['e_no']; ?>"> <!-- e_no 값을 data 속성으로 추가 -->
            <td><input type="text" class="form-control sub_no" style="text-align: center;" name="sub_no[]" value="<?php echo $filtered["sub_no"]; ?>"></td>
            <td><input type="text" class="form-control seri_no"  name="seri_no[]" value="<?php echo $filtered["seri_no"]; ?>"></td>
            <td><input type="text" class="form-control date_supply" name="date_supply[]" value="<?php echo $filtered["date_supply"]; ?>"></td>
            <td><input type="text" class="form-control place_fac" name="place_fac[]" value="<?php echo $filtered["place_fac"]; ?>"></td>
            <td><input type="text" class="form-control line_no" name="line_no[]" value="<?php echo $filtered["line_no"]; ?>"></td>
            <td><input type="text" class="form-control custo_nick" name="custo_nick[]" value="<?php echo $filtered["custo_nick"]; ?>"></td>
            <td><input type="text" class="form-control sw_ver" name="sw_ver[]" value="<?php echo $filtered["sw_ver"]; ?>"></td>
            <td><?= createSelectStatus($conn, 'manage_stat[]', $filtered['manage_stat']); ?></td>
            <td><input type="text" class="form-control specif" name="specif[]" value="<?php echo $filtered["specif"]; ?>"></td>
          </tr> 
  <?php    } //while 문 종료 
            } else 
              echo "<tr><td colspan='11' style='text-align: center;'>현재 데이터가 없습니다. 추가하려면 <span style='color: blue;'>설비추가</span>을 클릭해주세요.</td></tr>";
          }?> 
          </tbody>
        </table>