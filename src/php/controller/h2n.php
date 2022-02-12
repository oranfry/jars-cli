<?php

$sequence = $jars->config($jars->token())->sequence;

for ($n = 1; $n <= $sequence->max; $n++) {
    $h = $jars->n2h($n);

    if ($h == H) {
        return ['found' => $n];
    }
}

return ['found' => null];
