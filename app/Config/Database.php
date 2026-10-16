<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'          => '',
        'hostname'     => '',
        'username'     => '',
        'password'     => '',
        'database'     => '',
        'DBDriver'     => 'SQLite3',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => '',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'foreignKeys'  => true,
        'busyTimeout'  => 1000,
        'numberNative' => false,
        'foundRows'    => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
            return;
        }

        // Default to self-contained SQLite3 database
        $sqlitePath = defined('WRITEPATH') ? WRITEPATH . 'itso_equipment_db.sqlite' : dirname(__DIR__, 2) . '/writable/itso_equipment_db.sqlite';
        $this->default['database'] = $sqlitePath;
        $this->default['DBDriver'] = 'SQLite3';

        // If external MySQL credentials are provided, switch to MySQLi
        if ($host = getenv('DB_HOST')) {
            $this->default['DBDriver'] = 'MySQLi';
            $this->default['hostname'] = $host;
            $this->default['username'] = getenv('DB_USER') ?: 'root';
            $this->default['password'] = getenv('DB_PASS') ?: '';
            $this->default['database'] = getenv('DB_NAME') ?: 'itso_equipment_db';
            $this->default['port'] = getenv('DB_PORT') ? (int) getenv('DB_PORT') : 3306;
        }

        $dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');
        if ($dbUrl && $parsed = parse_url($dbUrl)) {
            $this->default['DBDriver'] = 'MySQLi';
            if (!empty($parsed['host'])) {
                $this->default['hostname'] = $parsed['host'];
            }
            if (!empty($parsed['user'])) {
                $this->default['username'] = $parsed['user'];
            }
            if (isset($parsed['pass'])) {
                $this->default['password'] = $parsed['pass'];
            }
            if (!empty($parsed['port'])) {
                $this->default['port'] = (int) $parsed['port'];
            }
            if (!empty($parsed['path'])) {
                $this->default['database'] = ltrim($parsed['path'], '/');
            }
        }
    }
}
