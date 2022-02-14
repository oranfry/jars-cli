<?php

$sequence = $jars->config()->sequence;

if (!$sequence) {
    error_response('Sequence not set up');
}

$lookup = [];
$collisions = [];

for ($i = 1; $i < MAX; $i++) {
    $id = $jars->n2h($i);

    if (isset($lookup[$id])) {
        echo str_pad($i, 10, ' ', STR_PAD_LEFT) . ' <=> ' . str_pad($lookup[$id], 10);
        echo $id . ' <=> ' . $jars->n2h($lookup[$id]);
        echo "\n";

        $collisions[] = $i;
    }

    $lookup[$id] = $i;
}

return [
    'collisions' => $collisions,
];
