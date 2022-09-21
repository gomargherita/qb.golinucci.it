<?php

namespace App\Console;

use Illuminate\Console\Command;

class CommandWrapper extends Command
{
    protected $verboseEnabled = false;

    public function __construct()
    {
        // set the DEVELOPMENT_ENABLED constant accordingly to the environment
        $env = strtolower(config('app.env'));
        $environment = (in_array($env, ['production', 'prod'])) ? 'production' : $env;
        $debug = config('app.debug');

        if (!defined('ENVIRONMENT_MODE')) {
            define('ENVIRONMENT_MODE', $environment);
        }
        if (!defined('DEBUG_ENABLED')) {
            define('DEBUG_ENABLED', $debug);
        }
        if (!defined('DEVELOPMENT_ENABLED')) {
            define('DEVELOPMENT_ENABLED', DEBUG_ENABLED && ENVIRONMENT_MODE !== 'production');
        }

        // construct the parent class
        parent::__construct();
    }

    /**
     * A simple debug helper to simulate a colored print_r().
     * Uses the built-in $this->line() of Laravel Command.
     *
     * @param [type] $object
     * @param int $level
     *
     * @return void
     */
    protected function prettyDump($object, int $level = 0)
    {
        if (!$this->verboseEnabled) {
            return;
        }

        $object = json_encode($object, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK);
        $object = json_decode($object, true);

        if (is_array($object)) {
            $this->line(str_repeat(' ', $level * 3) . '<fg=green>*Array <fg=yellow>(size=' . count(array_keys($object)) . ')');
            foreach ($object as $key => $val) {
                if (is_array($val)) {
                    $this->line(str_repeat(' ', $level * 3) . '<fg=yellow>' . $key . ' <fg=green>*Array <fg=yellow>(size=' . count(array_keys($val)) . ')');
                    $this->prettyDump($val, $level + 1);
                } else {
                    if ($val === null) {
                        $type = 'null';
                        $val = 'NULL';
                    } elseif (is_bool($val)) {
                        $type = 'bool';
                        if ($val === 'true') {
                            $val = 'true';
                        } else {
                            $val = 'false';
                        }
                    } elseif (strlen($val) === 0) {
                        $type = 'empty';
                    } elseif (is_numeric($val)) {
                        $type = 'number';
                    } else {
                        $type = 'string';
                    }
                    $this->line(str_repeat(' ', $level * 3) . '<fg=yellow>' . $key . ' <fg=green>*' . $type . ' <fg=default>=> <fg=cyan>' . $val);
                }
            }
        }
    }

    /**
     * Simple debug helper, outputs a message to STDERR using the built-in $this->line() of Laravel Command.
     *
     * @param string $message
     * @param int $indent
     *
     * @return void
     */
    protected function debug(string $message, int $indent = 0)
    {
        if (!$this->verboseEnabled) {
            return;
        }
        $this->line(str_repeat(' ', $indent * 3) . '<fg=yellow>DEBUG:<fg=green> ' . $message);
    }

    /**
     * Simple debug helper, to output as Command/Table a random row of data from a table.
     *
     * @param array $data
     *
     * @return void
     */
    protected function debugRandomRow(array $data) : void
    {
        if (!$this->verboseEnabled) {
            return;
        }
        
        // get a random row from $data
        $randomRow = array_rand($data);
        $randomRow = $data[$randomRow];

        $this->table(array_keys($randomRow), [$randomRow]);
    }

    /**
     * Simple debug helper, to output as PrettyDump a random row of data from a table.
     *
     * @param array $data
     *
     * @return void
     */
    protected function prettyDumpRandomRow(array $data) : void
    {
        if (!$this->verboseEnabled) {
            return;
        }
        
        // get a random row from $data
        $randomRow = array_rand($data);
        $randomRow = $data[$randomRow];

        $this->prettyDump($randomRow);
    }
}
