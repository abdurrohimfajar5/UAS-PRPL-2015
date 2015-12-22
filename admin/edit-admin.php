<?php require_once('../Connections/cms_blog.php'); ?>
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
	
  $logoutGoTo = "login.php";
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
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET name=%s, username=%s, password=%s WHERE ID=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_cms_blog, $cms_blog);
  $Result1 = mysql_query($updateSQL, $cms_blog) or die(mysql_error());

  $updateGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM category WHERE ID=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_cms_blog, $cms_blog);
  $Result1 = mysql_query($deleteSQL, $cms_blog) or die(mysql_error());

  $deleteGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_GET['edit'])) {
  $colname_Recordset1 = $_GET['edit'];
}
mysql_select_db($database_cms_blog, $cms_blog);
$query_Recordset1 = sprintf("SELECT * FROM users WHERE ID = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $cms_blog) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<html>
<head>
<title>Website ISC</title>
<link href="../css/style.css" rel="stylesheet" type="text/css">
<link rel="shorcut icon" href="../images/jwm-logo-icon.jpg" />
</head>

<body>
<div id="container">
  <div id="header">
  <center>BERITA SEPUTAR</center>
  </div>
    <!--ini adalah start navigasi-->
    <div class="nav">
    	EDIT ADMIN
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
      <h2>&nbsp;</h2>
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
        <table align="center">
          <tr valign="baseline">
            <td nowrap align="right">Nama:</td>
            <td><input type="text" name="name" value="<?php echo htmlentities($row_Recordset1['name'], ENT_COMPAT, ''); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Username:</td>
            <td><input type="text" name="username" value="<?php echo htmlentities($row_Recordset1['username'], ENT_COMPAT, ''); ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">Password:</td>
            <td><input type="password" name="password" value="<?php echo $row_Recordset1['password']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline">
            <td nowrap align="right">&nbsp;</td>
            <td><input type="submit" value="Edit admin"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="ID" value="<?php echo $row_Recordset1['ID']; ?>">
      </form>
      <p>&nbsp;</p>
      </div>
      
      <div id="sidebar">
      <h2>Halaman Admin</h2>
      <p><a href="index.php"><img src="../images/house.png" width="16" height="16"> Dashboard</a>      </p>
      <p><a href="add-news.php"><img src="../images/newspaper_add.png" width="16" height="16"> Tambah Berita</a></p>
      <p><a href="category.php"><img src="../images/tag_blue.png" width="16" height="16"> Kategori</a></p>
      <p><a href="admin.php"><img src="../images/user.png" width="16" height="16"> Admin</a></p>
      <p><a href="add-admin.php"><img src="../images/user_add.png" width="16" height="16"> Tambah Admin</a></p>
      <p><a href="../index.php"><img src="../images/house_go.png" width="16" height="16"> Homepage</a></p>
      <p><a href="<?php echo $logoutAction ?>"><img src="../images/delete.png" width="16" height="16"> Logout</a></p>
<p>&nbsp;</p>
      
      </div>
      
    </div>
	<div id="footer"> Copyright@2015 Design By Abdurrohim Fajar 
</div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
