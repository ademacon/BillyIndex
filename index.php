<?php
// on teste si le visiteur a soumis le formulaire de connexion
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {

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

	// on teste si une entrée de la base contient ce couple login / pass
	$sql = 'SELECT count(*) FROM membre WHERE login="'.mysql_escape_string($_POST['login']).'" 
    AND pass_md5="'.mysql_escape_string(md5($_POST['pass'])).'"';
	$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
	$data = mysql_fetch_array($req);

	mysql_free_result($req);
	mysql_close();

	// si on obtient une réponse, alors l'utilisateur est un membre
	if ($data[0] == 1) {
		session_start();
		$_SESSION['login'] = $_POST['login'];
		header('Location: membre.php');
		exit();
	}
	// si on ne trouve aucune réponse, le visiteur s'est trompé soit dans son login, soit dans son mot de passe
	elseif ($data[0] == 0) {
		$erreur = 'Compte non reconnu';
	}
	// sinon, alors la, il y a un gros problème :)
	else {
		$erreur = 'Probème dans la base de données : plusieurs membres ont les mêmes identifiants de connexion';
	}
	}
	else {
	$erreur = 'Au moins un des champs est vide';
	}
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.php"/>
<title>Accueil</title>
</head>

<body>     
<a href="index.php" class="logo" ><img src="img/BILLYINDEX2.png" width="200px" height="auto"></a>
    <div class="bloc">
        <div class="content">
<h1 id="connect">Connexion</h1>
<form action="index.php" method="post">
<h2>Login</h2> <input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>">
<h2>Mot de passe</h2> <input type="password" name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"><br /><br /><br />
<input type="submit" name="connexion" value="Connexion"><br /><br />
</form>
        </div>
                <div class="inscire">
<a id="inscription" href="inscription.php">Vous inscrire</a>
                </div>
<h3>
<?php
if (isset($erreur)) echo '<br />',$erreur; 
?> 
</h3>         
    </div>
</body>
</html>