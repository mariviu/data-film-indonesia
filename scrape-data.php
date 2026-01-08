<?php
/**
 * Scraper for filmindonesia.or.id
 * Handles specific column mapping for the main 'penonton' landing page.
 */

$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36"
        )
    )
);

$urls = array(
    'https://filmindonesia.or.id/film/penonton?tahun=2026',
    'https://filmindonesia.or.id/film/penonton?tahun=2025',
    'https://filmindonesia.or.id/film/penonton?tahun=2024',
    'https://filmindonesia.or.id/film/penonton?tahun=2023',
    'https://filmindonesia.or.id/film/penonton?tahun=2022',
    'https://filmindonesia.or.id/film/penonton?tahun=2021',
    'https://filmindonesia.or.id/film/penonton?tahun=2020',
    'https://filmindonesia.or.id/film/penonton?tahun=2019',
    'https://filmindonesia.or.id/film/penonton?tahun=2018',
    'https://filmindonesia.or.id/film/penonton?tahun=2017',
    'https://filmindonesia.or.id/film/penonton?tahun=2016',
    'https://filmindonesia.or.id/film/penonton?tahun=2015',
    'https://filmindonesia.or.id/film/penonton?tahun=2014',
    'https://filmindonesia.or.id/film/penonton?tahun=2013',
    'https://filmindonesia.or.id/film/penonton?tahun=2012',
    'https://filmindonesia.or.id/film/penonton?tahun=2011',
    'https://filmindonesia.or.id/film/penonton?tahun=2010',
    'https://filmindonesia.or.id/film/penonton?tahun=2009',
    'https://filmindonesia.or.id/film/penonton?tahun=2008',
    'https://filmindonesia.or.id/film/penonton?tahun=2007',
    'https://filmindonesia.or.id/film/penonton'
);

$data = array();
$previous_year = null;

foreach ($urls as $url) {
    $html = @file_get_contents($url, false, $context);
    if ($html === false) {
        echo "Failed to fetch: $url\n";
        continue;
    }

    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $xpath = new DOMXPath($doc);
    $table_rows = $xpath->query('//table/tbody/tr');

    // Determine Year Label
    if (preg_match('/tahun=([\d-]+)/', $url, $matches)) {
        $year = $matches[1];
    } else {
        $year = '2007-2026'; // For the main URL without query params
    }

    if ($previous_year !== $year) {
        if (!isset($data[$year])) {
            $data[$year] = array();
        }
        $previous_year = $year;
    }

    echo "Processing Year: $year...\n";

    foreach ($table_rows as $row) {
        $cells = $xpath->query('td', $row);
        
        // Ensure we have enough cells to avoid index errors
        if ($cells->length >= 3) {
            $number = trim($cells[0]->nodeValue);
            $title = trim($cells[1]->nodeValue);
            
            // SPECIAL RULE: For the specific URL, viewers are in the 4th column (index 3)
            // For all other URLs (yearly lists), viewers are in the 3rd column (index 2)
            if ($url === 'https://filmindonesia.or.id/film/penonton') {
                $viewer = isset($cells[3]) ? trim($cells[3]->nodeValue) : '0';
            } else {
                $viewer = trim($cells[2]->nodeValue);
            }

            $data[$year][] = array(
                'number' => $number,
                'title' => $title,
                'viewer' => $viewer
            );
        }
    }
}

$json = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('film-indonesia.json', $json);
echo "\nSuccess! Data saved to film-indonesia.json\n";
?>