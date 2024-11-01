<?php
    if(self::getOption("floating") == 1){
        $floating_img   = self::getOption("floating_img");
        $floating_pos   = self::getOption("floating_pos");
        $link           = self::getImageLink();
        $link           = "<div class='cp_floating cp_$floating_pos'>"
                        . "<a href='{$link}' target='_new'>"
                        . "<img src='" . __CROSS_POSTER_IMAGES__ . $floating_img . "'>" 
                        . "</a></div>";
        echo $link;
    }
?>

<div style="display:none !important">Powered by PhiStream: www.phistream.com</div>