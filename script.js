jQuery(function ($) {
    //unhides first option content
    $("#2023").fadeIn();
    //listen to dropdown for change
    $("#film-data").change(function(){
      //rehide content on change
      $('.selected-year').hide();
      //unhides current item
      $('#'+$('#film-data option:selected').val()).fadeIn();
    });
});