<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>MYSQL</title>
</head>
<body>
    
<?php

$user = 'root';
$password = 'root';
$db = 'app';
$host = 'localhost';
$port = 8889;

$link = mysql_connect(
   "$host:$port", 
   $user, 
   $password
);
$db_selected = mysql_select_db(
   $db, 
   $link
);

//$file = 'barcode.json'; 
//
//if(mktime() - filemtime($file) > 5 || !is_file($file) ){
//    // Recupère les données du fichier "barcode.json" toutes les 5 secondes  
//    $content = file_get_contents($file);   
//}
//
//$isbn = json_decode($content);

// si la connexion se fait en UTF-8, sinon ne rien indiquer
//$mysqli->set_charset("utf8");
/*
utilisation de la méthode connect_error
qui renvoie un message d'erreur si la connexion échoue
*/
if ($db_selected->connect_error) {
    die('Erreur de connexion ('.$db_selected->connect_errno.')'. $db_selected->connect_error);
}

//echo 'ça marche bien';
//
$requete = mysql_query("SELECT titre FROM z_notices") OR die('Erreur de la requête MySQL');

//echo $requete;

mysql_close($db_selected);

while($resultat = mysql_fetch_object($requete))
     {
          echo '<p>auteur : '.$resultat'</p>';
//          echo '<p>titre : '.$resultat->titre'</p>';
     } 

?>
    
</body>
</html>