<?php
    require_once("includes/load.php");
    
    $datas = file_get_contents('php://input');

    // Decode the JSON data
    $data = json_decode($datas, true);

    if($data && isset($data['id'])&& isset($data['number'])&& isset($data['cartId'])){
        $sql = "UPDATE cart set quantity = {$data['number']} WHERE id = {$data['cartId']}";
        echo $db->query($sql);
    }
?>