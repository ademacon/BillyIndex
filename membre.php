<?php
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}
?>
<html>
<head>
<title>Bibliotheque</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <div class="logomembre">
<a href="index.php"><img src="img/BILLYINDEX2.png" width="200px" height="auto"></a>
    </div>
<a class="deco" href="deconnexion.php">Déconnexion</a>
<div class="bonjour">
    <h4>Salut <?php echo htmlentities(trim($_SESSION['login'])); ?> tu peux commencer à scanner ta BILLYothèque !</h4><br />
</div>
    
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

if ($db_selected->connect_error) {
    die('Erreur de connexion ('.$db_selected->connect_errno.')'. $db_selected->connect_error);
}

mysql_query("CREATE TABLE ".$table."
        (
            id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
            titre TEXT,
            subtitle TEXT,
            auteur TEXT,
            sub TEXT
        )");

    $table = htmlentities(trim($_SESSION['login']));

    $formulairejson = 'formulaire.json';
    $file = 'barcode.json';
    $jsondecode = 'url.json';
    $content = file_get_contents($file);
    $isbn = json_decode($content);

    if (file_exists($file)) {
        $request = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn);
        file_put_contents($jsondecode, $request);
    }

    $results = json_decode($request);
    $json = $results->items;


// FORMULAIRE DE RECHERCHE

?>
<div class="recherche">
<form action="membre.php" method="post">
<input type="text" name="titre" value="<?php if (isset($_POST['auteur'])) 
    echo htmlentities(trim($_POST['titre'])); ?>">
<input type="submit" name="rechercher" value="Rechercher">
</form>
</div>
<?php

// SI LE FICHIER $JSONDECODE EST SUPÉRIEURE À 50BYTES (C'EST À DIRE QU'IL Y A DU CONTENU) ON S'EN SERT

if(filesize($jsondecode) > 50 ){

    foreach($json as $test){      
        $titre  =    $test->volumeInfo->title;
        $subtitle  = $test->volumeInfo->subtitle;
        $auteur =    $test->volumeInfo->authors[0];
        $sub    =    $test->searchInfo->textSnippet;
        $img    =    $test->volumeInfo->imageLinks->thumbnail;
    }
        
        $sql = "SELECT count(*) FROM ".$table." WHERE titre='".mysql_escape_string($titre)."'";
        $req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
        $data = mysql_fetch_array($req);
    
            if ($data[0] == 0) {
                
                ?> 
                    <div class="livres">
                        <h1> <?php echo $auteur ?></h1>
                        <h2> <?php echo $titre  ?></h2>
                        <i><h3 id="test"><?php echo $subtitle ?></h3></i>
                        <h4> "<?php echo $sub    ?>"</h4>
                        <div id="thumb"><?php echo "<img  src='".$img."'/>" ?></div>
                    </div>
                <?php
                    
                $sql = "INSERT INTO ".$table." VALUES('', 
                '". mysql_real_escape_string($titre)  ."', 
                '". mysql_real_escape_string($subtitle)  ."',
                '". mysql_real_escape_string($auteur)  ."' , 
                '". mysql_real_escape_string($sub)  ."')";
                mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
            }
    
            else {
                 ?> <div class="bloc" id="formlivres"><h1 id="introuvable"> Tu as déjà scanné ce livre </h1></div> <?php
            }


                                        }elseif(file_exists($formulairejson)){
                                        
    // SI LE FICHIER DU FORMULUAIRE EXISTE DÉJÀ ON L'AFFICHE
    
                                        $formulaire = file_get_contents($formulairejson);
                                        $formulairedecode = json_decode($formulaire);
    
                                        foreach($formulairedecode as $b){      
                                            $titrea  =    $b->titre;
                                            $subtitlea  = $b->subtitle;
                                            $auteura =    $b->auteur;
                                        }
    
                                        ?> 
                                            <div class="livres">
                                                <h1> <?php echo mysql_real_escape_string($auteura) ?></h1>
                                                <h2> <?php echo mysql_real_escape_string($titrea)  ?></h2>
                                                <i><h3 id="test"><?php echo mysql_real_escape_string($subtitlea) ?></h3></i>
                                            </div>
                                        <?php
                                            
//                                            unlink( $formulairejson ) ;
    
                                      
    // SINON ON AFFICHE LE FORMULAIRE POUR CRÉER LE FICHIER 
    
                                    }
                                        else{
                                            ?>
                                        <div class="bloc" id="formlivres">
                                            <h1 id="introuvable">Livre Introuvable. Rentre ton livre manuellement</h1>
                                        <div class="content">
                                        <form action="membre.php" method="post">
                                            <h2>Auteur</h2> <input type="text" name="auteur" value="<?php if (isset($_POST['auteur'])) 
                                                echo htmlentities(trim($_POST['auteur'])); ?>"><br />
                                            <h2>Titre</h2> <input type="text" name="titre" value="<?php if (isset($_POST['titre'])) 
                                                echo htmlentities(trim($_POST['titre'])); ?>"><br />
                                            <h2>Sous-titre</h2> <input type="text" name="subtitle" value="<?php if (isset($_POST['subtitle'])) 
                                                echo htmlentities(trim($_POST['subtitle'])); ?>"><br />
                                                <br /><br /><br />
                                            <input type="submit" name="Valider" value="Valider">
                                        </form>
                                        </div>
                                        </div>
                                        <?php
    
                                        $sqls = "SELECT count(*) FROM ".$table." WHERE titre='".mysql_escape_string($_POST['titre'])."'";
                                        $reqs = mysql_query($sqls) or die('Erreur SQL !<br />'.$sqls.'<br />'.mysql_error());
                                        $datas = mysql_fetch_array($reqs);
    
                                        if ($data[0] == 0) {
                                        $sqls = "INSERT INTO ".$table." VALUES('', 
                                        '".mysql_escape_string($_POST['titre'])."', 
                                        '".mysql_escape_string($_POST['subtitle'])."',
                                        '".mysql_escape_string($_POST['auteur'])."', 
                                        '')";
                                        mysql_query($sqls) or 
                                            die('Erreur SQL !'.$sqls.'<br />'.mysql_error());
    
                                            $c = array('titre' => mysql_escape_string($_POST['titre']), 
                                                       'auteur' => mysql_escape_string($_POST['auteur']),
                                                       'subtitle' => mysql_escape_string($_POST['subtitle']));
                                            
                                        $export = json_encode($c);
    
                                        file_put_contents($formulairejson, $export);
                                            
                                        }
    
                                        else {
                                            ?> <div class="bloc" 
                                                    id="formlivres"><h1 id="introuvable"> Tu as déjà scanné ce livre </h1></div> 
                                            <?php
                                        }

                                        }


if (isset($_POST['rechercher']) && $_POST['rechercher'] == 'Rechercher') {

$billy = "SELECT titre, auteur, subtitle, sub FROM ".$table." WHERE auteur='".mysql_escape_string($_POST['auteur'])."'";
$requete = mysql_query($billy) or die ('Erreur SQL !<br />'.$billy.'<br />'.mysql_error());

mysql_close($db_selected);
while($resultat = mysql_fetch_object($requete))
     {
        ?> 
        <div class="livres" id="recherche">
            <h1> <?php echo $resultat->auteur ?></h1>
            <h2> <?php echo $resultat->titre  ?></h2>
            <i><h3 id="test"><?php echo $resultat->subtitle ?></h3></i>
            <h4> <?php echo $resultat->sub    ?></h4>
        </div>
    <?php
     }
} 
    
// À LA FIN DU TRAITEMENT ON SUPPRIME LE CODE BARRE POUR ÉVITER LES DOUBLONS                                  

   if( file_exists ( $file))
     unlink( $file ) ;
    

?>

</body>
</html>