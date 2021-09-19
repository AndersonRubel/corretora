<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Jwt extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Payload
     * --------------------------------------------------------------------------
     *
     * Array to be converted and signed to a JWT string.
     *
     * @link https://github.com/firebase/php-jwt
     *
     * @var array
     */
    public $payload = [
        'iss' => 'https://corretora.com.br',
        'aud' => 'https://www.corretora.com.br',
        'sub' => 'corretora',
    ];

    /**
     * --------------------------------------------------------------------------
     * Algorithm to encode
     * --------------------------------------------------------------------------
     *
     * Supported algorithms are 'ES256', 'HS256', 'HS384', 'HS512', 'RS256',
     * 'RS384', and 'RS512'
     *
     * @var string
     */
    public $encAlg = 'HS512';

    /**
     * --------------------------------------------------------------------------
     * Algorithms to decode
     * --------------------------------------------------------------------------
     *
     * Supported algorithms are 'ES256', 'HS256', 'HS384', 'HS512', 'RS256',
     * 'RS384', and 'RS512'
     *
     * @var array
     */
    public $decAlgs = ['HS512'];

    /**
     * --------------------------------------------------------------------------
     * Expiration time
     * --------------------------------------------------------------------------
     *
     * Time in seconds until the generated tokens are expired. If you do not
     * want your tokens to have an expiration time, use the value 0 (zero).
     *
     * @var integer
     */
    public $exp = 3600;
}
