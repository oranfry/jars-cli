<?php

use OranFry\Jars\CLI\ImportListener;
use OranFry\Jars\Contract\Exception;

if (!function_exists('numberToSiSuffix')) {
    function numberToSiSuffix($number) {
        $suffixes = ['', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
        $power = floor(log(abs($number), 1000));
        
        // Handle numbers less than 1 (no suffix)
        if ($power < 1 || !isset($suffixes[$power])) {
            return number_format($number);
        }
        
        $num = $number / pow(1000, $power);
        return number_format($num, 1) . $suffixes[$power];
    }
}

if (FEEDBACK_FIFO) {
    echo "Opening feedback fifo for WRITE...\n";

    $jars->listen(new ImportListener(fopen(FEEDBACK_FIFO, 'w')));

    echo "Successfully opened feedback fifo for WRITE.\n";
}

echo "Importing\n\n";

while ($f = fgets(STDIN)) {
    [$date, $time, $json] = explode(' ', $f, 3);

    $timestamp = $date . ' ' . $time;

    echo $timestamp . "\n";

    $jars->import($timestamp, array_values(json_decode($json)), null, false, 0, true);
    echo "\n";
    echo 'Memory usage: ' . numberToSiSuffix(memory_get_usage()) . "\n";
}

return [];
