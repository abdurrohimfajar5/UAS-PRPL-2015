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

$maxRows_rsDetail = 5;
$pageNum_rsDetail = 0;
if (isset($_GET['pageNum_rsDetail'])) {
  $pageNum_rsDetail = $_GET['pageNum_rsDetail'];
}
$startRow_rsDetail = $pageNum_rsDetail * $maxRows_rsDetail;

mysql_select_db($database_cms_blog, $cms_blog);
$query_rsDetail = "SELECT post.ID, post.title, post.category, post.content, post.updated, LEFT(post.content, 100) AS ringkasan FROM post ORDER BY ID DESC";
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
<title>Java Web Media</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="shorcut icon" href="images/jwm-logo-icon.jpg" />
</head>

<body>
<div id="container">
  <div id="header"></div>
    <!--ini adalah start navigasi-->
    <div class="nav">
    	<a href="index.html">Home</a>
        <a href="http://www.javawebmedia.com/news.php">News</a>
        <a href="http://www.javawebmedia.com/course.php">Course</a>
      <a href="http://www.javawebmedia.com/contact.php">Contact Us</a>
  </div>
    <!-- ini adalah ending navigasi-->
    <div class="content">
    
      <div class="post">
        <?php do { ?>
        <h2><a href="news.php?view=<?php echo $row_rsDetail['ID']; ?>"><?php echo $row_rsDetail['title']; ?></a></h2>
 <?php 
 // Memotong satu paragraph utuh
 echo substr($row_rsDetail['content'], 0, strpos($row_rsDetail['content'], PHP_EOL)); 
 ?>  
 -  <a href="news.php?view=<?php echo $row_rsDetail['ID']; ?>">Read more...</a><hr>
 
 <?php 
 // Mencetak string
 echo nl2br($row_rsDetail['ringkasan']); 
 ?>  
 -  <a href="news.php?view=<?php echo $row_rsDetail['ID']; ?>">Read more...</a><hr>
  <?php 
 // Mencetak string
 $text = nl2br($row_rsDetail['ringkasan']); 
 $kata_lengkap = strrpos($text, ' ');
 //Mencetak dan menambahkan ...
 echo substr($text, 0, $kata_lengkap)."...";
 ?>  
 -  <a href="news.php?view=<?php echo $row_rsDetail['ID']; ?>">Read moree...</a><hr>
 
  <?php } while ($row_rsDetail = mysql_fetch_assoc($rsDetail)); ?>
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
	<div id="footer"><a href="index.php">Home</a> | <a href="http://www.javawebmedia.com">About Us</a> | <a href="admin/index.php">Admin</a> | <a href="admin/login.php">Login</a> | <a href="http://www.javawebmedia.com/contact.php">Contact Us</a></div>
</div>
</body>
</html>
<?php
mysql_free_result($rsNews);

mysql_free_result($rsDetail);
?>
