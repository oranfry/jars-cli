<?php

use jars\cli\ImportListener;

$jars->masterlog_check();

if (FEEDBACK_FIFO) {
    echo "Opening feedback fifo for WRITE...\n";

    $jars->listen(new ImportListener(fopen(FEEDBACK_FIFO, 'w')));

    echo "Successfully opened feedback fifo for WRITE.\n";
}

echo "Importing\n\n";

$unlock_pin = $jars->lock();

while ($f = fgets(STDIN)) {
    [$hash, $date, $time, $json] = explode(' ', $f, 4);

    $timestamp = $date . ' ' . $time;
    $data = json_decode($json);

    echo $timestamp . "\n";

    $data = $jars->import($timestamp, array_values($data), null, false, 0, true);

    echo "\n";

    if ($data === false) {
        error_response("Import error");
    }
}

$jars->unlock($unlock_pin);

return [];
