<?php
$context = stream_context_create(
    array(
        "http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36"
        )
    )
);

$urls = array(
    'http://filmindonesia.or.id/movie/viewer/2023',
    'http://filmindonesia.or.id/movie/viewer/2022',
    'http://filmindonesia.or.id/movie/viewer/2021',
    'http://filmindonesia.or.id/movie/viewer/2020',
    'http://filmindonesia.or.id/movie/viewer/2019',
    'http://filmindonesia.or.id/movie/viewer/2018',
    'http://filmindonesia.or.id/movie/viewer/2017',
    'http://filmindonesia.or.id/movie/viewer/2016',
    'http://filmindonesia.or.id/movie/viewer/2015',
    'http://filmindonesia.or.id/movie/viewer/2014',
    'http://filmindonesia.or.id/movie/viewer/2012',
    'http://filmindonesia.or.id/movie/viewer/2011',
    'http://filmindonesia.or.id/movie/viewer/2010',
    'http://filmindonesia.or.id/movie/viewer/2009',
    'http://filmindonesia.or.id/movie/viewer/2008',
    'http://filmindonesia.or.id/movie/viewer/2007',
    // 'http://filmindonesia.or.id/movie/viewer/1973-1994'
    // 'http://filmindonesia.or.id/movie/viewer/2007-2023',
);
  
$data = array();
foreach ($urls as $url) {
    $html = file_get_contents($url, false, $context);

    $doc = new DOMDocument();

    // Suppress errors for invalid HTML
    @$doc->loadHTML($html);

    $xpath = new DOMXPath($doc);

    $table_rows = $xpath->query('//table/tbody/tr');

    $pattern = '/\/viewer\/(\d{4})/';
    if (preg_match($pattern, $url, $matches)) {
        $year = $matches[1];
    }

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
