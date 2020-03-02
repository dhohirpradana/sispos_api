<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "connect.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    #code...
    $response = array();
    $nopasal = addslashes(trim($_POST['nopasal']));
    $objekjalan = addslashes(trim($_POST['objekjalan']));
    $objekblok = addslashes(trim($_POST['objekblok']));
    $objekdesa = addslashes(trim($_POST['objekdesa']));
    $objekrw = addslashes(trim($_POST['objekrw']));
    $objekrt = addslashes(trim($_POST['objekrt']));

    $subjekstatus = addslashes(trim($_POST['subjekstatus']));
    $subjekpekerjaan = addslashes(trim($_POST['subjekpekerjaan']));
    $subjeknama = addslashes(trim($_POST['subjeknama']));
    $subjeknamajalan = addslashes(trim($_POST['subjeknamajalan']));
    $subjekkab = addslashes(trim($_POST['subjekkab']));
    $subjekdesa = addslashes(trim($_POST['subjekdesa']));
    $subjekrw = addslashes(trim($_POST['subjekrw']));
    $subjekrt = addslashes(trim($_POST['subjekrt']));
    $subjekktp = addslashes(trim($_POST['subjekktp']));

    $uuid = addslashes(trim($_POST['uuid']));
    $uid = addslashes(trim($_POST['uid']));

    $tanahluas = addslashes(trim($_POST['tanahluas']));
    $tanahjenis = addslashes(trim($_POST['tanahjenis']));

    $nip = addslashes(trim($_POST['nip']));
    $andjwt = addslashes(trim($_POST['andjwt']));

    function GUID()
    {
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
    }
    $uuid1 = GUID();

    $insert_spops = "INSERT INTO spops (uuid,nop,nop_asal,data_letak_objek_id,data_subjek_pajak_id,data_tanah_id,user_id,created_at,updated_at,is_deleted) VALUES (
        '$uuid$uuid1',
        NULL,
        '$nopasal',
        (SELECT id FROM `data_letak_objeks` ORDER BY id DESC limit 1),
        (SELECT id FROM `data_subjek_pajaks` ORDER BY id DESC limit 1),
        (SELECT id FROM `data_tanahs` ORDER BY id DESC limit 1),
        '$uid',
        NOW(),
        NOW(),
        '0')";
    $insert_objeks = "INSERT INTO `data_letak_objeks` (`nama_jalan`, `blok_kav`, `rw`, `rt`, `desa_id`, `spop_id`,`created_at`,`updated_at`) VALUES (
        '$objekjalan',
        '$objekblok',
        '$objekrw',
        '$objekrt',
        '$objekdesa',
        (SELECT MAX(id) FROM `spops`), 
        NOW(), 
        NOW())";
    $insert_subjeks = "INSERT INTO `data_subjek_pajaks` (`nama_jalan`, `nama_subjek_pajak`, `rt`, `rw`, `nomor_ktp`, `status_id`, `pekerjaan_id`, `desa`, `kabupaten`, `spop_id`, `created_at`, `updated_at`) VALUES (
        '$subjeknamajalan',
        '$subjeknama',
        '$subjekrt',
        '$subjekrw',
        '$subjekktp',
        '$subjekstatus',
        '$subjekpekerjaan',
        '$subjekdesa',
        '$subjekkab', 
    (SELECT max(id) FROM `spops`),
    NOW(),
    NOW())";
    $insert_tanahs = "INSERT INTO `data_tanahs` (`luas_tanah`, `jenis_tanah_id`, `spop_id`, `created_at`, `updated_at`) VALUES ('$tanahluas', '$tanahjenis', (SELECT MAX(id) FROM `spops`), NOW(), NOW())";


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

        if ($nopasal != '') {
            $ceknop = "select nop from rujukans where nop = '$nopasal'";
            $result = mysqli_fetch_array(mysqli_query($con, $ceknop));
            if (isset($result)) {
                if ($jwt = $andjwt) {
                    if (mysqli_query($con, $insert_spops)) {
                        if (mysqli_query($con, $insert_objeks)) {
                            if (mysqli_query($con, $insert_subjeks)) {
                                if (mysqli_query($con, $insert_tanahs)) {
                                    $response['value'] = 1;
                                    $response['pesan'] = "berhasil tanahs";
                                    echo json_encode($response);
                                } else {
                                    $response['value'] = 0;
                                    $response['pesan'] = "gagal tanahs";
                                    echo json_encode($response);
                                }
                            } else {
                                $response['value'] = 0;
                                $response['pesan'] = "gagal subjeks";
                                echo json_encode($response);
                            }
                        } else {
                            $response['value'] = 0;
                            $response['pesan'] = "gagal objeks";
                            echo json_encode($response);
                        }
                    } else {
                        $response['value'] = 0;
                        $response['pesan'] = "gagal spops";
                        echo json_encode($response);
                    }
                } else {
                    $response['value'] = 0;
                    $response['pesan'] = "gagal auth token jwt";
                }
            } else {
                $response['value'] = 2;
                $response['pesan'] = "data nop tidak ada";
            }
        } else {
            if ($jwt = $andjwt) {
                if (mysqli_query($con, $insert_spops)) {
                    if (mysqli_query($con, $insert_objeks)) {
                        if (mysqli_query($con, $insert_subjeks)) {
                            if (mysqli_query($con, $insert_tanahs)) {
                                $response['value'] = 1;
                                $response['pesan'] = "berhasil tanahs";
                                echo json_encode($response);
                            } else {
                                $response['value'] = 0;
                                $response['pesan'] = "gagal tanahs";
                                echo json_encode($response);
                            }
                        } else {
                            $response['value'] = 0;
                            $response['pesan'] = "gagal subjeks";
                            echo json_encode($response);
                        }
                    } else {
                        $response['value'] = 0;
                        $response['pesan'] = "gagal objeks";
                        echo json_encode($response);
                    }
                } else {
                    $response['value'] = 0;
                    $response['pesan'] = "gagal spops";
                    echo json_encode($response);
                }
            } else {
                $response['value'] = 0;
                $response['pesan'] = "gagal auth token jwt";
            }
        }
    } else {
        $response['value'] = 0;
        $response['pesan'] = "tidak ada akun";
    }
}
mysqli_close($con);
?>