<?php
/*
 * Plugin Name: Data Film Indonesia
 * Plugin URI: https://github.com/mariviu/data-film-indonesia
 * Description: A plugin to display data on the number of Indonesian film viewers
 * Version: 1.2
 * Author: Anna Erdiawan
 * Author URI: https://erdiawan.com
 * License: MIT
 */

// Add CSS
function id_films_data_enqueue_styles() {
    wp_enqueue_style('id-films-data-styles', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'id_films_data_enqueue_styles');

// Add Javascript
function id_films_data_enqueue_scripts() {
    wp_enqueue_script('id-films-data-scripts', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'id_films_data_enqueue_scripts');

// Read and parse the JSON file
function id_films_data_shortcode() {
    $defaultJson = plugin_dir_path( __FILE__ ) . 'default-film-indonesia.json';
    $updatedJson = plugin_dir_path( __FILE__ ) . 'film-indonesia.json';
    if (!file_exists($updatedJson)) {
        $jsonData = $defaultJson;
    } else {
        $jsonData = $updatedJson;
    }
    $id_films_data = file_get_contents($jsonData);
    $data = json_decode($id_films_data, true);

    $output = '<div id="film-data">';
    $output .= '<div> <small>Pilih Tahun </small> <select>';
    
    foreach ($data as $year => $movies) {
        $selected = ($year == '2025') ? 'selected' : '';
        $output .= '<option class="select-year" value="' . $year . '" ' . $selected . '>'. $year . '</option>';
    }
    // $output .= '</select> <br><small>Kiri: Judul Film, Kanan: Total penonton bioskop</small>';
    $output .= '</select>';
    
    
    foreach ($data as $year => $movies) {
        $output .= '<div id="' . $year . '" class="selected-year">';
        $output .= '<table class="film-table">';
        $output .= '<thead><tr><th>@</th><th>Judul</th><th>Penonton</th></tr></thead>';
        $output .= '<tbody>';
        $limited_movies = array_slice($movies, 0, 10);
        foreach ($limited_movies as $item) {
            $output .= '<tr>';
            $output .= '<td>' . $item['number'] . '</td>';
            $output .= '<td>' . $item['title'] . '</td>';
            $output .= '<td>' . $item['viewer'] . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody></table></div>';
    }

    $output .= '<div class="footer-film"><span>Data diperbaharui 1 hari sekali</span><i>Sumber: filmindonesia.or.id</i></div></div>';

    return $output;
}
add_shortcode('id_films_data', 'id_films_data_shortcode');

?>
