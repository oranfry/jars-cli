<?php

namespace jars\cli;

class CliRouter extends \subsimple\Router
{
    protected static $routes = [
        'CLI collisions \S+' => [
            'AUTHSCHEME' => 'none',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/collisions',
            0 => null,
            1 => 'MAX',
        ],

        'CLI h2n \S+' => [
            'AUTHSCHEME' => 'none',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/h2n',
            0 => null,
            1 => 'H',
        ],

        'CLI import' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            0 => null,
        ],

        'CLI n2h \S+' => [
            'AUTHSCHEME' => 'none',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/n2h',
            0 => null,
            1 => 'N',
        ],

        'CLI refresh' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/refresh',
            0 => null,
        ],

        'CLI save' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/save',
            0 => null,
        ],
   ];
}
