<?php
 
// Récupération des paramètres
$result = new StdClass();
if( !empty($_GET['chaine']) ){
    $result = $_GET['chaine'];
}

// Enregistrement des paramètres dans "barcode.json"
$result = json_encode($result);

file_put_contents('barcode.json', $result);

header('Content-type: application/json');
echo $result;