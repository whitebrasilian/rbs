    <!-- starts footer -->
    <footer id="footer">
        <div class="container">
            <div class="row sections">
                <div class="col-sm-4 recent_posts">
                    <h3 class="footer_header">
                        Recent Posts
                    </h3>
                    <div class="post">
                        <a href="blogpost.html">
                            <img src="img/recent_post1.png" class="img-circle" />
                        </a>
                        <div class="date">
                            Wed, 12 Dec
                        </div>
                        <a href="blogpost.html" class="title">
                            Randomised words which don't look embarrasing hidden.
                        </a>
                    </div>
                    <div class="post">
                        <a href="blogpost.html">
                            <img src="img/recent_post2.png" class="img-circle" />
                        </a>
                        <div class="date">
                            Mon, 12 Dec
                        </div>
                        <a href="blogpost.html" class="title">
                            Randomised words which don't look embarrasing hidden.
                        </a>
                    </div>
                </div>
                <div class="col-sm-4 testimonials">
                    <h3 class="footer_header">
                        Testimonials
                    </h3>
                    <div class="wrapper">
                        <div class="quote">
                            <span>“</span>
                            There are many variations of passages of randomised words which don't look even slightly believable. You need to be sure there isn't anything embarrassing of text.
                            <span></span>
                        </div>
                        <div class="author">
                            <img src="img/user-display.png" />
                            <div class="name">Alejandra Galvan Castillo</div>
                            <div class="info">
                                Details Canvas
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 contact">
                    <h3 class="footer_header">
                        Contact
                    </h3>
                    <form action="#" method="post">
                        <input type="text" placeholder="Your name" />
                        <input type="text" placeholder="Your email" />
                        <textarea rows="3" placeholder="Message"></textarea>
                        <input type="submit" value="Send" />
                    </form>
                </div>
            </div>
            <div class="row credits">
                <div class="col-md-12">
                    <div class="row social">
                        <div class="col-md-12">
                            <a href="#" class="facebook">
                                <span class="socialicons ico1"></span>
                                <span class="socialicons_h ico1h"></span>
                            </a>
                            <a href="#" class="twitter">
                                <span class="socialicons ico2"></span>
                                <span class="socialicons_h ico2h"></span>
                            </a>
                            <a href="#" class="gplus">
                                <span class="socialicons ico3"></span>
                                <span class="socialicons_h ico3h"></span>
                            </a>
                            <a href="#" class="flickr">
                                <span class="socialicons ico4"></span>
                                <span class="socialicons_h ico4h"></span>
                            </a>
                            <a href="#" class="pinterest">
                                <span class="socialicons ico5"></span>
                                <span class="socialicons_h ico5h"></span>
                            </a>
                            <a href="#" class="dribble">
                                <span class="socialicons ico6"></span>
                                <span class="socialicons_h ico6h"></span>
                            </a>
                            <a href="#" class="behance">
                                <span class="socialicons ico7"></span>
                                <span class="socialicons_h ico7h"></span>
                            </a>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>

    <?php if ($index==1) { ?><script src="js/index-slider.js"></script><?php } ?>  
    
    <?php if ($index==1 || in_array('planning.php',$php_self_exploded) || in_array('history.php',$php_self_exploded)) { ?>

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

</body>
</html>