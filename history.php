<?php
include("starter.php");
include("header.php");
?>

<div id="aboutus">
    <div class="container">
        <div class="section_header">
            <h3>Old East Africa</h3>
        </div>
        <div class="row">
            <div class="col-sm-6 intro">
                <h6>Phasellus sed penatibus magna lacus et!</h6>
                <p>
                    Phasellus sed penatibus magna lacus et, turpis placerat ridiculus ut? Etiam ultrices? Lectus quis et, et, elementum ac ac lectus ultricies natoque ultrices lectus! Penatibus. Mauris! Porttitor porttitor lundium adipiscing, porttitor in, aliquam porta! Nascetur nunc ac ultricies platea rhoncus? Ut sed sagittis, penatibus augue, magna sed cursus montes nunc, elementum! Turpis nisi et, hac, ridiculus, proin massa nascetur rhoncus dolor mattis lectus natoque amet, parturient, et elementum sit lorem enim, placerat turpis in hac nunc eros sit integer natoque? Tincidunt hac cum turpis, diam amet adipiscing, phasellus vel augue urna est dapibus tempor ultricies placerat augue augue turpis? Nunc! Purus porta nunc proin mus mus! Odio duis diam nunc scelerisque sociis lorem habitasse, mid elit. Mid, in rhoncus mattis.
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

                        <a href="images/galleries/<?=$gallery[$i] ?>">
                        <img src="images/galleries/<?=$gallery[$i] ?>">
                        </a>

                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
</div>

<div id="portfolio">
    <div class="container">
        <div class="section_header">
            <h3>Photo Gallery</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="filters_container">
                    <ul id="filters">
                        <li><a href="#" data-filter="*" class="active">All</a></li>
                        <li class="separator">/</li>
                        <li><a href="#" data-filter=".landscape">Landscape</a></li>
                        <li class="separator">/</li>
                        <li><a href="#" data-filter=".sunset">Sunset</a></li>
                        <li class="separator">/</li>
                        <li><a href="#" data-filter=".people">People</a></li>
                        <li class="separator">/</li>
                        <li><a href="#" data-filter=".animals">Animals</a></li>
                    </ul>
                </div>
            </div>
        </div>            
        <div class="row">
            <div class="col-md-12">
                <div id="gallery_container">

                    <?php
                    $filter='';
                    $gallery = array(
                    array('African Hornbill1.jpg', 'filters' => array('landscape','sunset')),
                    array('Better writing desk in Selous1.jpg', 'filters' => array('people')),
                    array('Giraffe heads held high1.jpg', 'filters' => array('sunset')),
                    array('Guest houses at Lewa Downs1.jpg', 'filters' => array('animals','people')),
                    array('IMG_3602.jpeg', 'filters' => array('landscape','people')),
                    array('IMG_3703.jpeg', 'filters' => array('landscape','sunset')),
                    array('Lunch time African style1.jpg', 'filters' => array('animals'))
                    );

                    for ($i=0; $i <count($gallery) ; $i++) { 

                        for ($j=0; $j < count($gallery[$i]['filters']); $j++) { 

                            $filter .= $gallery[$i]['filters'][$j]." ";

                        } ?>

                        <div class="photo <?=$filter?>">
                            <img src="images/galleries/<?=$gallery[$i][0] ?>" class="img-responsive" />
                        </div>

                    <?php unset($filter); } ?>

                </div>
            </div>
        </div>            
    </div>
</div>

<?php include("footer.php"); ?>