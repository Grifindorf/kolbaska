
<div class="tp-banner-container">
    <div class="tp-banner" >

        <ul>	<!-- SLIDE  -->

            <?php
            $db = JFactory::getDbo();
            $query = "SELECT * FROM #__shares WHERE show_home = 1 and enabled = 1 and date_end > ".$db->quote(date('Y-m-d H:m:s'))." ORDER BY ordering DESC";
            $db->setQuery($query);
            $results = $db->loadObjectList();
            foreach ($results as $res) {
                if($res->typeeffect == 1) { ?>
                    <li data-transition="slidehorizontal" data-slotamount="1" data-masterspeed="600">
                        <img src="<?=$res->home_banner?>" style="background-color:<?=$res->backgroundcolor?>" alt=""  data-bgfit="cover" data-bgposition="center bottom" data-bgrepeat="no-repeat">

                        <!-- LAYERS NR. 1 -->
                        <!--<div class="tp-caption lfl"
                             data-x="0"
                             data-y="0"
                             data-speed="800"
                             data-start="1200"
                             data-easing="Power4.easeOut"
                             data-endspeed="300"
                             data-endeasing="Linear.easeNone"
                             data-captionhidden="off"><img class="img-responsive" src="<?=$res->home_banner?>" alt="" />
                        </div>-->
                        <!-- LAYERS NR. 2 -->
                        <div class="tp-caption lfr large_bold_grey heading white"
                             data-x="center"
                             data-y="250"
                             data-speed="800"
                             data-start="1000"
                             data-easing="Power4.easeOut"
                             data-endspeed="300"
                             data-endeasing="Linear.easeNone"
                             data-captionhidden="off"><a href="<?=$res->custom_link?>"><?=$res->home_banner_name?></a>
                        </div>
                        <!-- LAYER NR. 3 -->
                        <!--<div class="tp-caption whitedivider3px customin customout tp-resizeme"
                             data-x="right" data-hoffset="-20"
                             data-y="210" data-voffset="0"
                             data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-speed="700"
                             data-start="2300"
                             data-easing="Power3.easeInOut"
                             data-splitin="none"
                             data-splitout="none"
                             data-elementdelay="0.1"
                             data-endelementdelay="0.1"
                             data-endspeed="500"
                             style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;">&nbsp;
                        </div>-->
                        <!-- LAYER NR. 4 -->
                        <!--<div class="tp-caption finewide_medium_white randomrotate customout tp-resizeme"
                             data-x="right" data-hoffset="-10"
                             data-y="245" data-voffset="0"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-speed="1000"
                             data-start="2700"
                             data-easing="Power3.easeInOut"
                             data-splitin="chars"
                             data-splitout="chars"
                             data-elementdelay="0.08"
                             data-endelementdelay="0.08"
                             data-endspeed="500"
                             style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;">Hamburger
                        </div>-->
                        <!-- LAYER NR. 5 -->
                        <div class="tp-caption white customin customout tp-resizeme text-right vrstylecaption"
                             data-x="center"
                             data-y="180"
                             data-customin="x:0;y:50;z:0;rotationX:-120;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 0%;"
                             data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                             data-speed="1000"
                             data-start="1500"
                             data-easing="Power3.easeInOut"
                             data-splitin="lines"
                             data-splitout="lines"
                             data-elementdelay="0.2"
                             data-endelementdelay="0.08"
                             data-endspeed="300"
                             style="z-index: 10; max-width: auto; max-height: auto; white-space: nowrap;"><?=$res->home_banner_description?>
                        </div>

                    </li>
                <?php } elseif($res->typeeffect == 2) { ?>
                    <li data-transition="slidehorizontal" data-slotamount="1" data-masterspeed="600" >
                        <div style="background: url(<?=$res->home_banner?>) center center; width: 100%; height: 100%; display: table;">
                            <?php
                                if($res->id == 1){
                                    ?>
                                    <div class="playvideobutton">
                                        <a href="#modalvideo" data-toggle="modal">
                                            <img src="/images/play.png" width="128px" />
                                        </a>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </li>
                <?php } elseif($res->typeeffect == 3) { ?>
                    <li data-transition="slidehorizontal" data-slotamount="1" data-masterspeed="600" >
                        <div style="width: 100%; height: 500px">
                            <div class="container">
                                <video width="100%" height="640" controls>
                                    <source src="<?=$res->home_banner_description?>" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </li>
                <?php }
            }
            ?>

<?php /*
            <li data-transition="fade" data-slotamount="7" data-masterspeed="1500" >
                <!-- MAIN IMAGE -->
                <img src="/templates/kolbaska/img/slider/slide2.jpg"  alt=""  data-bgfit="cover" data-bgposition="center bottom" data-bgrepeat="no-repeat">

                <!-- LAYERS -->
                <!-- LAYER NR. 1 -->
                <div class="tp-caption lfl largeblackbg br-red"
                     data-x="20"
                     data-y="100"
                     data-speed="1500"
                     data-start="1200"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3">We Make Delicious...
                </div>
                <!-- LAYER NR. 2.0 -->
                <div class="tp-caption lfl medium_bg_darkblue br-green"
                     data-x="20"
                     data-y="200"
                     data-speed="1500"
                     data-start="1800"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Power4.easeIn"
                     data-captionhidden="off">Consectetur Adipisicing
                </div>
                <!-- LAYER NR. 2.1 -->
                <div class="tp-caption lfl medium_bg_darkblue br-lblue"
                     data-x="20"
                     data-y="250"
                     data-speed="1500"
                     data-start="2100"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3">Sed do Seusmod
                </div>
                <!-- LAYER NR. 2.2 -->
                <div class="tp-caption lfl medium_bg_darkblue br-purple"
                     data-x="20"
                     data-y="300"
                     data-speed="1500"
                     data-start="2400"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3">Incididunt ut Labore
                </div>
                <!-- LAYER NR. 2.3 -->
                <div class="tp-caption lfl medium_bg_darkblue br-orange"
                     data-x="20"
                     data-y="350"
                     data-speed="1500"
                     data-start="2700"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3">Excepteur Sint
                </div>
                <!-- LAYER NR. 3.0 -->
                <div class="tp-caption customin customout"
                     data-x="right" data-hoffset="-50"
                     data-y="100"
                     data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="400"
                     data-start="3600"
                     data-easing="Power3.easeInOut"
                     data-endspeed="300"
                     style="z-index: 5"><img class="slide-img img-responsive" src="/templates/kolbaska/img/slider/s21.png" alt="" />
                </div>
                <!-- LAYER NR. 3.1 -->
                <div class="tp-caption customin customout"
                     data-x="right" data-hoffset="-120"
                     data-y="130"
                     data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="400"
                     data-start="3900"
                     data-easing="Power3.easeInOut"
                     data-endspeed="300"
                     style="z-index: 6"><img class="slide-img img-responsive" src="/templates/kolbaska/img/slider/s22.png" alt="" />
                </div>
                <!-- LAYER NR. 3.2 -->
                <div class="tp-caption customin customout"
                     data-x="right" data-hoffset="-10"
                     data-y="160"
                     data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="400"
                     data-start="4200"
                     data-easing="Power3.easeInOut"
                     data-endspeed="300"
                     style="z-index: 7"><img class="slide-img img-responsive" src="/templates/kolbaska/img/slider/s23.png" alt="" />
                </div>
                <!-- LAYER NR. 3.3 -->
                <div class="tp-caption customin customout"
                     data-x="right" data-hoffset="-80"
                     data-y="190"
                     data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="400"
                     data-start="4500"
                     data-easing="Power3.easeInOut"
                     data-endspeed="300"
                     style="z-index: 8"><img class="slide-img img-responsive" src="/templates/kolbaska/img/slider/s24.png" alt="" />
                </div>
            </li>
            <li data-transition="zoomin" data-slotamount="6" data-masterspeed="400" >
                <!-- MAIN IMAGE -->
                <img src="/templates/kolbaska/img/slider/transparent.png" style="background-color:#fff" alt=""  data-bgfit="cover" data-bgposition="center bottom" data-bgrepeat="no-repeat">

                <!-- LAYERS -->
                <!-- LAYER NR. 1 -->
                <div class="tp-caption sfl modern_medium_light"
                     data-x="20"
                     data-y="90"
                     data-speed="800"
                     data-start="1000"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3">The New
                </div>
                <!-- LAYER NR. 1.1 -->
                <div class="tp-caption large_bold_grey heading customin customout"
                     data-x="10"
                     data-y="125"
                     data-splitin="chars"
                     data-splitout="chars"
                     data-elementdelay="0.05"
                     data-start="1500"
                     data-speed="900"
                     data-easing="Back.easeOut"
                     data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-endspeed="500"
                     data-endeasing="Power3.easeInOut"
                     data-captionhidden="on"
                     style="z-index:5">CakeFactory
                </div>
                <!-- LAYER NR. 2 -->
                <div class="tp-caption customin customout"
                     data-x="right"
                     data-y="100"
                     data-customin="x:50;y:150;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.5;scaleY:0.5;skewX:0;skewY:0;opacity:0;transformPerspective:0;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="800"
                     data-start="2000"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3"><img class="img-responsive" src="/templates/kolbaska/img/slider/s11.png" alt="" />
                </div>
                <!-- LAYER NR. 2.1 -->
                <div class="tp-caption customin customout"
                     data-x="right"
                     data-y="100"
                     data-customin="x:50;y:150;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.5;scaleY:0.5;skewX:0;skewY:0;opacity:0;transformPerspective:0;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="800"
                     data-start="2300"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3"><img class="img-responsive" src="/templates/kolbaska/img/slider/s12.png" alt="" />
                </div>
                <!-- LAYER NR. 2.2 -->
                <div class="tp-caption customin customout"
                     data-x="right"
                     data-y="100"
                     data-customin="x:50;y:150;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.5;scaleY:0.5;skewX:0;skewY:0;opacity:0;transformPerspective:0;transformOrigin:50% 50%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="800"
                     data-start="2600"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3"><img class="img-responsive" src="/templates/kolbaska/img/slider/s13.png" alt="" />
                </div>
                <!-- LAYER NR. 2.3 -->
                <div class="tp-caption sft"
                     data-x="right" data-hoffset="-400"
                     data-y="80"
                     data-speed="1000"
                     data-start="3000"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 10"><span class="price-tag br-white">30%<br />Off</span>
                </div>
                <!-- LAYER NR. 3 -->
                <div class="tp-caption finewide_verysmall_white_mw paragraph customin customout tp-resizeme"
                     data-x="20"
                     data-y="210"
                     data-customin="x:0;y:50;z:0;rotationX:-120;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 0%;"
                     data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                     data-speed="1000"
                     data-start="3600"
                     data-easing="Power3.easeInOut"
                     data-splitin="lines"
                     data-splitout="lines"
                     data-elementdelay="0.2"
                     data-endelementdelay="0.08"
                     data-endspeed="300"
                     style="z-index: 10; max-width: auto; max-height: auto; white-space: nowrap;">Lorem ipsum dolor sit amet, consetetur<br/>  sadipscing elitr, sed diam nonumy<br/>  eirmod tempor invidunt ut labore et<br/>  dolore magna aliquyam erat, sed diam <br/> voluptua. At vero eos et accusam.
                </div>
                <!-- LAYER NR. 4 -->
                <div class="tp-caption sfb"
                     data-x="20"
                     data-y="335"
                     data-speed="800"
                     data-start="4500"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 11"><a class="btn btn-danger" href="#">Download</a> <a class="btn btn-success hidden-xs" href="#">Purchase Now</a>
                </div>
            </li>
            <li data-transition="cube" data-slotamount="7" data-masterspeed="600" >
                <!-- MAIN IMAGE -->
                <img src="/templates/kolbaska/img/slider/slide2.jpg"  alt=""  data-bgfit="cover" data-bgposition="center bottom" data-bgrepeat="no-repeat">
                <!-- LAYERS NR. 1 -->
                <div class="tp-caption lfl"
                     data-x="110"
                     data-y="130"
                     data-speed="800"
                     data-start="1500"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Power4.easeIn"
                     data-captionhidden="off"><img src="/templates/kolbaska/img/slider/s31.png" class="img-responsive" alt="" />
                </div>
                <!-- LAYERS NR. 2 -->
                <div class="tp-caption lfl"
                     data-x="80"
                     data-y="265"
                     data-speed="800"
                     data-start="2200"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Power4.easeIn"
                     data-captionhidden="off"><img src="/templates/kolbaska/img/slider/s33.png" class="img-responsive" alt="" />
                </div>
                <!-- LAYERS NR. 3 -->
                <div class="tp-caption lfl"
                     data-x="450"
                     data-y="312"
                     data-speed="800"
                     data-start="2700"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Power4.easeIn"
                     data-captionhidden="off"><img src="/templates/kolbaska/img/slider/s34.png" class="img-responsive" alt="" />
                </div>
                <!-- LAYERS NR. 4 -->
                <div class="tp-caption sfr  thinheadline_dark white"
                     data-x="right" data-hoffset="-10"
                     data-y="90"
                     data-speed="800"
                     data-start="3200"
                     data-easing="Power4.easeOut"
                     data-endspeed="500"
                     data-endeasing="Power4.easeIn"
                     style="z-index: 3">Online
                </div>
                <!-- LAYERS NR. 4.1 -->
                <div class="tp-caption lfr largepinkbg br-green"
                     data-x="right" data-hoffset="-10"
                     data-y="135"
                     data-speed="800"
                     data-start="3500"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Linear.easeNone"
                     data-captionhidden="off">Seats Reserve
                </div>
                <!-- LAYERS NR. 5 -->
                <div class="tp-caption skewfromright medium_text text-right paragraph"
                     data-x="right" data-hoffset="-10"
                     data-y="225"
                     data-speed="800"
                     data-start="3800"
                     data-easing="Power4.easeOut"
                     data-endspeed="400"
                     data-endeasing="Power4.easeOut"
                     data-captionhidden="off">At vero eos etntium accu amet, adipisicing samus iusto<br />praese  delen itieos etconsectetur atque corrupti<br />praese etntiumder delen itierrupti.
                </div>
                <!-- LAYERS NR. 6 // -->
                <div class="tp-caption lfr modern_big_redbg br-red"
                     data-x="right"
                     data-hoffset="-10"
                     data-y="315"
                     data-speed="1500"
                     data-start="4100"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Linear.easeNone"
                     data-captionhidden="off">Desktop or Laptop
                </div>
                <!-- LAYERS NR. 6.1 // -->
                <div class="tp-caption lfr modern_big_redbg br-yellow"
                     data-x="right"
                     data-hoffset="-10"
                     data-y="375"
                     data-speed="1500"
                     data-start="4400"
                     data-easing="Power4.easeOut"
                     data-endspeed="300"
                     data-endeasing="Linear.easeNone"
                     data-captionhidden="off">Tablet or Phone
                </div>
            </li>
            */ ?>

        </ul>
        <!-- Banner Timer -->
        <!--<div class="tp-bannertimer"></div>-->
    </div>
</div>
