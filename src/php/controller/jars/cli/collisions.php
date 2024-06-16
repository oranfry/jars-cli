<?php

$pid = getmypid() ?: 'U' . rand(100000, 999999);
$dir = sys_get_temp_dir() . '/jars-collisions-' . $pid;

echo "Using data directory: $dir\n";

$lookup = [];
$collisions = [];

for ($i = 1; $i < MAX; $i++) {
    $id = $jars->n2h($i);
    $file = $dir . '/' . substr($id, 0, 1) . '/' . substr($id, 1, 1) . '/' . substr($id, 2, 1) . '/' . substr($id, 3, 1) . '/' . substr($id, 4);

    if (file_exists($file)) {
        $collisions[] = $i;
    }

    @mkdir(dirname($file), 0777, true);

    touch($file);
}

$rrmdir = function ($dir) use (&$rrmdir) {
    if (!is_dir($dir)) {
        return;
    }

    foreach (scandir($dir) as $object) {
        if ($object !== '.' && $object !== '..') {
            if (is_dir($dir . '/' . $object) && !is_link($dir . '/' . $object)) {
                $rrmdir($dir . '/' . $object);
            } else {
                unlink($dir. '/' . $object);
            }
        }
    }

    rmdir($dir);
};

$rrmdir($dir);

return [
    'collisions' => $collisions,
];
