<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "connect.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    #code...
    $response = array();
    $luas = $_POST['bangunanluas'];
    $bangun = $_POST['bangunanbangun'];
    $renov = $_POST['bangunanrenov'];
    $lantaijumlah = $_POST['bangunanjumlahlantai'];
    $daya = $_POST['bangunandaya'];
    $penggunaan = $_POST['bangunanpenggunaan'];
    $kondisi = $_POST['bangunankondisi'];
    $konstruksi = $_POST['bangunankonstruksi'];
    $atap = $_POST['bangunanatap'];
    $dinding = $_POST['bangunandinding'];
    $lantai = $_POST['bangunanlantai'];
    $langit = $_POST['bangunanlangit'];
    $uid = $_POST['uid'];
    $uuid = $_POST['uuid'];

    $nip = $_POST['nip'];
    $andjwt = $_POST['andjwt'];

    function GUID()
    {
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
    }
    $uuid1 = GUID();

    $insert_rincian_bangunan = "INSERT INTO `rincian_data_bangunans` (`id`, `uuid`, `luas_bangunan`, `tahun_dibangun`, `tahun_renovasi`, `jumlah_lantai`, `daya_listrik`, `jenis_penggunaan_bangunan_id`, `kondisi_id`, `konstruksi_id`, `atap_id`, `dinding_id`, `lantai_id`, `langit_id`, `spop_id`, `created_at`, `updated_at`) VALUES (NULL, '$uuid$uuid1', '$luas', '$bangun', '$renov', '$lantaijumlah', '$daya', '$penggunaan', '$kondisi', '$konstruksi', '$atap', '$dinding', '$lantai', '$langit', (SELECT max(id) FROM spops WHERE spops.user_id = $uid), NOW(), NOW())";

    $cek = "SELECT * FROM users WHERE id='$uid'";
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
            if (mysqli_query($con, $insert_rincian_bangunan)) {
                $response['bangunan'] = 1;
                $response['resbang'] = "berhasil bangunan";
                echo json_encode($response);
            } else {
                $response['bangunan'] = 0;
                $response['resbang'] = "gagal bangunan";
                echo json_encode($response);
            }
        }
    }
} else {
    $response['pesan'] = "no auth";
}
mysqli_close($con);

?>