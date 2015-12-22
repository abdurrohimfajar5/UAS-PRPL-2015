<?php require_once('Connections/koneksi.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "akses.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Super Admin,Admin,Super User,User";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "akses.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$colname_rsSession = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsSession = $_SESSION['MM_Username'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_Session = sprintf("SELECT * FROM akses_level WHERE username = %s", GetSQLValueString($colname_Session, "text"));
$Session = mysql_query($query_Session, $koneksi) or die(mysql_error());
$row_Session = mysql_fetch_assoc($Session);
$totalRows_Session = mysql_num_rows($Session);
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
  <h1>Hai <a href="#"><?php echo $row_Session['username']; ?></a>, Anda adalah <a href="#"><?php echo $row_Session['akses_level']; ?></a> | <a href="<?php echo $logoutAction ?>">Log Out</a></h1>
  <?php
  // Jika dia berstatus Super Admin
  if(isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup'] == "Super Admin")) { 
  ?>
  <h2>Anda adalah Super Admin</h2>
   <?php
  // Jika dia berstatus Admin
  }elseif(isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup'] == "Admin")) { 
  ?>
  <h3>Anda adalah Admin</h3>
    <?php
  // Jika dia berstatus Super User
  }elseif(isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup'] == "Super User")) { 
  ?>
  <h4>Anda adalah Super User</h4>
    <?php
  // Jika dia berstatus User
  }elseif(isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup'] == "User")) { 
  ?>
  <h5>Anda adalah User</h5>
  <?php }else{ ?>
  <p>Anda tidak memiliki akses level</p>
  <?php } ?>
</div>
</body>
</html>
<?php
mysql_free_result($Session);
?>
