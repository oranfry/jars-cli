<?php

use jars\Jars;

$jars = Jars::of(PORTAL_HOME, DB_HOME);

if (defined('AUTH_TOKEN')) {
    $jars->token(AUTH_TOKEN);
} else {
    $jars->login(USERNAME, PASSWORD, true);
}

return compact('jars');
