<?php

if (MODE === 'json') {
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} elseif (PROPERTY) {
    echo $data;
} else {
    echo implode("\n", array_map(fn ($key) => strtoupper($key) . '=' . $data[$key], array_keys($data)));
}

echo "\n";
