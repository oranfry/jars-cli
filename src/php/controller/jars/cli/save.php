<?php

return ['lines' => $jars->save(
    json_decode(stream_get_contents(STDIN)),
    BASE_VERSION !== null ? intval(BASE_VERSION) : null,
)];
