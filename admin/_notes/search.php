<?php require_once('../Connections/cms_blog.php'); ?>
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

$colname_rsSearch = "-1";
if (isset($_POST['txtsearch'])) {
  $colname_rsSearch = $_POST['txtsearch'];
}
mysql_select_db($database_cms_blog, $cms_blog);
$query_rsSearch = sprintf("SELECT komputer.nama, komputer.unit, komputer.kompname, komputer.ip, komputer.sn FROM komputer WHERE komputer.nama LIKE %s OR komputer.unit LIKE %s OR komputer.kompname LIKE %s OR komputer.ip LIKE %s OR komputer.sn LIKE %s ORDER BY komputer.nama ASC", GetSQLValueString("%" . $colname_rsSearch . "%", "text"),GetSQLValueString("%" . $colname_rsSearch . "%", "text"),GetSQLValueString("%" . $colname_rsSearch . "%", "text"),GetSQLValueString("%" . $colname_rsSearch . "%", "text"),GetSQLValueString("%" . $colname_rsSearch . "%", "text"));
$rsSearch = mysql_query($query_rsSearch, $cms_blog) or die(mysql_error());
$row_rsSearch = mysql_fetch_assoc($rsSearch);
$totalRows_rsSearch = mysql_num_rows($rsSearch);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Java Web Media</title>
<style type="text/css">
body {
	background-color: #666;
}
#container {
	background-color: #FFF;
	height: auto;
	width: 80%;
	margin: auto;
	padding: 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	border: solid thick #900;
	border-radius: 8px;
}
#container form {
	padding: 10px;
}
#container select, #container input {
	padding: 3px 5px;
	background-color: #FFC;
	border: solid thin #C00;
	border-radius: 3px;
}
#container label {
	display: block;
}
#container table {
	border: solid thin #333;
}
#container th {
	background-color: #CCC;
	color: #900;
	text-align: left;
}
#container th, #container td {
	padding: 5px 10px;
	border-bottom: solid thin #CCC;
}
</style>
</head>

<body>
<div id="container">
  <form name="form1" method="post" action="search.php">
    <h2>Form Pencarian:</h2>
    <p>
      <label for="txtsearch">Kata kunci (Nama, Serial Number, Unit):</label>
      <input type="text" name="txtsearch" id="txtsearch">
      <input type="submit" name="submit" id="submit" value="Submit">
      <input type="reset" name="submit2" id="submit2" value="Reset">
    </p>
  </form>
  <?php if(isset($_POST['txtsearch']) && ($_POST['txtsearch']=="")) { ?>
   <h2>Hasil Pencarian:</h2>
  <p>Ooopss.... Anda belum memasukkan kata kunci.</p>
  <?php }elseif(isset($_POST['txtsearch']) && ($row_rsSearch['nama']=="") && ($row_rsSearch['unit']=="") && ($row_rsSearch['kompname']=="") && ($row_rsSearch['ip']=="") && ($row_rsSearch['sn']=="")) { ?>
  <h2>Hasil Pencarian:</h2>
    <p>Kata kunci yang Anda masukkan tidak tersimpan dalam database kami.</p>
<?php }elseif(isset($_POST['txtsearch'])) {
  $colname_rsSearch = $_POST['txtsearch']; ?>
<h2>Hasil Pencarian:</h2>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th scope="col">Nama</th>
      <th scope="col">Unit</th>
      <th scope="col">Komp Name</th>
      <th scope="col">Serial Number</th>
    </tr>
    <?php do { ?>
  <tr>
    <td><?php echo $row_rsSearch['nama']; ?></td>
    <td><?php echo $row_rsSearch['unit']; ?></td>
    <td><?php echo $row_rsSearch['kompname']; ?></td>
    <td><?php echo $row_rsSearch['sn']; ?></td>
  </tr>
  <?php } while ($row_rsSearch = mysql_fetch_assoc($rsSearch)); ?>
    <tr>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </table>
<?php }else{ } ?>
</div>
</body>
</html>
<?php
mysql_free_result($rsSearch);
?>
