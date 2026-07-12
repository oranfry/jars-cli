<?php

use OranFry\Jars\CLI\ImportListener;
use OranFry\Jars\Contract\Exception;

if (FEEDBACK_FIFO) {
    echo "Opening feedback fifo for WRITE...\n";

    $jars->listen(new ImportListener(fopen(FEEDBACK_FIFO, 'w')));

    echo "Successfully opened feedback fifo for WRITE.\n";
}

echo "Importing\n\n";

if (!$pin = $jars->lockPrimary()) {
    throw new Exception('Unable to lock jars');
}

while ($f = fgets(STDIN)) {
    [$date, $time, $json] = explode(' ', $f, 3);

    $timestamp = $date . ' ' . $time;
    $data = json_decode($json);

    echo $timestamp . "\n";

    $data = $jars->import($timestamp, array_values($data), null, false, 0, true);

    echo "\n";

    if ($data === false) {
        throw new Exception('Import error');
    }
}

$jars->unlockPrimary($pin);

return [];
