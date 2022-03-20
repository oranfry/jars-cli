<?php

// const JARS_PERSIST_PER_IMPORT = 1;

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

function import_presave($data, $jars, $timestamp)
{
    global $linetype;

    id_map_r($data, $jars);

    foreach ($data as $line) {
        $line->type = $linetype;
    }
}

function id_map_r($data, $jars)
{
    global $id_map;

    foreach ($data as $line) {
        if (@$line->id) {
            $orig = $line->id;
            $line->id = @$id_map[$line->id] ?? $line->id;

            // if ($orig !== $line->id) {
            //     echo "Mapped $orig to {$line->id}\n";
            // }
        }

        foreach (get_object_vars($line) as $key => $value) {
            if (preg_match('/_id$/', $key)) {
                $orig = $line->$key;
                $line->$key = @$id_map[$line->$key] ?? $line->$key;

                // if ($orig !== $line->$key) {
                //     echo "Mapped $orig to {$line->$key}\n";
                // }
            }
        }

        if (@$line->_adopt) {
            foreach ($line->_adopt as $set => &$children) {
                foreach ($children as $i => $child_id) {
                    $orig = $children[$i];
                    $children[$i] = @$id_map[$child_id] ?? $child_id;

                    // if ($orig !== $children[$i]) {
                    //     echo "Mapped $orig to {$children[$i]}\n";
                    // }
                }
            }
        }

        if (@$line->_disown) {
            foreach ($line->_disown as $set => &$children) {
                if ($set == 'teachers') {
                    continue;
                }

                foreach ($children as $i => $child_id) {
                    $orig = $children[$i];
                    $children[$i] = @$id_map[$child_id] ?? $child_id;

                    // if ($orig !== $children[$i]) {
                    //     echo "Mapped $orig to {$children[$i]}\n";
                    // }
                }
            }
        }

        foreach (array_keys(get_object_vars($line)) as $key) {
            if (is_array($line->$key)) {
                id_map_r($line->$key, $jars);
            }
        }
    }
}