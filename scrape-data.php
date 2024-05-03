<?php
$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36"
        )
    )
);

$urls = array(
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
    'https://filmindonesia.or.id/film/penonton?tahun=1973-1994',
    'https://filmindonesia.or.id/film/penonton'
);
  
$data = array();
foreach ($urls as $url) {
    $html = file_get_contents($url, false, $context);

    $doc = new DOMDocument();

    // Suppress errors for invalid HTML
    @$doc->loadHTML($html);

    $xpath = new DOMXPath($doc);

    $table_rows = $xpath->query('//table/tbody/tr');

    if (preg_match('/tahun=([\d-]+)/', $url, $matches)) {
        $tahun = $matches[1];
        if (strpos($tahun, '-') !== false) {
            $year = $tahun;
        } else {
            $year = $tahun;
        }
    } else {
        $year = '2007-2024';
    }

    echo "Year: $year\n";

    foreach ($table_rows as $row) {
        $cells = $xpath->query('td', $row);
        $number = $cells[0]->nodeValue;
        $title = $cells[1]->nodeValue;
        $viewer = $cells[2]->nodeValue;
    
        $data[$year][] = array(
            'number' => $number,
            'title' => $title,
            'viewer' => $viewer
        );
    }
}

$json = json_encode($data);

file_put_contents('film-indonesia.json', $json);
echo "Data downloaded as film-indonesia.json file";

?>
