<!DOCTYPE html>
<html>

<head>

<title>CMS : <?= $PageText->PageTitle ?> : <?= $SITE->Company ?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="imagetoolbar" content="no">
<meta name="robots" content="noindex,nofollow,noarchive" >

<link rel="shortcut icon" type="image/ico" href="favicon.ico">
<link rev="Site name" href="http://www.<?= $SITE->URL ?>/" title="">

<?php if (in_array('blog-update.php',$php_self_exploded)) { ?>

	<link rel="stylesheet" type="text/css" href="http://jhollingworth.github.io/bootstrap-wysihtml5/lib/css/bootstrap.min.css"></link>
	<link rel="stylesheet" type="text/css" href="http://jhollingworth.github.io/bootstrap-wysihtml5/src/bootstrap-wysihtml5.css"></link>

<?php } ?>

<link rel="stylesheet" type="text/css" href="<?= HOST ?>/css/cms-style.css" media="all">

<!--[if IE]>
   <style type="text/css">
   .facebox {
       background:transparent;
       filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#333333,endColorstr=#333333);
       zoom: 1;
    }
    </style>
<![endif]-->

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

<script type="text/javascript" src="<?= HOST ?>/js/php.js"></script>

<!-- /// Minimize this plugins to only required usage before launch ///
<script type="text/javascript" src="<?= HOST ?>/js/jquery.tools.min.js"></script> -->
<!-- ////////////////////////////////////////////////////////////////// -->

<?php if (in_array('galleries-update.php',$php_self_exploded) || in_array('pagetext_update.php',$php_self_exploded) || in_array('media-gallery.php',$php_self_exploded) || in_array('blog-update.php',$php_self_exploded) || in_array('testimonials-update.php',$php_self_exploded) || in_array('guides-update.php',$php_self_exploded)){ ?>

	<link href="<?= HOST ?>/standards/classes/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="<?= HOST ?>/standards/classes/uploadify/swfobject.js"></script>
	<script type="text/javascript" src="<?= HOST ?>/standards/classes/uploadify/jquery.uploadify.v2.1.4.min.js"></script>

    <!-- http://galleria.aino.se/ -->
    <script src="<?= HOST ?>/js/galleria/galleria-1.2.9.min.js"></script>
    <script type="text/javascript">
    <!--
    // Load the classic theme
    Galleria.loadTheme('<?= HOST ?>/js/galleria/themes/twelve/galleria.twelve.min.js');
     //-->
	</script>

<?php } ?>

<?php if (in_array('blog-update.php',$php_self_exploded)) { ?>

	<!-- http://docs.jquery.com/Plugins/autocomplete -->
	<script type="text/javascript" src="<?= HOST ?>/js/jquery.autocomplete.js"></script>

<?php } ?>


</head>

<?php
if (in_array('users',$php_self_exploded)) { $tab = "tab1"; }
elseif (in_array('pagetext',$php_self_exploded)) { $tab = "tab2"; }
?>

<body id="<?= $tab ?>">

<div id="body_container">

	<div id="header_container">

		<div id="header">

			<div id="title"><a href="<?= HOST ?>/index.php" style="text-decoration:none;"><?= $SITE->Company ?> <span> CMS</span></a></div>

			<!-- <div id="Tabs">

				<ul id="tabnav">
					<li class="tab1"><a href="<?= HOST ?>/manager/products/index.php">Categories &amp; Products</a></li>
					<li class="tab2"><a href="<?= HOST ?>/manager/users/index.php">Users</a></li>
					<li class="tab3"><a href="<?= HOST ?>/manager/orders/index.php">Orders</a></li>

					<li class="tab5"><a href="<?= HOST ?>/manager/reports/index.php">Reports</a></li>

					<?php if (has_priv('admin')) { ?>
						<li class="tab6"><a href="<?= HOST ?>/manager/pagetext/index.php">Pages</a></li>
					<?php } ?>

					<li style="margin-left:50px;"><a href="<?= HOST ?>/logout.php">Logout</a></li>
				</ul>

			</div> -->

		</div>

	</div>
	<div id="content_container">

		<div id="content_area">


<!--
					<li class="tab1"><a href="<?= HOST ?>/manager/products/index.php">Categories &amp; Products</a></li>
					<li class="tab2"><a href="<?= HOST ?>/manager/users/index.php">Users</a></li>
					<li class="tab3"><a href="<?= HOST ?>/manager/orders/index.php">Orders</a></li>

					<li class="tab5"><a href="<?= HOST ?>/manager/reports/index.php">Reports</a></li>

					<?php if (has_priv('admin')) { ?>
						<li class="tab6"><a href="<?= HOST ?>/manager/pagetext/index.php">Pages</a></li>
					<?php } ?>

					<li style="margin-left:50px;"><a href="<?= HOST ?>/logout.php">Logout</a></li>
-->