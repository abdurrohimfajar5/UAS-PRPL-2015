<?php require_once('Connections/cms_blog.php'); ?>
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

$colname_rsView = "-1";
if (isset($_GET['view'])) {
  $colname_rsView = $_GET['view'];
}
mysql_select_db($database_cms_blog, $cms_blog);
$query_rsView = sprintf("SELECT * FROM post WHERE ID = %s", GetSQLValueString($colname_rsView, "int"));
$rsView = mysql_query($query_rsView, $cms_blog) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);
$totalRows_rsView = mysql_num_rows($rsView);

$maxRows_rsNews = 10;
$pageNum_rsNews = 0;
if (isset($_GET['pageNum_rsNews'])) {
  $pageNum_rsNews = $_GET['pageNum_rsNews'];
}
$startRow_rsNews = $pageNum_rsNews * $maxRows_rsNews;

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsNews = "SELECT * FROM post ORDER BY ID DESC";
$query_limit_rsNews = sprintf("%s LIMIT %d, %d", $query_rsNews, $startRow_rsNews, $maxRows_rsNews);
$rsNews = mysql_query($query_limit_rsNews, $cms_blog) or die(mysql_error());
$row_rsNews = mysql_fetch_assoc($rsNews);

if (isset($_GET['totalRows_rsNews'])) {
  $totalRows_rsNews = $_GET['totalRows_rsNews'];
} else {
  $all_rsNews = mysql_query($query_rsNews);
  $totalRows_rsNews = mysql_num_rows($all_rsNews);
}
$totalPages_rsNews = ceil($totalRows_rsNews/$maxRows_rsNews)-1;

$maxRows_rsDetail = 10;
$pageNum_rsDetail = 0;
if (isset($_GET['pageNum_rsDetail'])) {
  $pageNum_rsDetail = $_GET['pageNum_rsDetail'];
}
$startRow_rsDetail = $pageNum_rsDetail * $maxRows_rsDetail;

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsDetail = "SELECT * FROM post ORDER BY ID DESC";
$query_limit_rsDetail = sprintf("%s LIMIT %d, %d", $query_rsDetail, $startRow_rsDetail, $maxRows_rsDetail);
$rsDetail = mysql_query($query_limit_rsDetail, $cms_blog) or die(mysql_error());
$row_rsDetail = mysql_fetch_assoc($rsDetail);

if (isset($_GET['totalRows_rsDetail'])) {
  $totalRows_rsDetail = $_GET['totalRows_rsDetail'];
} else {
  $all_rsDetail = mysql_query($query_rsDetail);
  $totalRows_rsDetail = mysql_num_rows($all_rsDetail);
}
$totalPages_rsDetail = ceil($totalRows_rsDetail/$maxRows_rsDetail)-1;
?>
<html>
<head>
<title>Website ISC</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="shorcut icon" href="images/jwm-logo-icon.jpg" />
</head>

<body>
<div id="container">
  <div id="header">
  <center>BERITA SEPUTAR</center>
  </div>
    <!--ini adalah start navigasi-->
    <div class="nav">
    	<marquee>Selamat Datang di Website Kami</marquee>
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
      <?php 
	  // Menampilkan detail berita yang dipilih
	  if(isset($_GET['view']) && ($_GET['view']==$row_rsView['ID'])) {
	  ?>
      <h2><?php echo $row_rsView['title']; ?></h2>
<p><?php echo $row_rsView['content']; ?></p>

	  <?php
	  // Jika tidak memilih item berita, tampilkan daftar berita
	  }else{
	  ?>
      <?php do { ?>
        <h1><a href="news.php?view=<?php echo $row_rsDetail['ID']; ?>"><?php echo $row_rsDetail['title']; ?></a></h1>
        <?php echo $row_rsDetail['content']; ?>
        <?php } while ($row_rsDetail = mysql_fetch_assoc($rsDetail)); ?>
<?php
	  // End if ada di sini
	  }
	  ?>

      </div>
      
      <div id="sidebar">
      <h2>Berita terbaru:</h2>
      <ul>
        <?php do { ?>
          <li><a href="news.php?view=<?php echo $row_rsNews['ID']; ?>"><?php echo $row_rsNews['title']; ?></a></li>
          <?php } while ($row_rsNews = mysql_fetch_assoc($rsNews)); ?>
      </ul>
<p>&nbsp;</p>
      
      </div>
      
    </div>
	<div id="footer"> Copyright@2015 Design By Abdurrohim Fajar | <a href="admin/login.php">ADMIN</a>
</div>
</body>
</html>
<?php
mysql_free_result($rsView);

mysql_free_result($rsNews);

mysql_free_result($rsDetail);
?>
