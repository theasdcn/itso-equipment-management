<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Encryption extends BaseConfig
{
    
    public string $key = 'hex2bin:4a3a69e7addbc4d098c3e1484487d5a6cb602e19920d0a1a53559b98ee92d75f';

    public string $driver = 'OpenSSL';

    public int $blockSize = 16;

    public string $digest = 'SHA512';

    public bool $rawData = true;

    public string $encryptKeyInfo = '';

    public string $authKeyInfo = '';

    public string $cipher = 'AES-256-CTR';
}
