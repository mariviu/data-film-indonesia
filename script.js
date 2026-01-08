jQuery(document).ready(function($) {
    // Sembunyikan semua
    $(".selected-year").hide();

    // Tampilkan hanya tahun default (2025)
    $(".selected-year").each(function() {
        if ($(this).attr("id") === "2025") {
            $(this).show();
        }
    });

    // Tangani perubahan dropdown
    $("#film-data select").on("change", function() {
        var selectedYear = $(this).val();
        $(".selected-year").hide();
        $("#" + selectedYear).fadeIn();
    });
});
