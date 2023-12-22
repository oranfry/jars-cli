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
    $width = 75;
    $left_width = 25;

    echo "\n" . 'Usage: ' . basename($command) . ' [OPTIONS] COMMAND [...ARGS]' . "\n\n";

    echo "OPTIONS:\n\n";

    foreach ($parameters as $name => $details) {
        $left = ['    --' . str_replace('_', '-', $name)];

        if ($details->short ?? null) {
            $left[] = '     -' . $details->short;
        }

        $right = explode("\n", wordwrap($details->description, $width - $left_width));

        for ($i = 0; $i < max(count($left), count($right)); $i++) {
            echo str_pad($left[$i] ?? '', $left_width - 1) . ' ' . ($right[$i] ?? '') . "\n";
        }

        echo "\n";
    }

    echo "COMMANDS:\n\n";

    $commands = [
        'collisions MAX' => 'Compute id collisions up to the given MAX number in the sequence. Outputs the collisions in a format suitible for saving against the sequence',
        'get LINETYPE_NAME ID' => 'Reconstruct and return (as JSON object) the given line. Does not rely on reports.',
        'group REPORT_NAME [GROUP_NAME] [MIN_VERSION]' => 'Get report data for the given report and group',
        'groups REPORT_NAME [PREFIX] [MIN_VERSION]' => 'List groups in the given report that have the given prefix.',
        'h2n HASH' => 'Hash to Number - output the numerical ID that corresponds to the given hash ID for the portal, if found. Found through a sequential scan from 0 to the sequence max.',
        'import [FEEDBACK_FIFO]' => 'Read lines in master log format from STDIN and to the database. Useful for replaying a master log. If FEEDBACK_FIFO is specified, jars reports progress to a FIFO residing at the path specified therein.',
        'n2h NUMBER' => 'Number to Hash - compute the hash ID for the given numerical ID in the sequence.',
        'refresh' => 'Trigger report refresh.',
        'save' => 'Read lines from STDIN as JSON (must be an array) and save to the database.',
    ];

    foreach ($commands as $command => $description) {
        echo '    ' . $command . "\n";

        foreach (explode("\n", wordwrap($description, $width - 8)) as $line) {
            echo '        ' . $line . "\n";
        }

        echo "\n";
    }
}
