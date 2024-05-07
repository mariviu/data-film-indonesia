jQuery(document).ready( function( $ ) {
    //hides dropdown content
    $(".selected-year").hide();
    //unhides first option content
    $("#2024").fadeIn();
    //listen to dropdown for change
    $("#film-data").change(function(){
      //rehide content on change
      $('.selected-year').hide();
      //unhides current item
      $('#'+$('#film-data option:selected').val()).fadeIn();
    });
});