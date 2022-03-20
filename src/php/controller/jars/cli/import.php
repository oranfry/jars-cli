<?php

global $linetype, $id_map, $new_subs;

$id_map = [];
$new_subs = [];

$jars->masterlog_check();

echo "Importing\n\n";

while ($f = fgets(STDIN)) {
    // list($hash, $date, $time, $json) = explode(' ', $f, 4);
    list($date, $time, $linetype, $json) = explode(' ', $f, 4);

    // dd(
    //     'date', $date,
    //     'time', $time,
    //     'linetype', $linetype,
    //     'json', $json,
    // );

    $timestamp = "{$date} {$time}";
    $data = json_decode($json);

    if (function_exists('import_presave')) {
        $result = import_presave($data, $jars, $timestamp);

        if (is_array($result)) {
            extract($result);
        }
    }

    echo $timestamp . "\n";

    $data = $jars->import($timestamp, array_values($data), false, 0);

    echo "\n";

    if ($data === false) {
        error_response("Import error");
    }

    if (function_exists('import_postsave')) {
        $result = import_postsave($data, $jars, $timestamp);

        if (is_array($result)) {
            extract($result);
        }
    }
}

echo "            'subs' => [\n";

foreach ($new_subs as $pointer => $id) {
    echo "                $pointer => '$id',\n";
}

echo "            ]\n";

return [];
