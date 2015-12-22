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

$maxRows_rsBerita = 10;
$pageNum_rsBerita = 0;
if (isset($_GET['pageNum_rsBerita'])) {
  $pageNum_rsBerita = $_GET['pageNum_rsBerita'];
}
$startRow_rsBerita = $pageNum_rsBerita * $maxRows_rsBerita;

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsBerita = "SELECT * FROM post ORDER BY updated DESC";
$query_limit_rsBerita = sprintf("%s LIMIT %d, %d", $query_rsBerita, $startRow_rsBerita, $maxRows_rsBerita);
$rsBerita = mysql_query($query_limit_rsBerita, $cms_blog) or die(mysql_error());
$row_rsBerita = mysql_fetch_assoc($rsBerita);

if (isset($_GET['totalRows_rsBerita'])) {
  $totalRows_rsBerita = $_GET['totalRows_rsBerita'];
} else {
  $all_rsBerita = mysql_query($query_rsBerita);
  $totalRows_rsBerita = mysql_num_rows($all_rsBerita);
}
$totalPages_rsBerita = ceil($totalRows_rsBerita/$maxRows_rsBerita)-1;

$queryString_rsBerita = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsBerita") == false && 
        stristr($param, "totalRows_rsBerita") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsBerita = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsBerita = sprintf("&totalRows_rsBerita=%d%s", $totalRows_rsBerita, $queryString_rsBerita);
?>
<html>
<head>
<title>Website ISC</title>
<link href="../css/style.css" rel="stylesheet" type="text/css">
<link rel="shorcut icon" href="../images/jwm-logo-icon.jpg" />
</head>

<body>
<div id="container">
  <div id="header"></div>
    <!--ini adalah start navigasi-->
    <div class="nav">
    	HALAMAN BERITA
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
      <h2>&nbsp;</h2>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data-table">
        <tr>
          <th width="50%" scope="col">Berita Baru</th>
          <th width="25%" scope="col">Updated</th>
          <th width="25%" scope="col">Perintah</th>
        </tr>
        <?php do { ?>
  <tr>
    <td width="50%"><a href="../news.php?view=<?php echo $row_rsBerita['ID']; ?>"><?php echo $row_rsBerita['title']; ?></a></td>
    <td width="25%"><?php echo $row_rsBerita['updated']; ?></td>
    <td width="25%"><a href="edit-news.php?edit=<?php echo $row_rsBerita['ID']; ?>">Edit</a> | <a href="add-news.php?delete=<?php echo $row_rsBerita['ID']; ?>">Delete</a></td>
  </tr>
  <?php } while ($row_rsBerita = mysql_fetch_assoc($rsBerita)); ?>
        <tr>
          <th colspan="3">&nbsp;
            <table border="0">
              <tr>
                <td><?php if ($pageNum_rsBerita > 0) { // Show if not first page ?>
                Pertama
                      <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_rsBerita > 0) { // Show if not first page ?>
                Sebelumnya
                      <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_rsBerita < $totalPages_rsBerita) { // Show if not last page ?>
                Selanjutnya
                      <?php } // Show if not last page ?></td>
                <td><?php if ($pageNum_rsBerita < $totalPages_rsBerita) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_rsBerita=%d%s", $currentPage, $totalPages_rsBerita, $queryString_rsBerita); ?>">Terakhir</a>
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
	<?php require_once("../controller.php"); $isc->footer();?>
</div>
</body>
</html>
<?php
mysql_free_result($rsBerita);
?>
