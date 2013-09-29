<!DOCTYPE html>
<html>
<head>
	<title>RIVERBANK SAFARIS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="robots" content="index,follow,archive" >
    <link rev="Site name" href="http://www.riverbanksafaris.com/" title="">

    <!-- Styles -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/compiled/bootstrap-overrides.css" />
    <link rel="stylesheet" type="text/css" href="css/compiled/theme.css" />

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css' />

    <link rel="stylesheet" href="css/compiled/style.css" type="text/css" media="screen" />    
    <link rel="stylesheet" type="text/css" href="css/lib/animate.css" media="screen, projection" />

    <?php if ($index==1) { ?>
        <link rel="stylesheet" href="css/lib/flexslider.css" type="text/css" media="screen" />
    <?php } ?>

    <?php if (in_array('history.php',$php_self_exploded)) { ?>
        <link rel="stylesheet" type="text/css" href="css/lib/isotope.css" />
    <?php } ?>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body <?php if ($index==1) { ?> class="pull_top"<?php } ?>>

    <div class="navbar navbar-inverse <?php if ($index==1) { echo"navbar-fixed-top"; }else{ echo"navbar-static-top"; } ?>" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle <?php if ($index==1) { echo "pull-right"; } ?>" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.html" class="navbar-brand"><strong>RIVERBANK SAFARIS</strong></a>
            </div>

            <div class="collapse navbar-collapse navbar-ex1-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="index.php">HOME</a></li>
                    <li><a href="planning.php">PLANNING &#38 PREPARATION</a></li>
                    <li><a href="why-riverbank-safaris.php">WHY US?</a></li>
                    <li><a href="history.php">OUR HISTORY &#38 GALLERIES</a></li>
                    <li><a href="blog.php">BLOG</a></li>
                </ul>
            </div>
        </div>
    </div>