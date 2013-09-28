<?php
include("starter.php");
include("header.php");
?>

<section id="feature_slider" class="lol">
    <!-- 
        Each slide is composed by <img> and .info
        - .info's position is customized with css in index.css
        - each <img> parallax effect is declared by the following params inside its class:
        
        example: class="asset left-472 sp600 t120 z3"
        left-472 means left: -472px from the center
        sp600 is speed transition
        t120 is top to 120px
        z3 is z-index to 3
        Note: Maintain this order of params

        For the backgrounds, you can combine from the bgs folder :D
    -->
    <article class="slide" id="showcasing" style="background: url('img/backgrounds/landscape.png') repeat-x top center;">
        <img class="asset left-30 sp600 t120 z1" src="img/slides/scene1/macbook.png" />
        <div class="info">
            <h2>Beautiful theme for showcasing your works.</h2>
        </div>
    </article>
    <article class="slide" id="ideas" style="background: url('img/backgrounds/aqua.jpg') repeat-x top center;">
        <div class="info">
            <h2>We love to turn ideas into beautiful things.</h2>
        </div>
        <img class="asset left-480 sp600 t260 z1" src="img/slides/scene2/left.png" />
        <img class="asset left-210 sp600 t213 z2" src="img/slides/scene2/middle.png" />
        <img class="asset left60 sp600 t260 z1" src="img/slides/scene2/right.png" />
    </article>
    <article class="slide" id="tour" style="background: url('img/backgrounds/color-splash.jpg') repeat-x top center;">
        <img class="asset left-472 sp650 t210 z3" src="img/slides/scene3/ipad.png" />
        <img class="asset left-365 sp600 t270 z4" src="img/slides/scene3/iphone.png" />
        <img class="asset left-350 sp450 t135 z2" src="img/slides/scene3/desktop.png" />
        <img class="asset left-185 sp550 t220 z1" src="img/slides/scene3/macbook.png" />
        <div class="info">
            <h2>Fully Responsive theme</h2>
            <a href="features.html">TOUR THE PRODUCT</a>
        </div>
    </article>
    <article class="slide" id="responsive" style="background: url('img/backgrounds/indigo.jpg') repeat-x top center;">
        <img class="asset left-472 sp600 t120 z3" src="img/slides/scene4/html5.png" />
        <img class="asset left-190 sp500 t120 z2" src="img/slides/scene4/css3.png" />
        <div class="info">
            <h2>
                Responsive <strong>HTML5 & CSS3</strong>
                Theme
            </h2>                
        </div>
    </article>        
</section>

<div id="showcase">
    <div class="container">
        <div class="section_header">
            <h3>Our Services</h3>
        </div>            
        <div class="row feature_wrapper">
            <!-- Features Row -->
            <div class="features_op1_row">
                <!-- Feature -->
                <div class="col-sm-4 feature first">
                    <div class="img_box">
                        <a href="services.html">
                            <img src="img/service1.png">
                            <span class="circle"> 
                                <span class="plus">&#43;</span>
                            </span>
                        </a>
                    </div>
                    <div class="text">
                        <h6>Responsive theme</h6>
                        <p>
                            There are many variations of passages of generators on the  embarrassing hidden in   content here making it look like.
                        </p>
                    </div>
                </div>
                <!-- Feature -->
                <div class="col-sm-4 feature">
                    <div class="img_box">
                        <a href="services.html">
                            <img src="img/service2.png">
                            <span class="circle"> 
                                <span class="plus">&#43;</span>
                            </span>
                        </a>
                    </div>
                    <div class="text">
                        <h6>Easy customization</h6>
                        <p>
                            There are many variations of passages of generators on the  embarrassing hidden in   content here making it look like.
                        </p>
                    </div>
                </div>
                <!-- Feature -->
                <div class="col-sm-4 feature last">
                    <div class="img_box">
                        <a href="services.html">
                            <img src="img/service3.png">
                            <span class="circle"> 
                                <span class="plus">&#43;</span>
                            </span>
                        </a>
                    </div>
                    <div class="text">
                        <h6>Made with love</h6>
                        <p>
                            There are many variations of passages of generators on the  embarrassing hidden in   content here making it look like.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="features">
    <div class="container">
        <div class="section_header">
            <h3>Features</h3>
        </div> 
        <div class="row feature">
            <div class="col-sm-6">
                <img src="img/showcase1.png" class="img-responsive" />
            </div>
            <div class="col-sm-6 info">
                <h3>
                    <img src="img/features-ico1.png" />
                    Beautiful on all devices
                </h3>
                <p>
                    There are many variations of passages of Lorem Ipsum available, but the randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.
                </p>
            </div>
        </div>
        <div class="row feature">
            <div class="col-sm-6 pic-right">
                <img src="img/showcase2.png" class="pull-right img-responsive" />
            </div>
            <div class="col-sm-6 info info-left">
                <h3>
                    <img src="img/features-ico2.png" />
                    Blog page included
                </h3>
                <p>
                    There are many variations of passages of Lorem Ipsum available, but the randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.
                </p>
            </div>                
        </div>
        <div class="row feature">
            <div class="col-sm-6">
                <img src="img/showcase3.png" class="img-responsive" />
            </div>
            <div class="col-sm-6 info">
                <h3>
                    <img src="img/features-ico3.png" />
                    Simple and clean coming soon page
                </h3>
                <p>
                    There are many variations of passages of Lorem Ipsum available, but the randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("$CFG->baseroot/footer.php"); ?>