<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: multipart/form-data');
require"../connect.php";

if($_SERVER['REQUEST_METHOD']=="POST"){
    #code...
    $response = array();
    $foto = $_FILES['foto']['name'];
    
     $imagePath = "images/".$foto;
    
    move_uploaded_file($_FILES['foto']['tmp_name'],$imagePath);
    
        $insert_fotos = "INSERT INTO `foto` (`spop_id`, `data`, `created_at`, `updated_at`) VALUES ((SELECT MAX(id) FROM `spops`), '$foto', NOW(), NOW())";
        if (mysqli_query($con, $insert_fotos)){
        $response['value']=1;
        $response['pesan']="upload foto berhasil";
        echo json_encode($response);
        } else {
        $response['value']=0;
        $response['pesan']="gagal upload foto";
        echo json_encode($response);
        }
    }
?>