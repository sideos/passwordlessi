jQuery( document ).ready(function() {
    jQuery('#send_credential').click(function() {
        const v = jQuery('#users').find(":selected").val()
        const values = v.split('|')
        jQuery.ajax({
            url:"/wp-admin/admin-ajax.php",
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'send_credential',
                email: values[0],
                name: values[1]
            },
            success:function(response){
                if(response.error_code == 1){
                    alert(SIDEOS.messages[0])
                } else {
                    alert(SIDEOS.messages[1])
                }
            }
        });
    })
    jQuery('#showtoken').click(function() {
        jQuery('#token').toggleClass('blurred')
    })
});


