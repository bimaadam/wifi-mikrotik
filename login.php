<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voucher_code = $_POST['voucher_code'];

    // Mikrotik API credentials
    $host = "192.168.88.1"; // IP Mikrotik
    $username = "admin";    // Username Mikrotik
    $password = "password"; // Password Mikrotik

    require('routeros_api.class.php');
    $API = new RouterosAPI();

    if ($API->connect($host, $username, $password)) {
        $API->write('/ip/hotspot/user/print', false);
        $API->write('?name=' . $voucher_code);

        $READ = $API->read(false);
        $ARRAY = $API->parseResponse($READ);

        if (count($ARRAY) > 0) {
            // Voucher valid, redirect to Mikrotik login page
            header("Location: http://192.168.88.1/login?username=" . $voucher_code);
            exit;
        } else {
            echo "Voucher tidak valid.";
        }

        $API->disconnect();
    } else {
        echo "Tidak dapat terhubung ke Mikrotik.";
    }
}
?>
