<?php

$token = Blends::login(null, USERNAME, PASSWORD, true);
$input = "";

while ($line = fgets(STDIN)) {
    $input .= $line;
}

$data = json_decode($input);
$lines = Blends::import($token, new Filesystem(), date('Y-m-d H:i:s'), $data);

return [
    'lines' => $lines,
];
