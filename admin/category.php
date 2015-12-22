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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="category.php";
  $loginUsername = $_POST['category'];
  $LoginRS__query = sprintf("SELECT category FROM category WHERE category=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_cms_blog, $cms_blog);
  $LoginRS=mysql_query($LoginRS__query, $cms_blog) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO category (category) VALUES (%s)",
                       GetSQLValueString($_POST['category'], "text"));

  mysql_select_db($database_cms_blog, $cms_blog);
  $Result1 = mysql_query($insertSQL, $cms_blog) or die(mysql_error());

  $insertGoTo = "category.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE category SET category=%s WHERE ID=%s",
                       GetSQLValueString($_POST['category2'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_cms_blog, $cms_blog);
  $Result1 = mysql_query($updateSQL, $cms_blog) or die(mysql_error());

  $updateGoTo = "category.php";
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

  $deleteGoTo = "category.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_cms_blog, $cms_blog);
$query_Recordset1 = "SELECT * FROM category ORDER BY category ASC";
$Recordset1 = mysql_query($query_Recordset1, $cms_blog) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$colname_rsEdit = "-1";
if (isset($_GET['edit'])) {
  $colname_rsEdit = $_GET['edit'];
}
mysql_select_db($database_cms_blog, $cms_blog);
$query_rsEdit = sprintf("SELECT * FROM category WHERE ID = %s", GetSQLValueString($colname_rsEdit, "int"));
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
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="container">
  <div id="header">
  <center>BERITA SEPUTAR</center>
  </div>
    <!--ini adalah start navigasi-->
    <div class="nav">
    	HALAMAN KATEGORI
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
        <form name="form2" method="POST" action="<?php echo $editFormAction; ?>">
        <span id="sprytextfield2">
        <label for="category2">Edit Kategori</label>
        <input name="category2" type="text" id="category2" value="<?php echo $row_rsEdit['category']; ?>">
        <input name="ID" type="hidden" id="ID" value="<?php echo $row_rsEdit['ID']; ?>">
        <span class="textfieldRequiredMsg">A value is required.</span></span>
        <input type="submit" name="submit3" id="submit3" value="Ubah">
        <input type="reset" name="submit4" id="submit4" value="Reset">
        <input type="hidden" name="MM_update" value="form2">
      </form>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data-table">
        <tr>
          <td colspan="2" scope="col">
          <?php if(isset($_GET['requsername'])) { ?>
          <p style="color:red">Oopss..., kategori: <strong><?php echo $_GET['requsername']; ?></strong> sudah tersimpan dalam database. Masukkan kategori yang berbeda.</p>
          <?php } ?>
          <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
            <span id="sprytextfield1">
            <label for="category">Tambah Kategori Baru :</label>
            <input type="text" name="category" id="category">
            <span class="textfieldRequiredMsg">A value is required.</span></span>
            <input type="submit" name="submit" id="submit" value="Tambah">
            <input type="reset" name="submit2" id="submit2" value="Reset">
            <input type="hidden" name="MM_insert" value="form1">
          </form></td>
        </tr>
        <tr>
          <th width="70%" scope="col">Nama Kategori</th>
          <th width="30%" scope="col">Perintah</th>
        </tr>
        <?php do { ?>
          <tr>
            <td width="70%"><?php echo $row_Recordset1['category']; ?></td>
            <td width="30%"><a href="category.php?edit=<?php echo $row_Recordset1['ID']; ?>">Edit</a> | <a href="category.php?delete=<?php echo $row_Recordset1['ID']; ?>">Delete</a></td>
          </tr>
          <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
<tr>
          <th colspan="2">&nbsp;</th>
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
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($rsEdit);
?>
