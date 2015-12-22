<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "akses_level";
  $MM_redirectLoginSuccess = "admin.php";
  $MM_redirectLoginFailed = "akses.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_koneksi, $koneksi);
  	
  $LoginRS__query=sprintf("SELECT username, password, akses_level FROM akses_level WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $koneksi) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'akses_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Akses Level</title>
<style type="text/css">
body {
	background-color: #000;
}
#wrapper {
	background-color: #FFF;
	width: 80%;
	padding: 30px;
	font-family: Arial, Helvetica, sans-serif;
	height:auto;
	margin: auto;
}
form {
	padding: 20px;
	background-color: #E7E7E7;
	border: solid thin #7B7B7B;
	border-radius: 5px;
}
label {
	display: block;
}
input {
	padding: 5px 10px;
}
h1 {
	padding-bottom: 5px;
	border-bottom: solid 1px #BCBCBC;
}
</style>
</head>

<body>
<div id="wrapper">
  <h1>Halaman login</h1>
  <form name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
    <p>
      <label for="username">Username:</label>
      <input type="text" name="username" id="username">
    </p>
    <p>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password">
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Masuk">
      <input type="reset" name="submit2" id="submit2" value="Batal">
    </p>
  </form>
  <p>&nbsp;</p>
</div>
</body>
</html>