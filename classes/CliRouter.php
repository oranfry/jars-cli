<?php

namespace OranFry\Jars\CLI;

class CliRouter extends \subsimple\Router
{
    protected static $routes = [
        'CLI import' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            'FEEDBACK_FIFO' => null,
            'BASE_VERSION' => null,
            0 => null,
        ],

        'CLI import \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            'FEEDBACK_FIFO' => null,
            0 => null,
            1 => 'BASE_VERSION',
        ],

        'CLI import -f \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            'BASE_VERSION' => null,
            0 => null,
            1 => null,
            2 => 'FEEDBACK_FIFO',
        ],

        'CLI import -f \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            0 => null,
            1 => null,
            2 => 'FEEDBACK_FIFO',
            3 => 'BASE_VERSION',
        ],

        'CLI import \S+ -f \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/import',
            0 => null,
            1 => 'BASE_VERSION',
            2 => null,
            3 => 'FEEDBACK_FIFO',
        ],

        'CLI info -e [_A-Za-z]+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/info',
            'MODE' => 'env',
            0 => null,
            1 => null,
            2 => 'PROPERTY',
        ],

        'CLI info [_A-Za-z]+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/info',
            'MODE' => 'json',
            0 => null,
            1 => 'PROPERTY',
        ],

        'CLI info -e' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/info',
            'MODE' => 'env',
            'PROPERTY' => null,
            0 => null,
            1 => null,
        ],

        'CLI info' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/info',
            'MODE' => 'json',
            'PROPERTY' => null,
            0 => null,
        ],

        'CLI refresh' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/refresh',
            0 => null,
        ],

        'CLI head' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/head',
            0 => null,
        ],

        'CLI rebuild-index' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/rebuild-index',
            0 => null,
        ],

        'CLI save' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/save',
            'BASE_VERSION' => null,
            0 => null,
        ],

        'CLI save \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/save',
            0 => null,
            1 => 'BASE_VERSION',
        ],

        'CLI groups \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/groups',
            'PREFIX' => '',
            'MIN_VERSION' => null,
            0 => null,
            1 => 'REPORT_NAME',
        ],

        'CLI groups \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/groups',
            'MIN_VERSION' => null,
            0 => null,
            1 => 'REPORT_NAME',
            2 => 'PREFIX',
        ],

        'CLI groups \S+ \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/groups',
            0 => null,
            1 => 'REPORT_NAME',
            2 => 'PREFIX',
            3 => 'MIN_VERSION',
        ],

        'CLI group \S+ ^-$' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/group',
            'MIN_VERSION' => null,
            'GROUP_NAME' => '',
            0 => null,
            1 => 'REPORT_NAME',
            2 => null,
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

        'CLI group \S+ - \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/group',
            'GROUP_NAME' => '',
            0 => null,
            1 => 'REPORT_NAME',
            2 => null,
            3 => 'MIN_VERSION',
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

        'CLI record \S+ \S+' => [
            'AUTHSCHEME' => 'onetime',
            'LAYOUT' => 'jars/cli/main',
            'PAGE' => 'jars/cli/record',
            0 => null,
            1 => 'TABLE_NAME',
            2 => 'ID',
        ],
   ];
}
