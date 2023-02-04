<?php

use jars\cli\ImportListener;

$jars->masterlog_check();

if (FEEDBACK_FIFO) {
    echo "Opening feedback fifo for WRITE...\n";

    $jars->listen(new ImportListener(fopen(FEEDBACK_FIFO, 'w')));

    echo "Successfully opened feedback fifo for WRITE.\n";
}

echo "Importing\n\n";

while ($f = fgets(STDIN)) {
    [$hash, $date, $time, $json] = explode(' ', $f, 4);

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

return [];
