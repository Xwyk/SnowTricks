jQuery(document).ready(function () {
    // Set default image for all img
    $( "img" ).on('error', function() {
        // If default image isn't set (if default is already set, DON'T LOOP)
        if ($(this).attr("src") !== "/img/default.jpg"){
            $( this ).attr( "src", "/img/default.jpg" );
        }
    });
    showAlerts();
    $('.toast').toast('show');
});

/**
 * Display & hide alerts with slide animation
 */
function showAlerts(){
    // Hide element
    $('.alert').hide().removeClass('d-none');
    // Show element
    $('.alert').slideDown(1000)
    // Hide element after X seconds, then remove it
    setTimeout(()=>{
        $('.alert').slideUp(1000, ()=>{
            $('.alert').remove();
        });
    }, 5000);
}