<?php
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>SPAW Editor Demo</title>
  </head>
  <body>
  <form method="post">
	<?php
	include("../spaw.inc.php");
	$spaw1 = new SpawEditor("spaw1", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque massa augue, congue a consectetur id, condimentum ac purus. Donec odio tellus, congue sodales viverra quis, placerat eu odio. Morbi vitae dolor in turpis iaculis bibendum lobortis sed lectus. Curabitur pretium, dolor non egestas mattis, libero sapien mattis felis, eget euismod erat tortor ac velit. Proin lobortis risus odio. Nullam tempor semper lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur ut nisi dolor, elementum tempus enim. Vestibulum nisi libero, vestibulum id ultricies sit amet, elementum vitae orci. Etiam pretium tincidunt nulla, ac lacinia arcu molestie eget. Quisque scelerisque sagittis nisi non facilisis. Phasellus a leo neque. Fusce elementum augue sem. In hac habitasse platea dictumst. Ut est orci, sollicitudin ac blandit sit amet, consectetur quis elit. Proin id molestie purus. Curabitur laoreet mauris ligula, quis condimentum orci.");
	$spaw1->show();
	?>
  </form>
  </body>
</html>
