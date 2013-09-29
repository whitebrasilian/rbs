<?php
include("starter.php");
include("header.php");
?>

<div id="blog">
    <div class="container">
        <div class="section_header">
            <h3>Riverbank Safaris Blog</h3>
        </div>

        <?php
        if (!is_empty($_vars['p'])) { $clause = "blog.id = '".$_vars['p']."' AND"; }
        elseif (!is_empty($_vars['s'])){ $clause = "blog_link.section_id = '".$_vars['s']."' AND"; }
        elseif (!is_empty($_vars['d'])){ $clause = "blog.datetime LIKE '".$_vars['d']."%' AND"; }
        elseif (!is_empty($_vars['a'])){ $clause = "blog.author = '".$_vars['a']."' AND"; }
        //else{ $clause = "blog.datetime LIKE '".$_vars['d']."%'"; }

        $query_count = db_query("
        SELECT
        blog.id
        FROM
        blog_link
        Inner Join blog ON blog_link.blog_id = blog.id
        WHERE
        ".$clause."
        blog.status = 'show'
        GROUP BY blog.id
        ",0,0);

        if(is_empty($_vars['page'])){ $page = 1; }else{ $page = $_vars['page']; }
        $limit = 8;
        $totalrows = db_num_rows($query_count);
        $startValue = ($page * $limit) - $limit;
        if ($startValue < 0) { $startValue = 0; }

        $_qdc = db_query("
        SELECT
        blog.id,
        blog.title,
        blog.content,
        blog.video_id,
        blog.gallery_id,
        blog.author,
        blog.datetime
        FROM
        blog_link
        Inner Join blog ON blog_link.blog_id = blog.id
        WHERE
        ".$clause."
        blog.status = 'show'
        GROUP BY blog.id
        ORDER BY blog.datetime DESC
        LIMIT ".$startValue.", ".$limit."
        ",0,0);

        $row=0;
        while ($qdc = db_fetch_assoc($_qdc)){

            if ($row==0) { ?>

                <!-- Post Row -->
                <div class="row post_row">

                <?php 
            }
            $row++;
            ?>

            <!-- Post -->
            <div class="col-sm-4">
                <div class="post">
                    <div class="img">
                        <a href="blog-post.php?p=<?= $qdc['id'] ?>">

                            <?php
                            $_dbq = db_query("SELECT lrg_rename FROM upload WHERE gallery_id = '".$qdc['gallery_id']."' ORDER BY upload_id ASC limit 1");
                            $dbq = db_fetch_assoc($_dbq); ?>

                            <img title="" alt="" src="<?= HOST ?>/images/galleries/<?= $dbq['lrg_rename'] ?>" style="width:293px;" class="img-responsive"/>

                        </a>
                    </div>
                    <div class="text">
                        <h5><a href="blog-post.php?p=<?= $qdc['id'] ?>"><?= $qdc['title'] ?></a></h5>
                        <span class="date"><?= formatDateLong2($qdc['datetime']) ?></span>
                        <p>

                        <?php 
                        if (strlen($qdc['content']) > 300 && is_empty($_vars['p'])) {

                            echo prep_var(substr($qdc['content'],0,300))."....";

                        } else { echo prep_var($qdc['content']); } ?>

                        </p>
                    </div>
                    <div class="author_box">
                        <h6><?= $qdc['author'] ?></h6>
                        <!--<p>Creative Director</p>-->
                    </div>
                </div>
            </div>

            <?php 
            
        }

        for ($i=$row; $i < 3; $i++) { ?>

            <!-- Post -->
            <div class="col-sm-4">
            </div>

        <?php } ?>

        </div>

        <div class="paginator-container">
            <ul class="pagination">

                <?php
                $kee = array_keys($_vars);
                for ($i = 0; $i < count($_vars); $i++) {
                    if ($kee[$i] != 'page') { $param = "&".$kee[$i]."=".$_vars[$kee[$i]]; }
                }

                // create a start value
                $start = ($page - 1) * $limit;

                // Showing Results 1 to 1 (or if you're page limit were 5) 1 to 5, etc.
                $starting_no = $start + 1;

                if ($totalrows - $start < $limit) {
                   $end_count = $totalrows;
                } else if ($totalrows - $start >= $limit) {
                   $end_count = $start + $limit;
                }

                if ($totalrows > $limit) {

                    if($page != 1){

                        $pageprev = $page -1; ?>

                         <li><a href="blog/?page=<?= $pageprev.$param ?>">Prev</a></li>

                    <?php
                    }

                    $numofpages = ceil($totalrows / $limit);
                    $numofpages = 5;

                    for($i = 1; $i <= $numofpages; $i++){

                        if($i == $page) { ?>

                            <li><?=$i; ?></li>

                        <?php }else { ?>

                            <li><a href="blog/?page=<?= $i.$param ?>"><?=$i; ?></a></li>

                        <?php
                        }

                    }

                    if(($totalrows % $limit) >= 9){

                        if($i == $page) { ?>

                            <li><?=$i; ?></li>

                        <?php }else{ ?>

                            <li><a href="blog/?page=<?= $i.$param ?>"><?=$i; ?></a></li>

                        <?php
                        }

                    }

                    if(($totalrows - ($limit * $page)) > 0){

                        $pagenext = $page + 1; ?>

                        <li><a href="blog/?page=<?= $pagenext.$param ?>">Next</a></li>

                    <?php
                    }

                } ?>

            </ul>
        </div>

    </div>
</div>

<?php include("footer.php"); ?>