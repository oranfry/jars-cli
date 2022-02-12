<?php

namespace jars\cli;

class CliRouter extends \subsimple\Router
{
    protected static $routes = [
        'CLI collisions \S+' =>         [null, 'PAGE' => 'collisions',      'MAX',  'AUTHSCHEME' => 'none'],
        'CLI expunge-tokens' =>         [null, 'PAGE' => 'expunge-tokens',          'AUTHSCHEME' => 'onetime'],
        'CLI h2n \S+' =>                [null, 'PAGE' => 'h2n',             'H',    'AUTHSCHEME' => 'none'],
        'CLI import' =>                 [null, 'PAGE' => 'import',                  'AUTHSCHEME' => 'onetime'],
        'CLI n2h \S+' =>                [null, 'PAGE' => 'n2h',             'N',    'AUTHSCHEME' => 'none'],
        'CLI reset-schema' =>           [null, 'PAGE' => 'reset-schema',            'AUTHSCHEME' => 'onetime'],
        'CLI save' =>                   [null, 'PAGE' => 'save',                    'AUTHSCHEME' => 'onetime'],
   ];
}
