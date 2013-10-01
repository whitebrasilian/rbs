<?php $content = content($page); ?>

<div id="mail-sent" class="alertbox" style="display:none;" onclick="$(this).fadeOut(500);">
	Your message has been sent. We will be contacting you very soon. Thank you!
	<br><br>

	<small>(click to close)</small>
</div>

<div class="single-column">

	<div class="section-header"><?= $content[0][0] ?></div>
	<div class="single-section-header-bar"></div>
	<div class="single-section-copy">

		<br><br>

		<a class="prev sprite arrow-left" style="float:left; margin:100px 45px 0 0;"></a>

		<?php
		$_qdb = db_query("SELECT * FROM testimonials WHERE status <> 'archive' ORDER BY date_time DESC");
		while ($qdb = db_fetch_assoc($_qdb)){

			$_dbq = db_query("SELECT lrg_rename FROM upload WHERE testimonial_id = '".$qdb['id']."'",0,0);
			$dbq = db_fetch_assoc($_dbq);

			$qdb['lrg_rename'] = $dbq['lrg_rename'];
			//pre($qdb);

			$tests[] = $qdb;

		} ?>

		<!-- container for the slides -->
		<div class="scrollable">

			<div class="items">

				<?php for ($i = 0; $i < count($tests); $i++) { ?>

					<div class="cont">

						<?php if (!is_empty($tests[$i]['lrg_rename'])) { ?>

							<img src="<?= $CFG->host ?>/images/galleries/<?= $tests[$i]['lrg_rename'] ?>" style="float:left; width:194px; height:264px; border:7px solid #fff; margin-right:33px;" alt="">

							<div style="float:left; width:480px; font-style:italic; margin-top:30px;">

								<?= $tests[$i]['testimonial'] ?>

								<div style="margin:20px 0 0 200px;">

									<span style="font-weight:bold;">

										<?= $tests[$i]['name'] ?>

										<?php if (!is_empty($tests[$i]['city']) && !is_empty($tests[$i]['state'])) { ?>

											, <?= $tests[$i]['city'] ?> <?= $tests[$i]['state'] ?>

										<?php } ?>

									</span>

									<br>
									<?= month_year($tests[$i]['date_time']) ?>

								</div>

							</div>
							<br class="clear">

						<?php }else{ ?>

							<table width="744" height="270" border=0 cellspacing=0 cellpadding=20 style="font-style:italic;">
								<tr>
									<td style="vertical-align:middle;">

										<div style="margin-top:60px; width:670px;">

											<?= $tests[$i]['testimonial'] ?>

											<div style="margin:20px 0 0 320px;">

												<span style="font-weight:bold;">

													<?= $tests[$i]['name'] ?>

													<?php if (!is_empty($tests[$i]['city']) && !is_empty($tests[$i]['state'])) { ?>

														, <?= $tests[$i]['city'] ?> <?= $tests[$i]['state'] ?>

													<?php } ?>

												</span>

												<br>
												<?= month_year($tests[$i]['date_time']) ?>

											</div>

										</div>

									</td>
								</tr>
							</table>

						<?php } ?>

					</div>

				<?php } ?>

				<br class="clear">

			</div>

		</div>

		<!-- "next slide" button -->
		<a class="next sprite arrow-right" style="float:right; margin:100px 0 0 0;"></a>
		<br class="clear">

		<script language="JavaScript">
		$(function() {

			$(".scrollable").scrollable();

		});
		</script>

	</div>

	<div class="single-section-header-bar" style="height:1px;"></div>

	<div>Would you like to send us a testimonial about your trip? Please do so through our <a href="<?= HOST ?>/contact-us/">Contact Page</a>.</div>

</div>