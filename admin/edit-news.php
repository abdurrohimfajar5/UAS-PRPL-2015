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
  $updateSQL = sprintf("UPDATE post SET title=%s, category=%s, content=%s WHERE ID=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['category'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_cms_blog, $cms_blog);
  $Result1 = mysql_query($updateSQL, $cms_blog) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsCategory = "SELECT * FROM category ORDER BY category ASC";
$rsCategory = mysql_query($query_rsCategory, $cms_blog) or die(mysql_error());
$row_rsCategory = mysql_fetch_assoc($rsCategory);
$totalRows_rsCategory = mysql_num_rows($rsCategory);

$colname_rsEdit = "-1";
if (isset($_GET['edit'])) {
  $colname_rsEdit = $_GET['edit'];
}
mysql_select_db($database_cms_blog, $cms_blog);
$query_rsEdit = sprintf("SELECT * FROM post WHERE ID = %s ORDER BY updated DESC", GetSQLValueString($colname_rsEdit, "int"));
$rsEdit = mysql_query($query_rsEdit, $cms_blog) or die(mysql_error());
$row_rsEdit = mysql_fetch_assoc($rsEdit);
$totalRows_rsEdit = mysql_num_rows($rsEdit);
?>
<html>
<head>
<title>Website ISC</title>
<link href="../css/style.css" rel="stylesheet" type="text/css">
<link rel="shorcut icon" href="../images/jwm-logo-icon.jpg" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="container">
  <div id="header">
  <center>BERITA SEPUTAR</center>
  </div>
    <!--ini adalah start navigasi-->
    <div class="nav">
    	EDIT BERITA
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
        <form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
        <span id="sprytextfield1">
        <label for="title">Judul berita:</label>
        <input name="title" type="text" id="title" value="<?php echo $row_rsEdit['title']; ?>" size="50">
        <span class="textfieldRequiredMsg">A value is required.</span></span>
        <p><span id="spryselect1">
          <label for="category">Kategori berita:</label>
          <select name="category" id="category">
            <option value="" <?php if (!(strcmp("", $row_rsEdit['category']))) {echo "selected=\"selected\"";} ?>>Pilih kategori...</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsCategory['category']?>"<?php if (!(strcmp($row_rsCategory['category'], $row_rsEdit['category']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCategory['category']?></option>
            <?php
} while ($row_rsCategory = mysql_fetch_assoc($rsCategory));
  $rows = mysql_num_rows($rsCategory);
  if($rows > 0) {
      mysql_data_seek($rsCategory, 0);
	  $row_rsCategory = mysql_fetch_assoc($rsCategory);
  }
?>
          </select>
          <span class="selectRequiredMsg">Please select an item.</span></span></p>
        <p><span id="sprytextarea1">
          <label for="content">Isi berita:</label>
          <textarea name="content" id="content" cols="60" rows="10"><?php echo $row_rsEdit['content']; ?></textarea>
        <span class="textareaRequiredMsg">A value is required.</span></span></p>
        <p>
          <input type="submit" name="submit" id="submit" value="Ubah">
          <input type="reset" name="submit2" id="submit2" value="Reset">
          <input name="ID" type="hidden" id="ID" value="<?php echo $row_rsEdit['ID']; ?>">
        </p>
        <input type="hidden" name="MM_update" value="form1">
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
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
</script>
</body>
</html>
<?php
mysql_free_result($rsCategory);

mysql_free_result($rsEdit);
?>
