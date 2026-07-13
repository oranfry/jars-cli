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

    echo $timestamp . "\n";

    $jars->import($timestamp, array_values(json_decode($json)), null, false, 0, true);

    echo "\n";
}

$jars->unlockPrimary($pin);

return [];
