<?php

function read_password()
{
    $f = popen("/bin/bash -c 'read -s; echo \$REPLY'", "r");
    $input = fgets($f, 100);
    pclose($f);
    echo "\n";

    return preg_replace('/\n$/', '', $input);
}

function print_r_wide(array $arr, int $width = 1)
{
    $width = max($width, 1);
    $count = count($arr);
    $rows = ceil($count / $width);

    echo '[';

    for ($i = 0; $i < $count;) {
        if ($rows > 1) {
            echo "\n    ";
        }

        for ($o = 0; $o < $width && $i < $count; $o++, $i++) {
            if ($o) {
                echo ' ';
            }

            echo $arr[$i];

            if ($rows > 1 || $i < $count - 1) {
                echo ',';
            }
        }
    }

    if ($rows > 1) {
        echo "\n";
    }

    echo ']';
}

function usage($command, $parameters)
{
    echo 'Usage: ' . basename($command);

    foreach ($parameters as $name => $details) {
        echo ' ';

        if ($short = @$details->short) {
            echo '-' . $short . '|';
        }

        echo '--' . str_replace('_', '-', $name) . '=' . strtoupper($name);
    }

    echo ' COMMAND [...ARGS]';

    echo "\n";
}
