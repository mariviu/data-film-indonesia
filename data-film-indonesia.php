<?php
/*
 * Plugin Name: Data Film Indonesia
 * Plugin URI: https://github.com/mariviu/data-film-indonesia
 * Description: A plugin to display data on the number of Indonesian film viewers
 * Version: 1.0
 * Author: Anna Erdiawan
 * Author URI: https://erdiawan.com
 * License: MIT
 */

// Add CSS
// function id_films_data_enqueue_styles() {
//     wp_enqueue_style('id-films-data-styles', plugin_dir_url(__FILE__) . 'style.css');
// }
// add_action('wp_enqueue_scripts', 'id_films_data_enqueue_styles');

// Add Javascript
// function id_films_data_enqueue_scripts() {
//     wp_enqueue_script('id-films-data-scripts', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '1.0', true);
// }
// add_action('wp_enqueue_scripts', 'id_films_data_enqueue_scripts');

// Enqueue assets
add_action( 'wp_enqueue_scripts', 'id_films_data_assets' );
function id_films_data_assets() {
    wp_register_style( 'id-films-data', plugins_url( 'style.css' , __FILE__ ) );
    wp_register_script( 'id-films-data', plugins_url( 'script.js' , __FILE__ ) );

    wp_enqueue_style( 'id-films-data' );
    wp_enqueue_script( 'id-films-data', array('jquery'), '1.0', true );
}

// Read and parse the JSON file
function id_films_data_shortcode() {
    $defaultJson = 'default-film-indonesia.json';
    $updatedJson = 'film-indonesia.json';
    if (!file_exists($updatedJson)) {
        $jsonData = $defaultJson;
    } else {
        $jsonData = $updatedJson;
    }
    $id_films_data = file_get_contents($jsonData);
    $data = json_decode($id_films_data, true);

    $output = '<div id="film-data">';
    $output .= '<div> <span>Pilih Tahun </span> <select>';
    foreach ($data as $year => $movies) {
        $output .= '<option class="select-year" value="' . $year . '">'. $year . '</option>';
    }
    $output .= '</select>';

    foreach ($data as $year => $movies) {
        $output .= '<div id=' . $year . ' class="selected-year hidden"> ';
        foreach ($movies as $item) {
            $output .= '<div class="select-item"> <p> <span class="number">' . $item['number'] . '.</span><span class="title">' . $item['title'] . '</span></p>';
            $output .= '<p>' . $item['viewer'] . ' Penonton</p> </div>';
        }
        $output .= '</div>';
    }

    $output .= '<small><i>Sumber: filmindonesia.or.id</i> <br>Data diperbaharui 1 minggu sekali</small> </div>';

    return $output;
}
add_shortcode('id_films_data', 'id_films_data_shortcode');

?>
