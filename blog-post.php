<?php
include("starter.php");
include("header.php");
?>

<div id="blog_post">
    <div class="container">
        
        <div class="section_header">
            <h3>Riverbank Safaris Blog</h3>
        </div>

        <div class="row">

            <?php
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
            blog.id = ".$_vars['p']." AND
            blog.status = 'show'
            ",0,0);

            $qdc = db_fetch_assoc($_qdc);
            ?>   

            <div class="col-sm-8">
                <img class="post_pic img-responsive" src="img/blog_post.jpg" />

                <div class="post_content">
                    <h2><?= $qdc['title'] ?></h2>
                    <span class="date"><?= formatDateLong2($qdc['datetime']) ?></span>
                    
                    <?php echo prep_var($qdc['content']); ?>
                    
                    <div class="author_box">
                        <div class="author"><?= $qdc['author'] ?></div>
                        <!--<div class="area">Creative Director</div>-->
                    </div>
                </div>

            </div>
            
            <!-- SideBar -->
            <div class="col-sm-4">
                <div class="sidebar">
                    <div class="box">
                        <div class="sidebar_header">
                            <h4>Recent Posts</h4>
                        </div>

                        <?php
                        $_sec = db_query("SELECT id, title FROM blog WHERE status = 'show' ORDER BY datetime DESC limit 3");
                        while($sec = db_fetch_assoc($_sec)){ ?>

                            <div class="recent">
                                <span>
                                    <a href="<?= $CFG->host ?>/blog-post.php?p=<?= $sec['id'] ?>"><img src="img/recent_photos.jpg" alt=""></a>
                                </span>
                                <p><a href="<?= $CFG->host ?>/blog-post.php?p=<?= $sec['id'] ?>"><?= $sec['title'] ?></a></p>
                            </div>

                        <?php } ?>

                    </div>

                    <div class="box last">
                        <div class="sidebar_header">
                            <h4>Menu</h4>
                        </div>

                        <ul class="sidebar_menu">
                            <ul>

                                <?php
                                $tags = "";
                                $_sec = db_query("SELECT id, section FROM blog_section WHERE 1 ORDER BY section ASC");
                                while($sec = db_fetch_assoc($_sec)){

                                    $tags .= "<li><a href=\"".$CFG->host."/blog.php?s=".$sec['id']."\">".$sec['section']."</a></li>";

                                }

                                echo $tags;
                                ?>

                            </ul>
                        </ul>
                    </div>
                    
                   <!-- 
                   <div class="box">
                        <div class="sidebar_header">
                            <h4>Archives</h4>
                        </div>

                        <ul class="sidebar_menu">
                            <ul>

                                <?php
                                $months = array("","January","February","March","April","May","June","July","August","September","October","November","December");

                                for ($i = date('n'); $i > 0; $i--){

                                    $dat = date(Y)."-".leadingZeros($i,2);

                                    $_sec = db_query("SELECT id FROM blog WHERE datetime LIKE '".$dat."%' AND status = 'show'");
                                    $cnt = db_num_rows($_sec);

                                    if ($cnt > 0) { ?>

                                        <li><a href="<?= $CFG->host ?>/blog/?d=<?= $dat ?>"><?= $months[$i] ?> <?= date(Y) ?> (<?= $cnt ?>)</a></li>

                                    <?php } ?>

                                <?php } ?>

                                <?php for ($i = 12; $i > date('n'); $i--){

                                    $dat = (date(Y)-1)."-".leadingZeros($i,2);

                                    $_sec = db_query("SELECT id FROM blog WHERE datetime LIKE '".$dat."%' AND status = 'show'");
                                    $cnt = db_num_rows($_sec);

                                    if ($cnt > 0) { ?>

                                        <li><a href="<?= $CFG->host ?>/blog/?d=<?= $dat ?>"><?= $months[$i] ?> <?= date(Y) ?> (<?= $cnt ?>)</a></li>

                                    <?php } ?>

                                <?php } ?>

                                <br>

                                <?php
                                $_sec = db_query("SELECT datetime FROM blog WHERE status = 'show' ORDER BY datetime ASC limit 1");
                                $sec = db_fetch_assoc($_sec);
                                for ($i = (date('Y')-1); $i > substr($sec['datetime'],0,4); $i--){

                                    $_sec = db_query("SELECT id FROM blog WHERE datetime LIKE '".$i."%'");
                                    $cnt = db_num_rows($_sec);

                                    if ($cnt > 0) { ?>

                                        <li><a href="<?= $CFG->host ?>/blog/?d=<?= $dat ?>"><?= $months[$i] ?> <?= date(Y) ?> (<?= $cnt ?>)</a></li>

                                    <?php } ?>

                                <?php } ?>


                            </ul>
                        </ul>
                    </div>
                     -->

                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>