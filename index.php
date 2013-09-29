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
        <article class="slide" id="showcasing" style="background: url('img/backgrounds/africa1.jpg') repeat-x top center;">
            <div class="info">
                <h2>East Africa up close and personal</h2>
            </div>
        </article>
        <article class="slide" id="ideas" style="background: url('img/backgrounds/africa2.jpg') repeat-x top center;">
            <div class="info">
                <h2>Everything in Africa bites, but the safari bug is worst of all</h2>
            </div>
        </article>
        <article class="slide" id="tour" style="background: url('img/backgrounds/africa3.jpg') repeat-x top center;">
            <div class="info">
                <h2>I dream of an Africa which is in peace with itself</h2>
            </div>
        </article>
        <article class="slide" id="responsive" style="background: url('img/backgrounds/africa4.jpg') repeat-x top center;">
            <div class="info">
                <h2>Always love a good cheetah moment</h2>                
            </div>
        </article>        
    </section>
    
<div id="aboutus">
    <div class="container">
        <div class="section_header">
            <h3>East Africa Up Close and Personal</h3>
        </div>
        <div class="row">
            <div class="col-sm-12 intro">
                <h6>Phasellus sed penatibus magna lacus et, turpis placerat ridiculus ut!</h6>
                <p>
                    Phasellus sed penatibus magna lacus et, turpis placerat ridiculus ut? Etiam ultrices? Lectus quis et, et, elementum ac ac lectus ultricies natoque ultrices lectus! Penatibus. Mauris! Porttitor porttitor lundium adipiscing, porttitor in, aliquam porta! Nascetur nunc ac ultricies platea rhoncus? Ut sed sagittis, penatibus augue, magna sed cursus montes nunc, elementum! Turpis nisi et, hac, ridiculus, proin massa nascetur rhoncus dolor mattis lectus natoque amet, parturient, et elementum sit lorem enim, placerat turpis in hac nunc eros sit integer natoque? Tincidunt hac cum turpis, diam amet adipiscing, phasellus vel augue urna est dapibus tempor ultricies placerat augue augue turpis? Nunc! Purus porta nunc proin mus mus! Odio duis diam nunc scelerisque sociis lorem habitasse, mid elit. Mid, in rhoncus mattis.
                    <br /><br />
                    Sit augue eu dis tortor? Cursus phasellus ultricies sit montes magna, placerat. Lectus nunc! Sit, magna integer. Placerat ac. Penatibus ac magna tempor turpis, cum elementum. Tortor risus diam! Sed ac augue lorem velit nunc duis sit et nunc. Risus lectus eros a vel. Amet nunc dignissim. Ut duis, amet pulvinar placerat integer amet nec augue tincidunt auctor a? Tincidunt odio sit augue nascetur nisi, ut porta placerat porttitor placerat massa. Enim, placerat dis sed arcu etiam velit ultrices, elementum aenean vut ut, porttitor vel sed proin, dictumst in? Nisi in! Nascetur elementum risus magna? Porttitor risus proin massa, placerat augue amet sagittis, pellentesque ac. Scelerisque porttitor dolor porttitor montes vel a pid phasellus ut arcu integer sagittis, dolor.
                   </p>
            </div>
        </div>
    </div>
</div>

<div id="aboutus2">
    <div class="container">
        <div class="section_header">
            <h3>Where We Go</h3>
        </div>
        <div class="row">
            <div class="col-sm-6 intro">
                <h6>Phasellus sed penatibus magna lacus et!</h6>
                <p>
                    Phasellus sed penatibus magna lacus et, turpis placerat ridiculus ut? Etiam ultrices? Lectus quis et, et, elementum ac ac lectus ultricies natoque ultrices lectus! Penatibus. Mauris! Porttitor porttitor lundium adipiscing, porttitor in, aliquam porta! Nascetur nunc ac ultricies platea rhoncus? Ut sed sagittis, penatibus augue, magna sed cursus montes nunc, elementum! Turpis nisi et, hac, ridiculus, proin massa nascetur rhoncus dolor mattis lectus natoque amet, parturient, et elementum sit lorem enim, placerat turpis in hac nunc eros sit integer natoque? Tincidunt hac cum turpis, diam amet adipiscing, phasellus vel augue urna est dapibus tempor ultricies placerat augue augue turpis? Nunc! Purus porta nunc proin mus mus! Odio duis diam nunc scelerisque sociis lorem habitasse, mid elit. Mid, in rhoncus mattis.
                    <br /><br />
                    Sit augue eu dis tortor? Cursus phasellus ultricies sit montes magna, placerat. Lectus nunc! Sit, magna integer. Placerat ac. Penatibus ac magna tempor turpis, cum elementum. Tortor risus diam! Sed ac augue lorem velit nunc duis sit et nunc. Risus lectus eros a vel. Amet nunc dignissim. Ut duis, amet pulvinar placerat integer amet nec augue tincidunt auctor a? Tincidunt odio sit augue nascetur nisi, ut porta placerat porttitor placerat massa. Enim, placerat dis sed arcu etiam velit ultrices, elementum aenean vut ut, porttitor vel sed proin, dictumst in? Nisi in! Nascetur elementum risus magna? Porttitor risus proin massa, placerat augue amet sagittis, pellentesque ac. Scelerisque porttitor dolor porttitor montes vel a pid phasellus ut arcu integer sagittis, dolor.
                </p>
            </div>
            <div class="col-sm-6">

                <div id="galleria" class="galleria">

                    <?php
                    $gallery = array(
                    'African Hornbill1.jpg',
                    'Better writing desk in Selous1.jpg',
                    'Giraffe heads held high1.jpg',
                    'Guest houses at Lewa Downs1.jpg',
                    'IMG_3602.jpeg',
                    'IMG_3703.jpeg',
                    'Lunch time African style1.jpg'
                    );

                    for ($i=0; $i <count($gallery) ; $i++) { ?>

                        <a  href="images/galleries/<?=$gallery[$i] ?>">
                        <img src="images/galleries/<?=$gallery[$i] ?>">
                        </a>

                    <?php } ?>

                </div>

                <br><br>

                <iframe width="440" height="315" src="//www.youtube.com/embed/n2s5ev0i8tM" frameborder="0" allowfullscreen></iframe>

            </div>
        </div>
    </div>
</div>

<div class="map">

    <iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com.mx/?ie=UTF8&amp;ll=64.089157,-21.816616&amp;spn=0.045157,0.15398&amp;t=m&amp;z=13&amp;output=embed"></iframe>

</div>

<?php include("footer.php"); ?>