<script>
    jQuery(document).ready(function(){
        cp_checkLogin('<?php echo self::getOption("username");?>', true);
    });
</script>

<div class="wrap">
    <h2><img src="<?php echo __CROSS_POSTER_IMAGES__ . "icon5.png";?>"><?php _e("Settings", __CROSS_POSTER_PLUGIN_SLUG__);?></h2>

    <div id="cp_settings" style="display: none">
        <div class="error loggedout" style="display: none"><?php _e("You are not logged in", __CROSS_POSTER_PLUGIN_SLUG__);?></div>
        <div class="updated loggedin" style="display: none"><?php _e("You are logged in", __CROSS_POSTER_PLUGIN_SLUG__);?></div>

        <form action="" method="post" id="settingsForm">
        <table class="settings" cellspacing="5">
            <tr>
                <th colspan="2"><label><?php echo _e("My URBELLO username", __CROSS_POSTER_PLUGIN_SLUG__);?></label></th>
            </tr>
            <tr>
                <td><input type="text" name="urbello_user" id="urbello_user" value="<?php echo self::getOption("username");?>"></td>
                <td>
                    <p class="description loggedout"><a class="url" target="_blank"><?php _e("Don't have an URBELLO username? REGISTER HERE", __CROSS_POSTER_PLUGIN_SLUG__);?></a></p>
                    <p class="description loggedin"><a class="url" target="_blank"><?php _e("You are logged into URBELLO as ", __CROSS_POSTER_PLUGIN_SLUG__);?><span id="loggedInAs"></span></a></p>
                </td>
            </tr>
            <tr>
                <th colspan="2"><?php _e("Share my blog posts automatically on URBELLO", __CROSS_POSTER_PLUGIN_SLUG__);?></th>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="checkbox" name="share" id="share" value="1" <?php echo self::getOption("share") == 1 ? "checked" : ""?>>
                    <label for="share"><?php _e("Check this!", __CROSS_POSTER_PLUGIN_SLUG__);?></label>
                </td>
            </tr>
            <tr>
                <th colspan="2"><?php _e("I want a floating URBELLO icon", __CROSS_POSTER_PLUGIN_SLUG__);?></th>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" name="floating" id="floating" value="1" <?php echo self::getOption("floating") == 1 ? "checked" : ""?>>
                    <label for="floating"><?php _e("Check this!", __CROSS_POSTER_PLUGIN_SLUG__);?></label>
                </td>
                <td><?php _e("Anchor to the", __CROSS_POSTER_PLUGIN_SLUG__);?></td>
            </tr>
            <tr>
                <td>
                <?php 
                    for($x = 1; $x < 4; $x++){
                        $val    = "floating{$x}.png";
                ?>
                    <input type="radio" name="floating_img" id="floating_img<?php echo $x;?>" value="<?php echo $val;?>" <?php echo self::getOption("floating_img") == $val ? "checked" : ""?>>
                    <label for="floating_img<?php echo $x;?>"><img src="<?php echo __CROSS_POSTER_IMAGES__ . "floating" . $x . ".png";?>"></label>
                <?php } ?>
                </td>
                <td>
                <?php
                    $x      = 0;
                    $pos    = array("top left", "bottom left", "top right", "bottom right");
                    foreach($pos as $name){
                        $val    = str_replace(" ", "", $name);
                        $x++;
                ?>
                    <input type="radio" name="floating_pos" id="floating_pos<?php echo $x;?>" value="<?php echo $val;?>" <?php echo self::getOption("floating_pos") == $val ? "checked" : ""?>>
                    <label for="floating_pos<?php echo $x;?>"><?php _e($name, __CROSS_POSTER_PLUGIN_SLUG__);?></label>
                <?php
                    }
                ?>
                </td>
            </tr>
            <tr>
                <th colspan="2"><?php _e("I want an URBELLO side panel icon", __CROSS_POSTER_PLUGIN_SLUG__);?></th>
            </tr>
            <tr>
                <td>
                    <input type="checkbox" name="sidepanel" id="sidepanel" value="1" <?php echo self::getOption("sidepanel") == 1 ? "checked" : ""?>>
                    <label for="sidepanel"><?php _e("Check this!", __CROSS_POSTER_PLUGIN_SLUG__);?></label>
                </td>
                <td><?php _e("Side panel icon", __CROSS_POSTER_PLUGIN_SLUG__);?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                <?php 
                    for($x = 1; $x < 4; $x++){
                        $val        = "icon{$x}.png";
                ?>
                    <div class="panel-icons">
                        <input type="radio" name="sidepanel_img" id="sidepanel_img<?php echo $x;?>" value="<?php echo $val;?>" <?php echo self::getOption("sidepanel_img") == $val ? "checked" : ""?>>
                        <label for="sidepanel_img<?php echo $x;?>"><img src="<?php echo __CROSS_POSTER_IMAGES__ . "icon" . $x . ".png";?>"></label>
                    </div>
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                <?php
                    for($x = 4; $x < 7; $x++){
                        $val        = "icon{$x}.png";
                ?>
                    <div class="panel-icons">
                        <input type="radio" name="sidepanel_img" id="sidepanel_img<?php echo $x;?>" value="<?php echo $val;?>" <?php echo self::getOption("sidepanel_img") == $val ? "checked" : ""?>>
                        <label for="sidepanel_img<?php echo $x;?>"><img src="<?php echo __CROSS_POSTER_IMAGES__ . "icon" . $x . ".png";?>"></label>
                    </div>
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                <?php
                    for($x = 7; $x < 8; $x++){
                        $val        = "icon{$x}.png";
                ?>
                    <div class="panel-icons">
                        <input type="radio" name="sidepanel_img" id="sidepanel_img<?php echo $x;?>" value="<?php echo $val;?>" <?php echo self::getOption("sidepanel_img") == $val ? "checked" : ""?>>
                        <label for="sidepanel_img<?php echo $x;?>"><img src="<?php echo __CROSS_POSTER_IMAGES__ . "icon" . $x . ".png";?>"></label>
                    </div>
                <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" class="button button-primary" name="cp-submit" id="cp-submit" value="Save"></td>
            </tr>
        </table>
        </form>
    </div>
</div>