<?php 
include('../starter.php');
set_time_limit(0);

/*   create slugs for missing posts and clips   */
$_clips = db_query("SELECT id, name FROM clips WHERE slug IS NULL",0,0);
while ($clips = db_fetch_assoc($_clips)) { 

	db_query("UPDATE clips SET slug = '".slugMaker($clips['name'],'clips')."' WHERE id = '".$clips['id']."'",0,0);

}
echo"done clips";

$_posts = db_query("SELECT post_id, post_title FROM posts WHERE slug IS NULL",0,0);
while ($posts = db_fetch_assoc($_posts)) { 

	db_query("UPDATE posts SET slug = '".slugMaker($posts['post_title'],'posts')."' WHERE post_id = '".$posts['post_id']."'",0,0);

}
echo"done posts";
die;

/*   transfer clip date to post data   */
$m=1;
$d=1;
$_clips = db_query("SELECT * FROM clips WHERE 1",0,0);
while ($clips = db_fetch_assoc($_clips)) { 

	if (!is_empty($clips['embed_code'])) { 
		
		/*
		if ($clips['datepicker']=='2011') { 
			$clips['datepicker'] = "2011-".leadingZeros($m,2)."-".leadingZeros($d,2);
			$d++;
		}elseif ($clips['datepicker']=='2010') {
			$clips['datepicker'] = "2010-09-16";
		}
		*/

		db_query("INSERT INTO posts (post_title, post_copy, post_status, post_date, post_elevate, post_show, slug, post_author, post_preview, clips_id) VALUES ('".mrClean($clips['name'])."', '".mrClean($clips['description'])."', 'Published', '".$clips['datepicker']."', '1', '".$clips['show']."', '".$clips['slug']."', '1', '0', '".$clips['id']."')",0,0);
		$post_id = db_insert_id();

		db_query("UPDATE clips SET post_id = '".$post_id."', datepicker = '".$clips['datepicker']."' WHERE id = '".$clips['id']."'",0,0);

		db_query("INSERT INTO connector_posts (post_id, ref, ref_id, uid) VALUES ('".$post_id."','video','".$clips['id']."',1)",0,0);

		if ($d == 30) { $d=1; $m++; }

	}

}
echo"done";
die;
?>