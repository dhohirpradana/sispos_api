<?php
require_once "connect.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();
    $user_id = addslashes(trim($_POST['user_id']));
    $nip = addslashes(trim($_POST['nip']));
    $andjwt = addslashes(trim($_POST['andjwt']));

    $cek = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_fetch_array(mysqli_query($con, $cek));
    if (isset($result)) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $payload = json_encode(['userId' => $nip]);
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'secretkey', true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $base64UrlHeader . "" . $base64UrlPayload . "" . $base64UrlSignature;

        if ($jwt = $andjwt) {
            $sql = mysqli_query($con, "SELECT spops.id, spops. nop_asal, spops.updated_at, data_subjek_pajaks.nama_subjek_pajak, data_subjek_pajaks.desa from spops, data_subjek_pajaks where spops.user_id = '3' and spops.is_deleted = '0' and data_subjek_pajaks.spop_id = spops.id order by updated_at desc");
            while ($a = mysqli_fetch_array($sql)) {
                $b['id'] = $a['id'];
                $b['nop_asal'] = $a['nop_asal'];
                $b['updated_at'] = $potong = substr($a['updated_at'], 0, 10);
                $b['nama'] = $a['nama_subjek_pajak'];
                $b['desa'] = $a['desa'];
                array_push($response, $b);
            }
        }
    }

    echo json_encode($response);
}
