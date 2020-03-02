<?php
require_once"connect.php";

$response = array();
$namaCari=addslashes(trim($_POST['namaCari']));

$sql = mysqli_query($con, "SELECT * from rujukans where nama_subjek_pajak like '$namaCari%' or nop like '%$namaCari%.0' or nama_subjek_pajak like '%$namaCari' or nop like '%$namaCari' or nop like '$namaCari' or alamat_op like '%$namaCari%' limit 100000");
while($a=mysqli_fetch_array($sql)){
    $b['nop'] = $a['nop'];
    $b['nama_subjek_pajak'] = $a['nama_subjek_pajak'];
    $b['alamat_op'] = $a['alamat_op'];
    array_push($response, $b);
    
}

echo json_encode($response);

?>