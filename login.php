<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "connect.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    #code...
    $response = array();
    $nip = addslashes(trim($_POST['nip']));
    $pass = addslashes(trim($_POST['pass']));

    $cek = "SELECT * FROM users WHERE nip='$nip' and password='$pass'";
    $result = mysqli_fetch_array(mysqli_query($con, $cek));

    if (isset($result)) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $payload = json_encode(['userId' => $nip]);
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'secretkey', true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $base64UrlHeader . "" . $base64UrlPayload . "" . $base64UrlSignature;

        $response['value'] = 1;
        $response['level'] = $result['level'];
        $response['message'] = "Login Berhasil";
        $response['nip'] = $result['nip'];
        $response['id'] = $result['id'];
        $response['name'] = $result['name'];
        $response['pass'] = $result['pass'];
        $response['token'] = $jwt;

        echo json_encode($response);
    } else {
        $response['value'] = 0;
        $response['message'] = "Login Gagal";
        echo json_encode($response);
    }
}
