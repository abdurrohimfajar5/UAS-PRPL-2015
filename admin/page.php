<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php if(isset($_GET['page']) && ($_GET['page']=="profile")) { echo "Profile: Java Web Media | www.javawebmedia.com"; }elseif(isset($_GET['page']) && ($_GET['page']=="contact")) { echo "Contact Us: Java Web Media | www.javawebmedia.com"; }else{ echo "www.javawebmedia.com"; } ?></title>
<style type="text/css">
body {
	background-color: #900;
}
.container {
	width: 60%;
	padding: 20px;
	background-color: #FFF;
	margin: auto;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
}
.container h2 {
	border-bottom: solid thin #F90;
	color: #F90;
	padding-bottom: 5px;
}
#navigasi {
	width: 100%;
	height: 30px;
	background-color: #900;
	float: left;
	margin-bottom: 10px;
	color: #FFF;
}
#navigasi a, #navigasi a:visited {
	padding: 5px 10px;
	height: 20px;
	text-align: center;
	vertical-align: middle;
	text-decoration: none;
	display: inline-block;
	color: #FFF;
	font-weight: bold;
	border-right: solid thin #CCC;
}
#navigasi a:hover {
	text-decoration: none;
	background-colo: #003;
}
</style>
</head>

<body>
<div class="container">
<div id="navigasi"><a href="page.php">Home</a><a href="page.php?page=profile">Profile</a><a href="page.php?page=contact">Contact Us</a></div>

<?php if(isset($_GET['page']) && ($_GET['page']=="profile")) { ?>
  <h2>This is profile page</h2>
  <p>Atque corrupti quos dolores et quas molestias id est laborum et dolorum fuga. Nam libero tempore, at vero eos et accusamus et iusto odio saepe eveniet ut et voluptates repudiandae sint. Itaque earum rerum hic tenetur a sapiente delectus, temporibus autem quibusdam eaque ipsa quae ab illo inventore veritatis. Cum soluta nobis est eligendi optio totam rem aperiam, quia voluptas sit aspernatur.</p>
  <p>Et quasi architecto beatae vitae nam libero tempore, itaque earum rerum hic tenetur a sapiente delectus. Praesentium voluptatum deleniti sed quia non numquam eius modi qui ratione voluptatem sequi nesciunt. Et molestiae non recusandae. Qui in ea voluptate sed ut perspiciatis unde omnis nam libero tempore.</p>
  
  <?php }elseif(isset($_GET['page']) && ($_GET['page']=="contact")) { ?>
  <h2>This is contact us page</h2>
  <p>Love's not time's fool, though rosy lips and cheeks admit impediments; love is not love it is the star to every wand'ring bark. Within his bending sickle's compass come; which alters when it alteration finds, whose worth's unknown, although his height be taken. Or bends with the remover to remove.</p>
  <p>Love alters not with his brief hours and weeks, oh, no, it is an ever fixed mark I never writ, nor no man ever loved. Within his bending sickle's compass come; it is the star to every wand'ring bark, love's not time's fool, though rosy lips and cheeks. Admit impediments; love is not love. Which alters when it alteration finds, that looks on tempests and is never shaken; whose worth's unknown, although his height be taken. Love alters not with his brief hours and weeks, if this be error and upon me proved, I never writ, nor no man ever loved.</p>
  
  <?php }else{ ?>
  <h2>This is main page</h2>
  <p>Duis aute irure dolor ut enim ad minim veniam, ut aliquip ex ea commodo consequat. Ullamco laboris nisi sunt in culpa ut labore et dolore magna aliqua. Excepteur sint occaecat ut enim ad minim veniam, cupidatat non proident. Ut aliquip ex ea commodo consequat.</p>
  <p>Duis aute irure dolor in reprehenderit in voluptate sunt in culpa. Quis nostrud exercitation sed do eiusmod tempor incididunt excepteur sint occaecat. Velit esse cillum dolore. Velit esse cillum dolore sed do eiusmod tempor incididunt lorem ipsum dolor sit amet.</p>
  <?php } ?>
  <hr>
  <p><a href="www.javawebmedia.com">About Us</a> | <a href="www.javawebmedia.com">Course</a> | <a href="www.javawebmedia.com">Contact Us</a></p>
</div>
</body>
</html>