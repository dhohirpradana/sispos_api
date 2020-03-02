<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require"connect.php";

if($_SERVER['REQUEST_METHOD']=="POST"){
    #code...
    $response = array();
    $spop_id = $_POST['id'];
    $uid = $_POST['uid'];
    
        $insert = "UPDATE spops SET is_deleted = '1' WHERE id = '$spop_id'";
        if (mysqli_query($con, $insert)){
        $response['value']=1;
        $response['pesan']="berhasil hapus";
        echo json_encode($response);
        } else {
        $response['value']=0;
        $response['pesan']="gagal hapus";
        echo json_encode($response);
        }
    }
?>