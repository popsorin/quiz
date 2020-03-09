jQuery(document).ready(function () {
    console.log('document is ready');

    jQuery('#ajaxjQuery').on('click', function () {
        jQuery.ajax({
            type:"GET",
            url:"https://jsonplaceholder.typicode.com/users",
            dataType:"json",
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.log(error.statusText + " " + error.status)
            }
        })
    });
});
