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
            'FEEDBACK_FIFO' => null,
            0 => null,
        ],

        'CLI import \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            0 => null,
            1 => 'FEEDBACK_FIFO',
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

        'CLI groups \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/groups',
            'MIN_VERSION' => null,
            0 => null,
            1 => 'REPORT_NAME',
        ],

        'CLI groups \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/groups',
            0 => null,
            1 => 'REPORT_NAME',
            2 => 'MIN_VERSION',
        ],

        'CLI group \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/group',
            'MIN_VERSION' => null,
            0 => null,
            1 => 'REPORT_NAME',
            2 => 'GROUP_NAME',
        ],

        'CLI group \S+ \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/group',
            0 => null,
            1 => 'REPORT_NAME',
            2 => 'GROUP_NAME',
            3 => 'MIN_VERSION',
        ],

        'CLI get \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/get',
            0 => null,
            1 => 'LINETYPE_NAME',
            2 => 'ID',
        ],
   ];
}
