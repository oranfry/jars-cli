<?php

use jars\contract\JarsConnector;

return jars_cli_load();

function jars_cli_load()
{
    // only allow using this on the CLI

    if (php_sapi_name() !== 'cli') {
        error_log('Unexpected condition: sapi name is not cli', 4);

        return null;
    }

    global $argv;

    $command = array_shift($argv);

    // parameter definitions

    $parameters = [
        'autoload' => (object) [
            'description' => 'Path to PHP script responsible for setting up autoloading or pre-loading required classes. Only useful for local jars access.',
            'short' => 'a',
            'argHelptext' => 'PATH',
        ],
        'connection-string' => (object) [
            'description' => 'A connection string for jars access.',
            'short' => 'c',
            'argHelptext' => 'STRING',
        ],
        'etc-dir' => (object) [
            'description' => 'Path to look for portal files. By default, /etc/jars and APP_HOME/etc are searched.',
            'short' => 'e',
            'argHelptext' => 'PATH',
        ],
        'interactive' => (object) [
            'description' => 'Prompt for username or password if not specified elsewhere.',
            'short' => 'i',
            'type' => 'boolean',
        ],
        'password' => (object) [
            'description' => 'When no token is specified, allows specifying a password non-interactively.',
            'short' => 'p',
            'argHelptext' => 'PASSWORD',
        ],
        'portal' => (object) [
            'description' => 'Name of the portal json file (without .json extension) to use if present. Default \'portal\'. If specified explicitly, the portal file is required to exist.',
            'short' => 'n',
            'argHelptext' => 'NAME',
        ],
        'token' => (object) [
            'description' => 'A jars access token, to allow access without creating a new token via username / password.',
            'short' => 't',
            'argHelptext' => 'TOKEN',
        ],
        'username' => (object) [
            'description' => 'When no token is specified, allows specifying a username non-interactively.',
            'short' => 'u',
            'arg' => 'USERNAME',
        ],
    ];

    // parse options given on command line ($argv)

    $arguments = array_map(fn ($details) => @$details->type === 'boolean' ? false : $details->default ?? null, $parameters);

    while (count($argv)) {
        $matches = [];

        foreach ($parameters as $param => $details) {
            if (reset($argv) === '--') {
                // explicit break from options, e.g., `jars --portal acme -- import`
                array_shift($argv);
                break 2;
            } elseif (@$details->type === 'boolean') {
                $pattern = '--' . str_replace('_', '-', $param);

                if ($short = $details->short ?? null) {
                    $pattern = '(?:-' . $short . '|' . $pattern . ')';
                }

                $pattern = '/^' . $pattern . '$/';

                if (preg_match($pattern, reset($argv), $matches)) {
                    array_shift($argv);
                    $arguments[$param] = true;

                    continue 2;
                }
            } else {
                $pattern = '--' . str_replace('_', '-', $param) . '(?:=(.*))?';

                if ($short = $details->short ?? null) {
                    $pattern = '(?:-' . $short . '|' . $pattern . ')';
                }

                $pattern = '/^' . $pattern . '$/';

                if (preg_match($pattern, reset($argv), $matches)) {
                    array_shift($argv);

                    $arguments[$param] = $matches[1] ?? array_shift($argv);

                    continue 2;
                }
            }
        }

        // we have hit the first argument that we don't understand - stop parsing
        break;
    }

    // option fetching function

    $jars_get_option = function(string $name, $default = null) use ($parameters, $arguments): string|bool|null {
        $upper = 'JARS_' . str_replace('-', '_', strtoupper($name));
        $lower = str_replace('_', '-', strtolower($name));

        $value = match (true) {
            defined($upper) => constant($upper),
            getenv($upper) !== false => getenv($upper),
            isset($arguments[$lower]) => $arguments[$lower],
            default => $default,
        };

        if (($parameters[$lower]->type ?? null) === 'boolean') {
            return (bool) $value;
        }

        return $value;
    };

    // reconstruct argv as the command *without* options

    array_unshift($argv, $command);

    // get portal config from portal config file if present

    $portal_name = $jars_get_option('portal', 'portal');
    $etc_dirs = (array) ($jars_get_option('etc-dir') ?? ['/etc/jars', APP_HOME . '/etc']);

    foreach ($etc_dirs as $etc_dir) {
        $etc_dir = (strpos($etc_dir, '/') !== 0 ? APP_HOME . '/' : null) . $etc_dir;

        if (is_file($portal_file = $etc_dir . '/' . $portal_name . '.json')) {
            $portal_data = json_decode(file_get_contents($portal_file));
            break;
        }
    }

    if (null !== $jars_get_option('portal') && !isset($portal_data)) {
        error_log('Could not portal file for ' . $portal_name, 4);
        jars_usage($command, $parameters);
        exit(1);
    }

    // supplement options from portal config data

    if (isset($portal_data)) {
        if (isset($portal_data->environment)) {
            foreach ($portal_data->environment as $name => $value) {
                putenv("$name=$value");
            }
        }

        if (!isset($arguments['username']) && ($username = $portal_data->username ?? null)) {
            $arguments['username'] = $username;
        }

        if (!isset($arguments['connection-string']) && ($connection_string = $portal_data->connection_string ?? null)) {
            $arguments['connection-string'] = $connection_string;
        }

        if (!isset($arguments['autoload']) && ($autoload = $portal_data->autoload ?? null)) {
            $arguments['autoload'] = $autoload;
        }
    }

    // finalise and apply autoload

    if ($autoload = $jars_get_option('autoload')) {
        require $autoload;
    }

    // finalise connection string

    if (!$connection_string = $jars_get_option('connection-string')) {
        error_log('Please specify a connection string', 4);
        jars_usage($command, $parameters);
        exit(1);
    }

    // instantiate jars client

    $jars = JarsConnector::connect($connection_string);

    // authenticate with jars client

    if ($token = $jars_get_option('token')) {
        $jars->token($token);
    } else {
        $interactive = $jars_get_option('interactive');
        $username = $jars_get_option('username');
        $password = $jars_get_option('password');

        if ($interactive && !$password) {
            if (!$username) {
                $username = readline('Username: ');
            }

            echo "Password: ";
            $password = jars_read_password();
        }

        $jars->login($username, $password, true);
    }

    return $jars;
}

function jars_read_password()
{
    $f = popen("/bin/bash -c 'read -s; echo \$REPLY'", "r");
    $input = fgets($f, 100);
    pclose($f);
    echo "\n";

    return preg_replace('/\n$/', '', $input);
}

function jars_usage($command, $parameters)
{
    $width = 98;
    $left_width = 40;

    echo "\n" . 'Usage: ' . basename($command) . ' [OPTIONS] [--] COMMAND [...ARGS]' . "\n\n";

    echo "OPTIONS:\n\n";

    foreach ($parameters as $name => $details) {
        $argument = (@$details->type === 'boolean') ? '' : (' ' . ($details->argHelptext ?? 'ARG'));

        $left = ['    --' . str_replace('_', '-', $name) . $argument];

        if ($details->short ?? null) {
            $left[] = '     -' . $details->short . $argument;
        }

        $right = explode("\n", wordwrap($details->description, $width - $left_width));

        for ($i = 0; $i < max(count($left), count($right)); $i++) {
            echo str_pad($left[$i] ?? '', $left_width - 1) . ' ' . ($right[$i] ?? '') . "\n";
        }

        echo "\n";
    }

    echo "COMMANDS:\n\n";

    $commands = [
        'get LINETYPE_NAME ID' => 'Reconstruct and return (as JSON object) the given line. Does not rely on reports.',
        'group REPORT_NAME [GROUP_NAME] [MIN_VERSION]' => 'Get report data for the given report and group',
        'groups REPORT_NAME [PREFIX] [MIN_VERSION]' => 'List groups in the given report that have the given prefix.',
        'h2n HASH' => 'Hash to Number - output the numerical ID that corresponds to the given hash ID for the portal, if found. Found through a sequential scan from 0 to the sequence max.',
        'import [FEEDBACK_FIFO]' => 'Read lines in master log format from STDIN and to the database. Useful for replaying a master log. If FEEDBACK_FIFO is specified, jars reports progress to a FIFO residing at the path specified therein.',
        'n2h NUMBER' => 'Number to Hash - compute the hash ID for the given numerical ID in the sequence.',
        'refresh' => 'Trigger report refresh.',
        'save' => 'Read lines from STDIN as JSON (must be an array) and save to the database.',
    ];

    foreach ($commands as $command => $description) {
        echo '    ' . $command . "\n";

        foreach (explode("\n", wordwrap($description, $width - 8)) as $line) {
            echo '        ' . $line . "\n";
        }

        echo "\n";
    }
}
