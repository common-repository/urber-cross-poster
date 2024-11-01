<div
    id="cp_resources"
    data-blank="<?php echo __CROSS_POSTER_RESOURCES__ ?>blank.php"
    data-post-url="<?php echo __CROSS_POSTER_API_URL_POST__ ?>"
    data-login-url="<?php echo __CROSS_POSTER_API_URL_LOGIN__ ?>"
>
<?php
    if(isset($_SESSION["cp_api_json"])){
        $params = json_decode($_SESSION["cp_api_json"], true);
?>
<script>
    cp_crossPost(
        {
            account: {
                emailAddress: "<?php echo $params['account']['emailAddress'];?>",
                username: "<?php echo $params['account']['username'];?>"
            },
            post: {
                text: "<?php echo $params['post']['text'];?>",
                subject: "<?php echo $params['post']['subject'];?>",
                imageUrl: "<?php echo $params['post']['imageUrl'];?>",
                postLink: "<?php echo $params['post']['postLink'];?>",
                postID: "<?php echo $params['post']['postID'];?>"
            }
        }
    );
</script>
<?php
        unset($_SESSION["cp_api_json"]);
    }
?>

<form name="cp_form" id="cp_form" target="cpwindow"></form>