<?php
function route($uri) {
    $uri = trim($uri, '/');
    if ($uri == '') {
        return 'views/main_index.php';
    } elseif ($uri == 'about') {
        return 'views/about.php';
    } elseif ($uri == 'contact') {
        return 'views/contact.php';
    } elseif ($uri == 'quote') {
        return 'views/quote/quote_index.php';
    } elseif ($uri == 'jobcode') {
        return 'views/job_code/jobcode_index.php';
    } elseif ($uri == 'equip') {
        return 'views/equipment/equip_index.php';
    } elseif ($uri == 'order') {
        return 'views/order/order_index.php';
    } elseif ($uri == 'login') {
        return 'views/login/login.php';
    } else {
        return 'views/404.php';
    }
}
?>