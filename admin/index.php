<?php
include("../starter.php");

if (has_priv('admin')) {

	header("Location:pages/index.php");
	die;

}

if (!is_empty($_vars['password'])) {

	//pre($_SESSION['user']);

	$qid = db_query("SELECT * FROM users WHERE priv = 'admin' AND password = '".md5($_vars['password'])."' limit 1",0,0);
	$user = db_fetch_assoc($qid);

	if (is_array($user)) {

		$_SESSION['user'] = $user;

		header("Location:pages/index.php?action=login");
		die;

	} else {

		header("Location:index.php?action=login-fail");
		die;
	}

}


$PageText = GetPageText("Login");
include($CFG->baseroot."/admin/cms-header.php");
?>

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><? pv($PageText->PageTitle) ?></div>

					<br class="clear">

				</div>

				<div id="content_body">

					<form id="this_form" name="this_form" method="POST" action="index.php">

						<div class="field_title">PASSWORD <span class="star" style="">*</span></div>

						<div class="field_box">
							<input type="password" name="password" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;">
						</div>

						<div style="margin:15px 30px 0px 0px;"><input type="submit" name="submit" value="Submit" class=""></div>
						<br class="clear">

						<br>

					</form>

				</div>

			</div>
		</div>
	</div>

	<br>

</div>

<div class="sidebar">


</div>

<br class="clear">

<? include($CFG->baseroot."/admin/cms-footer.php"); ?>