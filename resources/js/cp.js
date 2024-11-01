jQuery(document).ready(function(){
    jQuery("#publish").on("click", function(){
        if(jQuery("#cp_crosspost").is(":checked")) window.open(jQuery("#cp_resources").attr("data-blank"), "cpwindow");
    });
});

function cp_checkLogin(userName, toggleFields){
    cp_log("in checkLogin");
    jQuery("#cp_settings").hide();
    jQuery.ajax({
        url: jQuery("#cp_resources").attr("data-login-url"),
        method: "get",
        crossDomain : true,
        xhrFields: {
            withCredentials: true
        },
        success: function(data, textStatus, jqXHR){
                cp_log(textStatus + JSON.stringify(data));
                if(!toggleFields) return;

                if(data.loggedIn == true){
                    jQuery(".loggedout").remove();
                    jQuery(".loggedin").show();
                    jQuery("#loggedInAs").html(data.username);
                    if(userName.length == 0){
                        jQuery("#urbello_user").val(data.username);
                        jQuery("#cp-submit").trigger("click");
                    }
                }else{
                    jQuery(".loggedin").remove();
                    jQuery(".loggedout").show();
                }
                jQuery("#cp_settings").show().find(".url").attr("href", data.url);
        }
    });
}

function cp_crossPost(obj){
    cp_log("in crossPost " + obj);
    jQuery.ajax({
        url: jQuery("#cp_resources").attr("data-post-url"),
        method: "put",
        crossDomain : true,
        xhrFields: {
            withCredentials: true
        },
        data: obj,
        success: function(data, textStatus, jqXHR){
                cp_log(textStatus + JSON.stringify(data));
                jQuery("#cp_form").attr("action", data.url).submit();
        }
    });
}

function cp_log(msg){
    //console.log(msg);
}


