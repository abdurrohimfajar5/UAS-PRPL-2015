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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsUser = 5;
$pageNum_rsUser = 0;
if (isset($_GET['pageNum_rsUser'])) {
  $pageNum_rsUser = $_GET['pageNum_rsUser'];
}
$startRow_rsUser = $pageNum_rsUser * $maxRows_rsUser;

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsUser = "SELECT * FROM users ORDER BY name ASC";
$query_limit_rsUser = sprintf("%s LIMIT %d, %d", $query_rsUser, $startRow_rsUser, $maxRows_rsUser);
$rsUser = mysql_query($query_limit_rsUser, $cms_blog) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);

if (isset($_GET['totalRows_rsUser'])) {
  $totalRows_rsUser = $_GET['totalRows_rsUser'];
} else {
  $all_rsUser = mysql_query($query_rsUser);
  $totalRows_rsUser = mysql_num_rows($all_rsUser);
}
?>
<?php
$totalPages_rsUser = ceil($totalRows_rsUser/$maxRows_rsUser)-1;$maxRows_rsUser = 5;
$pageNum_rsUser = 0;
if (isset($_GET['pageNum_rsUser'])) {
  $pageNum_rsUser = $_GET['pageNum_rsUser'];
}
$startRow_rsUser = $pageNum_rsUser * $maxRows_rsUser;

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsUser = "SELECT * FROM users ORDER BY name ASC";
$query_limit_rsUser = sprintf("%s LIMIT %d, %d", $query_rsUser, $startRow_rsUser, $maxRows_rsUser);
$rsUser = mysql_query($query_limit_rsUser, $cms_blog) or die(mysql_error());
$row_rsUser = mysql_fetch_assoc($rsUser);

if (isset($_GET['totalRows_rsUser'])) {
  $totalRows_rsUser = $_GET['totalRows_rsUser'];
} else {
  $all_rsUser = mysql_query($query_rsUser);
  $totalRows_rsUser = mysql_num_rows($all_rsUser);
}
$totalPages_rsUser = ceil($totalRows_rsUser/$maxRows_rsUser)-1;

$queryString_rsUser = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsUser") == false && 
        stristr($param, "totalRows_rsUser") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsUser = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsUser = sprintf("&totalRows_rsUser=%d%s", $totalRows_rsUser, $queryString_rsUser);
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
    	DAFTAR ADMIN
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
      <h2>&nbsp;</h2>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data-table">
        <tr>
          <th width="50%" scope="col">Nama Admin &amp; Username</th>
          <th width="25%" scope="col">Password</th>
          <th width="25%" scope="col">Perintah</th>
        </tr>
        <?php do { ?>
          <tr>
            <td width="50%"><?php echo $row_rsUser['name']; ?></td>
            <td width="25%"><?php echo $row_rsUser['password']; ?></td>
            <td width="25%"><a href="edit-admin.php?edit=<?php echo $row_rsUser['ID']; ?>">Edit</a> | <a href="add-admin.php?delete=<?php echo $row_rsUser['ID']; ?>">Delete</a></td>
          </tr>
          <?php } while ($row_rsUser = mysql_fetch_assoc($rsUser)); ?>
<tr>
          <th colspan="3"> <table border="0">
            <tr>
              <td><?php if ($pageNum_rsUser > 0) { // Show if not first page ?>
              Pertama
                    <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_rsUser > 0) { // Show if not first page ?>
              Sebelumnya
                    <?php } // Show if not first page ?></td>
              <td><?php if ($pageNum_rsUser < $totalPages_rsUser) { // Show if not last page ?>
              Selanjutnya
                    <?php } // Show if not last page ?></td>
              <td><?php if ($pageNum_rsUser < $totalPages_rsUser) { // Show if not last page ?>
              Terakhir
                    <?php } // Show if not last page ?></td>
            </tr>
          </table></th>
        </tr>
      </table>
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
mysql_free_result($rsUser);
?>
