<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>메인 화면</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../public/css/main_style.css">
    <style>
    .card {
            cursor: pointer;
        }
        /* 아이콘 움직임 애니메이션 정의 */
        @keyframes runningAnimation {
            0% { transform: translateX(0); }
            50% { transform: translateX(5px); }
            100% { transform: translateX(0); }
        }
        .running-icon {
            animation: runningAnimation 1s infinite;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- 상단 로고와 로그아웃 아이콘을 위한 div -->
        <div class="container-fluid">
            <div class="navbar-header">
                <img src="../public/img/amess_logo.png" width="150" height="20">
            </div>
            <div class="d-flex justify-content-end">
                <a href="../views/login/logout.php" style="font-size: 16px;"><i class="fa-solid fa-person-running custom-icon-size running-icon"></i>LOGOUT</a>
            </div>
        </div>
    </nav>
    <div class="container mt-0" style="height: 85vh;">
        <div class="row justify-content-center">
            <!-- 메인 메뉴 카드들 -->
            <div class="col-md-3">
                <div class="card shadow mb-3" onclick="openInNewTab('../views/quote/quote_index.php')">
                    <div class="card-body">
                        <h5 class="card-title">견적관리</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow mb-3" onclick="openInNewTab('../views/job_code/jobcode_index.php')">
                    <div class="card-body text-center">
                        <h5 class="card-title">작업코드</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow mb-3" onclick="openInNewTab('../views/order/order_index.php')">
                    <div class="card-body text-center">
                        <h5 class="card-title">영업관리</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow mb-3" onclick="openInNewTab('../views/equipment/equip_index.php')">
                    <div class="card-body text-center">
                        <h5 class="card-title">장비관리</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <i class="fas fa-cog settings-icon"><a href="../views/login/login_edit.php" target="_blank"></a></i>
    <!-- 로그인 모달 포함 -->
    <?php include '../views/login/login.php'; ?>
    <script>
        function openInNewTab(url) {
            window.open(url, '_blank').focus();
        }
        function toggleMenu(menuId) {
            var menu = document.getElementById(menuId);
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }
        window.onload = function() {
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            <?php endif; ?>
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>