<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>JSON DECODE</title>
<link rel="stylesheet" type="text/css" href="style.css"/>

</head>
<body>
    
<?php

$file = 'barcode.json'; 

if(mktime() - filemtime($file) > 5 || !is_file($file) ){
    // Recupère les données du fichier "barcode.json" toutes les 5 secondes  
    $content = file_get_contents($file);   
}

$url = json_decode($content);

$request = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $url;

$response = file_get_contents($request);  
$results = json_decode($response);

$prout = $results->items;

//echo $response;

foreach($prout as $test){
    
    $titre  = $test->volumeInfo->title;
    $auteur = $test->volumeInfo->authors[0];
    $sub    = $test->searchInfo->textSnippet;
    $img    = $test->imageLinks->thumbnail;
    
 
//    echo $sub;
//    echo $img;
}
?>
    <h1> <?php echo $auteur ?></h1>
    <h2> <?php echo $titre  ?></h2>
    <h4> <?php echo $sub    ?></h4>
    <img src="<?php echo $img ?>">
    


    
</body>
</html>