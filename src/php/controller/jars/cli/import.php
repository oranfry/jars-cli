<?php

use OranFry\Jars\CLI\ImportListener;

if (FEEDBACK_FIFO) {
    echo "Opening feedback fifo for WRITE...\n";

    $jars->listen(new ImportListener(fopen(FEEDBACK_FIFO, 'w')));

    echo "Successfully opened feedback fifo for WRITE.\n";
}

echo "Importing\n\n";

while ($f = fgets(STDIN)) {
    [$hash, $date, $time, $json] = explode(' ', $f, 4);

    $timestamp = $date . ' ' . $time;
    $data = json_decode($json);

    echo $timestamp . "\n";

    $data = $jars->import($timestamp, array_values($data), BASE_VERSION, false, 0, true, $hash);

    echo "\n";

    if ($data === false) {
        error_response("Import error");
    }
}

return [];
