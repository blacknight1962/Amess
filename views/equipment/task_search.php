<?php
session_start();
include('include/header.php');
include(__DIR__ . '/../../db.php');
include ("task_searchajax_f.php");
include ("task_searchajax_t.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Equipment</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/practice/AMESystem/public/style.css">
    <link rel="stylesheet" href="/practice/AMESystem/public/style_equip.css">
    <script src="js/task_search.js"></script>
    
</head>

<body>
<div class='bg-dark bg-opacity-10' style='text-align: center;'>
  <h6 class='bg-primary bg-opacity-10 mt-2 mb-1 p-2' style='text-align: center; font-weight: bold;'>설비 검색</h6>
    <div class='container-fluid' style='padding: 0 10px; display: flex; align-items: center; margin: 6px 2px;'>
      <!-- 기간 선택 버튼 -->
      <button type="button" id="oneYearBtn1" class="btn btn-outline-primary btn-sm" style="font-size: .65rem; margin: 2px" data-period="1year">최근 1년</button>
      <button type="button" id="threeYearsBtn1" class="btn btn-outline-primary btn-sm" style="font-size: .65rem; margin: 2px" data-period="3years">최근 3년</button>
      <!-- 특정 년도 선택 드롭다운 -->
      <select id="yearSelect1" class="form-select form-select-sm me-2" style="font-size: .65rem; width: auto;">
          <option value="">선택</option>
          <?php
          $currentYear = date("Y");
          for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
              echo "<option value='$year'>$year</option>";
          }
          ?>
      </select>
      <!-- 검색란 -->
      <input type='text' class='form-control form-control-sm me-2' style="font-size: .65rem; width: 30%;" name="searchQuery" id="searchInput1" autocomplete="off" placeholder="Search....">
      <!-- 숨겨진 입력 필드로 선택된 기간 저장 -->
      <input type="hidden" name="selectedPeriod" id="selectedPeriod">
      <!-- <button type="button" class="btn btn-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="searchEquipment()">검색</button> -->
    </div>
    <div id="searchResultContainer" style="width: 1900px; margin: 0 auto;">
    <table class="table table-bordered table-striped table-hover" style="width: 1900px; margin: 0 auto;">
      <thead class="table-warning" style="font-size: .65rem;">
        <tr>
          <th style="width: 3%;">No</th>
          <th style="width: 3%;">부서</th>
          <th style="width: 13%;">장비명</th>
          <th style="width: 11%;">모델명</th>
          <th style="width: 9%;">S/N</th>

          <th style="width: 9%;">S/W ver.</th>
          <th style="width: 7%;">가동상황</th>
          <th style="width: 7%;">납품일자</th>
          <th style="width: 5%;">고객명</th>
          <th style="width: 5%;">사업장</th>

          <th style="width: 5%;">설치장소</th>
          <th style="width: 5%;">공정</th>
          <th style="width: 7%;">고객명칭</th>
          <th style="width: 11%;">특기사항</th>
        </tr>
      </thead>
      <tbody id="facilityEquipmentBody" style="font-size: .65rem;">

      </tbody>
    </table>
  </div>
  <!-- 작업 검색 -->
  <div id="taskSearchContainer" style="width: 1900px; margin: 0 auto;">
    <h6 class='bg-success bg-opacity-10 mt-2 mb-1 p-2' style='text-align: center; font-weight: bold;'>작업 검색</h6>
        <div class='container-fluid' style='padding: 0 10px; display: flex; align-items: center; margin: 6px 2px;'>
          <!-- 기간 선택 버튼 -->
          <button type="button" id="oneYearBtn2" class="btn btn-outline-primary btn-sm" style="font-size: .65rem; margin: 2px" data-period="1year">최근 1년</button>
          <button type="button" id="threeYearsBtn2" class="btn btn-outline-primary btn-sm" style="font-size: .65rem; margin: 2px" data-period="3years">최근 3년</button>
          <!-- 특정 년도 선택 드롭다운 -->
          <select id="yearSelect2" class="form-select form-select-sm me-2" style="font-size: .65rem; width: auto;">
            <option value="">선택</option>
            <?php
            $currentYear = date("Y");
            for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
          </select>
          <!-- 검색란 -->
          <input type='text' class='form-control form-control-sm me-2' style="font-size: .65rem; width: 30%;" name="searchQuery" id="searchInput2" autocomplete="off" placeholder="Search....">
          <!-- 숨겨진 입력 필드로 선택된 기간 저장 -->
          <input type="hidden" name="selectedPeriod" id="selectedPeriod">
        <!-- <button type="button" class="btn btn-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="searchEquipment()">검색</button> -->
        </div>
        <div id="searchResultContainer_task" style="width: 1900px; margin: 0;">
          <table class="table table-bordered table-striped table-hover" id = "data-table" style="width: 1900px; margin: 0;">
            <thead class="table-success" style="font-size: .65rem;">
              <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 4%;">부서</th>
                <th style="width: 8%;">장비명</th>
                <th style="width: 8%;">모델명</th>
                <th style="width: 8%;">S/N</th>

                <th style="width: 7%;">S/W ver.</th>
                <th style="width: 5%;">작업자</th>
                <th style="width: 5%;">작업일자</th>
                <th style="width: 5%;">가동상황</th>
                <th style="width: 5%;">고객명</th>

                <th style="width: 6%;">사업장</th>
                <th style="width: 5%;">설치장소</th>
                <th style="width: 5%;">공정</th>
                <th style="width: 6%;">고객명칭</th>
                <th style="width: 4%;">구분</th>

                <th style="width: 7%;">작업항목</th>
                <th style="width: 9%;">작업내용</th>
              </tr>
            </thead>
            <tbody id="taskSearchBody" style="font-size: .65rem;">
            </tbody>
        </table>
        </div>
</body>
</html>
