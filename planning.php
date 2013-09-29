<?php
include("starter.php");
include("header.php");
?>
    
<div id="aboutus">
    <div class="container">
        <div class="section_header">
            <h3>How to Prepare?</h3>
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

                <div id="galleria" style="max-width:440px; height:330px;">

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

                <div class="flex-video widescreen" style="margin: 0 auto;text-align:center;">
                    <iframe width="440" height="330" src="//www.youtube.com/embed/n2s5ev0i8tM" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="aboutus">
    <div class="container">
        <div class="section_header">
            <h3>Plan Your Trip</h3>
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

<?php include("footer.php"); ?>