<?php

$lookup = [];
$collisions = [];

for ($i = 1; $i < MAX; $i++) {
    $id = $jars->n2h($i);

    if (isset($lookup[$id])) {
        $collisions[] = $i;
    }

    $lookup[$id] = $i;
}

return [
    'collisions' => $collisions,
];
