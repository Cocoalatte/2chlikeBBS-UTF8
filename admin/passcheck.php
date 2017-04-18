<?php
#=====================================
#　パスワード設定
#=====================================
#エラー画面（エラー処理）
#DispError(TITLE,TOPIC);
function disperror($title, $topic) {
	?>
<html>
<head>
<title><?=$title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<font color="red" size="+1"><b><?=$title?></b></font><br>
<br>
<div align="left"><b><?=$topic?></b></div><br>
<br>
</body>
</html>
<?php
	exit;
}
header("Content-Type: text/html; charset=UTF-8");
$passfile = "passfile.cgi";
$admin_array = @file($passfile);
if (!isset($admin_array[0])) $admin_array[0] = '';
$admin = rtrim($admin_array[0]);
if (!isset($_COOKIE['adminpass'])) $_COOKIE['adminpass'] = '';
if (!isset($_POST['adminpass'])) $_POST['adminpass'] = '';
if ($admin) {
	if (!$_COOKIE['adminpass'] and !$_POST['adminpass']) {
		?>
<html>
<head>
<title>パスワード認証</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<br><br>
<div align="center">
管理パスワードを入力してください。<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="password" name="adminpass" >
<input type="submit" value="送信">
</form>
</div>
</body></html>
<?php
		exit;
	}
	if ($_COOKIE['adminpass']) {
		if (crypt($_COOKIE['adminpass'], $admin) != $admin) {
			disperror("ＥＲＲＯＲ！", "パスワードが違います");
			exit;
		}
	}
	if (!$_COOKIE['adminpass'] and $_POST['adminpass']) {
		if (crypt($_POST['adminpass'], $admin) != $admin) {
			disperror("ＥＲＲＯＲ！", "パスワードが違います");
			exit;
		}
		setcookie("adminpass",$_POST['adminpass']);
	}
}
else {
	if(!isset($_POST['setpass']) or !$_POST['setpass']) {
		?>
<html>
<head>
<title>パスワード設定</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<br><br>
<div align="center">
パスワードが登録されていません。<br>
新しいパスワードを入力してください。<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="password" name="setpass" >
<input type="submit" value="登録">
</form>
</div>
</body></html>
<?php
		exit;
	}
	else {
		$admin = crypt($_POST['setpass']);
		$fp = @fopen($passfile, "w");
		fputs($fp, $admin);
		fclose($fp);
		setcookie("adminpass",$_POST['setpass']);
	}
}
?>