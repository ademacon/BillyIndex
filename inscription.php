<?php
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
	// on teste l'existence de nos variables. On teste également si elles ne sont pas vides
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']))) {
	// on teste les deux mots de passe
	if ($_POST['pass'] != $_POST['pass_confirm']) {
		$erreur = 'Les 2 mots de passe sont différents.';
	}
	else {
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

		// on recherche si ce login est déjà utilisé par un autre membre
		$sql = 'SELECT count(*) FROM membre WHERE login="'.mysql_escape_string($_POST['login']).'"';
		$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		$data = mysql_fetch_array($req);

		if ($data[0] == 0) {
		$sql = 'INSERT INTO membre VALUES("", "'.mysql_escape_string($_POST['login']).'", "'.mysql_escape_string(md5($_POST['pass'])).'")';
		mysql_query($sql) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());

		session_start(); 
		$_SESSION['login'] = $_POST['login'];
		header('Location: membre.php');
		exit();
		}
		else {
		$erreur = 'Un membre possède déjà ce login.';
		}
	}
	}
	else {
	$erreur = 'Au moins un des champs est vide.';
	}
}
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<title>Inscription</title>
</head>

<body>
<a href="index.php" class="logo" ><img src="img/BILLYINDEX2.png" width="200px" height="auto"></a>
    <div class="bloc">
        <div class="content">
<h1 id="connect">Inscription</h1>
<form action="inscription.php" method="post">
<h2>Login</h2> <input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"><br />
<h2>Mot de passe</h2> <input type="password" name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"><br />
<h2>Confirmation</h2> <input type="password" name="pass_confirm" value="<?php if (isset($_POST['pass_confirm'])) echo htmlentities(trim($_POST['pass_confirm'])); ?>"><br /><br /><br />
<input type="submit" name="inscription" value="Inscription">
</form>
        </div>
<h3>
<?php
if (isset($erreur)) echo '<br />',$erreur;
?>
</h3>
    </div>
</body>
</html>