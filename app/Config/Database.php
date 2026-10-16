<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'itso_equipment_db',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
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

        if ($host = getenv('DB_HOST')) {
            $this->default['hostname'] = $host;
        }
        if ($user = getenv('DB_USER')) {
            $this->default['username'] = $user;
        }
        if ($pass = getenv('DB_PASS')) {
            $this->default['password'] = $pass;
        }
        if ($name = getenv('DB_NAME')) {
            $this->default['database'] = $name;
        }
        if ($port = getenv('DB_PORT')) {
            $this->default['port'] = (int) $port;
        }

        $dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');
        if ($dbUrl && $parsed = parse_url($dbUrl)) {
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
