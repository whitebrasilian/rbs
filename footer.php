    <!-- starts footer -->
    <footer id="footer">
        <div class="container">
            <div class="row sections">
                <div class="col-sm-4 recent_posts">
                    <h3 class="footer_header">
                        Recent Blog Posts
                    </h3>

                    <?php
                    $_sec = db_query("SELECT id, title, datetime, gallery_id FROM blog WHERE status = 'show' ORDER BY datetime DESC limit 3");
                    while($sec = db_fetch_assoc($_sec)){ ?>

                        <div class="post">
                            <a href="<?= $CFG->host ?>/blog-post.php?p=<?= $sec['id'] ?>">

                                <?php
                                $_dbq = db_query("SELECT lrg_rename FROM upload WHERE gallery_id = '".$sec['gallery_id']."' ORDER BY upload_id ASC limit 1");
                                $dbq = db_fetch_assoc($_dbq); ?>

                                <img title="" alt="" src="<?= HOST ?>/images/galleries/<?= $dbq['lrg_rename'] ?>" style="width:55px; height:55px;" class="img-circle">

                            </a>
                            <div class="date">
                                <?= formatDateLong2($sec['datetime']) ?>
                            </div>
                            <a href="<?= $CFG->host ?>/blog-post.php?p=<?= $sec['id'] ?>" class="title">
                                <?= $sec['title'] ?>
                            </a>
                        </div>

                    <?php } ?>

                </div>
                <div class="col-sm-4 testimonials">
                    <h3 class="footer_header">
                        Testimonials
                    </h3>
                    <div class="wrapper">
                        <div class="quote">
                            <span>“</span>
                            We had such a great time. Sit augue eu dis tortor? Cursus phasellus ultricies sit montes magna, placerat. Lectus nunc! Sit, magna integer. Placerat ac. Penatibus ac magna tempor turpis, cum elementum. 
                            <span></span>
                        </div>
                        <div class="author">
                            <img src="img/user-display.png" class="img-circle"/>
                            <div class="name">Alejandra & Galvan Castillo</div>
                            <div class="info">
                                Aug 2013
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 contact">
                    <h3 class="footer_header">
                        Contact Us
                    </h3>
                    <form action="#" method="post">
                        <input type="text" id="name" placeholder="Your Nname" />
                        <input type="text" id="email" placeholder="Your Email" />
                        <input type="text" id="phone" placeholder="Your Phone" />
                        <textarea rows="6" id="body" placeholder="Message"></textarea>
                        <input type="submit" value="Send" />
                    </form>
                    <br><br>
                </div>
            </div>
            <div class="row credits">
                <div class="col-md-12">
                    <div class="row social"></div>
                </div>            
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>

    <?php if ($index==1) { ?><script src="js/index-slider.js"></script><?php } ?>  
    
    <?php if ($index==1 || in_array('planning.php',$php_self_exploded) || in_array('history.php',$php_self_exploded) || in_array('blog-post.php',$php_self_exploded)) { ?>

        <!-- http://galleria.aino.se/ -->
        <script src="js/galleria/galleria-1.2.9.min.js"></script>
        <script type="text/javascript">
        <!--
        // Load the classic theme
        Galleria.loadTheme('js/galleria/themes/twelve/galleria.twelve.min.js');
        Galleria.run('#galleria');
        //-->
        </script>

    <?php } ?>

    <?php if (in_array('history.php',$php_self_exploded)) { ?>

        <script src="js/jquery.isotope.min.js"></script>
        <script type="text/javascript">
            $(function(){

                var $container = $('#gallery_container'),
                      $filters = $("#filters a");
            
                $container.imagesLoaded( function(){
                    $container.isotope({
                        itemSelector : '.photo',
                        masonry: {
                            columnWidth: 100
                        }
                    });
                });

                // filter items when filter link is clicked
                $filters.click(function() {
                    $filters.removeClass("active");
                    $(this).addClass("active");
                    var selector = $(this).data('filter');
                    $container.isotope({ filter: selector });
                    return false;
                });
            
            
            });
        </script>
       
    <?php } ?>   

    <script type="text/javascript">
    <!--
    function sendMail() {

        var name            = $('#name').val();
        var email           = $('#email').val();
        var phone           = $('#phone').val();
        var body            = $('#body').val();

        $.ajax({
            type: "POST",
            url: "standards/ajax.php",
            data: 'name=' + name + '&email=' + email + '&phone=' + phone + '&body=' + body,
            success: function(msg){

                if (msg==1){
                    $('#myModal').modal({show:true})
                    $('#body').val('');
                }

            }
        });

        return false;

    }

    $(function(){

        $("form").submit(function(e){
            sendMail();
            e.preventDefault(e);
        });

    });

    //-->
    </script>

</body>
</html>