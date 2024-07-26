<?php

return [
    'lines' => $jars->save(json_decode(stream_get_contents(STDIN)), BASE_VERSION),
];
