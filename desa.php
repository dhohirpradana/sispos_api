<?php
require_once"connect.php";

$response = array();

$sql = mysqli_query($con, "SELECT * from desas order by nama asc");
while($a=mysqli_fetch_array($sql)){
    $b['id'] = $a['id'];
    $b['nama']=$a['nama'];
    array_push($response, $b);
    
}

echo json_encode($response);

    mysqli_close($con);

?>