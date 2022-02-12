<?php

$token = Blends::login(new Filesystem(), USERNAME, PASSWORD, true);

Linetype::load(null, null, 'token')->delete($token, [(object) [
    'field' => 'expired',
    'cmp' => '=',
    'value' => 'yes',
]]);

return [];
