<?php
while($posts = db_fetch_array($_posts)){

	$forum[$y] = $posts;
	
	$_reply = db_query("
	SELECT
	comment_date,
	comment_author as user_id
	FROM
	comments
	WHERE
	comment_post_id = '".$posts['post_id']."'
	ORDER BY comment_id DESC 
	LIMIT 1
	",0,0);
	$reply = db_fetch_array($_reply);

	$_r = db_query("
	SELECT
	Count(comment_id) AS cnt
	FROM
	comments
	WHERE
	comment_post_id = '".$posts['post_id']."'
	",0,0);
	$r = db_fetch_array($_r);

	$_u = db_query("
	SELECT
	username as reply_username
	FROM
	users
	WHERE
	id = '".$reply['user_id']."'
	limit 1
	",0,0);
	$u = db_fetch_array($_u);

	$forum[$y]['cnt'] = $r['cnt'];
	$forum[$y]['user_id'] = $reply['user_id'];
	$forum[$y]['reply_username'] = $u['reply_username'];
	$forum[$y]['comment_date'] = $reply['comment_date'];

	$y++;

}

//pre($forum);

if ($_vars['sort'] == 'views')			{ $forum = orderBy($forum, 'post_view_cnt', 2); }
elseif ($_vars['sort'] == 'replies')	{ $forum = orderBy($forum, 'cnt', 2); }
elseif ($_vars['sort'] == 'freshness')	{ $forum = orderBy($forum, 'comment_date', 2); }	

for ($i = 0; $i < count($forum); $i++) { ?>

	<div id="" class="forum_post <?php if ($x == 0) { $x++; echo"forum_post_bg"; }else{ $x--; } ?>" style="">

		<div id="" class="forum_row" style="width:315px;">

			<a href="<?= HOST ?>/forum/<?= $forum[$i]['slug'] ?>" class="forum_title"><?= strtoupper($forum[$i]['post_title']) ?></a><br>

			<?php if (has_priv('admin')) { ?>
				<div id="" class="lfloat" style=""><a class="" style="font-size:11px; " href="<?= HOST ?>/post-form.php?id=<?= $forum[$i]['post_id'] ?>">edit</a>&nbsp;-&nbsp;</div>
			<?php } ?>

			<div class="lfloat" style="font-size:11px;">by <a href="<?= HOST ?>/member/<?= $forum[$i]['username']; ?>" class=""><?= $forum[$i]['username'] ?></a> on <?= formatDateLong2($forum[$i]['post_date']) ?></div>

			<br class="clear">

		</div>
		<!-- <div id="" class="forum_row forum_title" style="width:60px;"><?= format_number($forum[$i]['post_view_cnt'],'','',0) ?></div> -->
		<div id="" class="forum_row forum_title" style="width:60px;">&nbsp;&nbsp;&nbsp;<?= $forum[$i]['cnt'] ?></div>
		<div id="" class="forum_row" style="width:130px; font-size:11px;">
			<?php if ($forum[$i]['cnt']>0) { ?>
				Last post by <a href="<?= HOST ?>/member/<?= $forum[$i]['reply_username']; ?>" class=""><?= $forum[$i]['reply_username'] ?></a><br>
				<?= formatDateLong2($forum[$i]['comment_date']) ?>
			<?php }else{ echo"&nbsp;"; } ?>
		</div>

		<br class="clear">

	</div>
	
<?php } ?>