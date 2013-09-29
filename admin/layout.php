<?php 
include("../starter.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>


<title>Core3 Kiosk</title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<meta name="verify-v1" content="">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="imagetoolbar" content="no">
<meta name="robots" content="noindex,nofollow,noarchive" >
<meta name="description" content="Core3 Kiosk">
<meta name="DC.title" content="Core3 Kiosk">

<link rel="shortcut icon" type="image/ico" href="favicon.ico">
<link rev="Site name" href="http://core3kioisk.com/" title="">
<link rel="stylesheet" type="text/css" href="cms-style.css" media="all">

<script type="text/javascript" src="../js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="../js/jquery-scripts.js"></script>
<script type="text/javascript" src="../js/jquery.tools.min.js"></script>

</head>

<body id="tab1">

<div id="body_container">

	<div id="header_container">

		<div id="header">

			<div id="title">Kiosk <span> Storefront</span></div>

			<div id="Tabs">

				<ul id="tabnav">
					<li class="tab1"><a href="index.html">Categories & Products</a></li>
					<li class="tab2"><a href="index2.html">Users</a></li>
					<li class="tab3"><a href="index3.html">Orders</a></li>
					<li class="tab4"><a href="index4.html">Vendors</a></li>
					<li class="tab5"><a href="index5.html">Slideshow</a></li>
					<li class="tab6"><a href="index6.html">Reports</a></li>
					<li style="margin-left:380px;"><a href="index6.html">Logout</a></li>
				</ul>

			</div>

		</div>

	</div>
	<div id="content_container">

		<div id="content_area">

			<div id="mainbar">

				<div class="blur">
					<div class="shadow">
						<div class="content">

							<div id="content_header">
							
								<div id="content_title">Page Title</div>

								<div id="sublinks">

									<ul id="">
										<li><a href="#">Link 1</a></li>
										<li><a href="#">Link 2</a></li>
										<li><a href="#">Link 3</a></li>
										<li><a href="#">Link 4</a></li>
									</ul>

								</div>

								<br class="clear">
							
							</div>

							<div id="content_body">
							
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque massa augue, congue a consectetur id, condimentum ac purus. Donec odio tellus, congue sodales viverra quis, placerat eu odio. Morbi vitae dolor in turpis iaculis bibendum lobortis sed lectus. Curabitur pretium, dolor non egestas mattis, libero sapien mattis felis, eget euismod erat tortor ac velit. Proin lobortis risus odio. Nullam tempor semper lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur ut nisi dolor, elementum tempus enim. Vestibulum nisi libero, vestibulum id ultricies sit amet, elementum vitae orci. Etiam pretium tincidunt nulla, ac lacinia arcu molestie eget. Quisque scelerisque sagittis nisi non facilisis. Phasellus a leo neque. Fusce elementum augue sem. In hac habitasse platea dictumst. Ut est orci, sollicitudin ac blandit sit amet, consectetur quis elit. Proin id molestie purus. Curabitur laoreet mauris ligula, quis condimentum orci.<br><br>

							Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent at est quam. Aenean est urna, lobortis a pellentesque a, molestie sit amet nisl. Aliquam molestie arcu sed diam malesuada pretium. Maecenas vestibulum, libero vel vulputate egestas, orci sem sodales magna, at tempus lectus ante semper ante. Vestibulum et sem massa. Integer vehicula, urna et elementum blandit, sapien sapien sodales justo, et ullamcorper leo sapien vel lacus. Phasellus congue cursus dui nec aliquet. Duis porta, odio ac iaculis luctus, urna felis gravida urna, sollicitudin vehicula nunc metus quis erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In vel massa id nibh consequat vestibulum sit amet at elit. Nunc luctus pharetra ipsum tristique mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec augue nisi, in vulputate mauris. Donec nunc mi, fringilla eu adipiscing vel, elementum in felis.

							</div>

						</div>
					</div>
				</div>

				<br>

				<div class="blur">
					<div class="shadow">
						<div class="content">

							<div id="content_header">
							
								<div id="content_title">Page Title</div>

								<div id="sublinks">

									<ul id="">
										<li><a href="#">Link 1</a></li>
										<li><a href="#">Link 2</a></li>
										<li><a href="#">Link 3</a></li>
										<li><a href="#">Link 4</a></li>
									</ul>

								</div>

								<br class="clear">
							
							</div>

							<div id="content_body">
							
								<div id="" class="field_title" style="">Text Field Name</div>

								<div id="" class="field_copy" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</div>

								<div id="" class="field_box" style="">
									<input type="text" id="" name="normal" value="" class="" tabindex=<?php pv($ti); $ti++; ?> style="">
								</div>

								<br>

								<div id="" class="field_title" style="">Select Name</div>

								<div id="" class="field_copy" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</div>

								<div id="" class="field_box" style="">
									<select name="" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="">
										<option value="">Choose ....</option>
										<option value="">Option 1</option>
										<option value="">Option 2</option>
										<option value="">Option 3</option>
										<option value="">Option 4</option>
									</select>
								</div>

								<br>

								<div id="" class="field_title" style="">Radio Name</div>

								<div id="" class="field_box" style="">
									<input type="radio" class="normal" name="" value="" tabindex=<?php pv($ti); $ti++; ?>>Radio 1 - <span class="" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris.</span><br>
									<input type="radio" class="normal" name="" value="" tabindex=<?php pv($ti); $ti++; ?>>Radio 2 - <span class="" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris.</span><br>
									<input type="radio" class="normal" name="" value="" tabindex=<?php pv($ti); $ti++; ?>>Radio 3 - <span class="" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris.</span><br>
								</div>

								<br>

								<div id="" class="field_title" style="">Checkbox Name</div>

								<div id="" class="field_box" style="">
									<input type="checkbox" class="normal" name="" value="" tabindex=<?php pv($ti); $ti++; ?>>Checkbox 1 - <span class="" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris.</span><br>
									<input type="checkbox" class="normal" name="" value="" tabindex=<?php pv($ti); $ti++; ?>>Checkbox 2 - <span class="" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris.</span><br>
									<input type="checkbox" class="normal" name="" value="" tabindex=<?php pv($ti); $ti++; ?>>Checkbox 3 - <span class="" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris.</span><br>
								</div>

								<br>

								<div id="" class="field_title" style="">Textarea Name</div>

								<div id="" class="field_copy" style="">Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</div>

								<div id="" class="field_box" style="">
									<textarea id="" name="" class="normal" style="" tabindex=<?php pv($ti); $ti++; ?>></textarea>
								</div>

								<br>

								<hr class="normal">

								<div id="" class="" style="float:right;"><input type="submit" name="" value="Submit" class=""></div>

							</div>

						</div>
					</div>
				</div>

				<br>

			</div>

			<div id="sidebar">

				<div id="sidebar_header">Help Topic</div>
				<div id="sidebar_content">
					Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
				</div>

				<div id="sidebar_header"></div>
				<div id="sidebar_content">
					Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
				</div>

				<div id="sidebar_header"></div>
				<div id="sidebar_content">
					Quisque quam lectus, auctor sed vulputate quis, lobortis eget mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
				</div>

			</div>

			<br class="clear">

		</div>

	</div>

</div>

</body>
</html>
