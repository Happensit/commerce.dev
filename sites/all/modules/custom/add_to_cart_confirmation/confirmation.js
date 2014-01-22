(function($) {
    $(document).ready(function() {
        $("#cart_msg").click(function(e){
            if($(e.target).attr("id") == "cart_msg_close"){
                $("#cart_msg").html("");
            }
        });
    });
})(jQuery);