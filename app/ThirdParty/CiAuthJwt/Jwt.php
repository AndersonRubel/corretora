<?php

/**
 * This file is part of the canaliza/ci-auth-jwt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\ThirdParty\CiAuthJwt;

use CodeIgniter\Exceptions\ConfigException;
use Firebase\JWT\JWT as FirebaseJwt;
use InvalidArgumentException;
use Exception;

/**
 * JWT helper.
 */
class Jwt
{
    /**
     * Converts and signs an array into a JWT string.
     *
     * @param array $customPayload Payload to be merger with default payload.
     * @param int $expirationTime Time of Expiration
     *
     * @return array
     *
     * @throws \CodeIgniter\Exceptions\ConfigException
     */
    public static function encode(array $customPayload = [], int $expirationTime = 0): array
    {
        $config = config('Jwt');

        if (!is_array($config->payload)) {
            unset($customPayload, $config);

            throw new ConfigException(
                '$payload in app/Config/Jwt.php file needs to be an array.'
            );
        }

        if (!is_int($config->exp)) {
            unset($customPayload, $config);

            throw new ConfigException(
                '$exp in app/Config/Jwt.php file needs to be an integer.'
            );
        }

        $iat = time();

        $timePayload = ['iat' => $iat];

        if ($expirationTime !== 0) {
            $expConfig = (int) $expirationTime;
        } else {
            $expConfig = (int) $config->exp;
        }

        if ($expConfig !== 0) {
            $exp = $iat + $expConfig;

            $timePayload['exp'] = $exp;
        }

        unset($iat, $expConfig);

        $payload = array_merge($config->payload, $timePayload, $customPayload);

        unset($customPayload, $timePayload);

        ksort($payload);

        $key = getenv('JWT_KEY');

        if (empty($key)) {
            unset($customPayload, $config, $payload, $key);

            throw new ConfigException(
                'JWT_KEY not defined in your environment.'
            );
        }

        $encAlg = $config->encAlg;

        unset($config);

        if (empty($encAlg)) {
            unset($customPayload, $config, $payload, $key, $encAlg);

            throw new ConfigException(
                '$encAlg in your app/Config/Jwt.php file is invalid.'
            );
        }

        $response = ['token' => FirebaseJwt::encode($payload, $key, $encAlg)];

        if (isset($exp)) {
            $response['exp'] = $exp;

            unset($exp);
        }

        return $response;
    }

    /**
     * Decode a JWT string into a PHP object.
     *
     * @param string $jwt JWT token to be decoded.
     *
     * @return object
     *
     * @throws \CodeIgniter\Exceptions\ConfigException
     */
    public static function decode(string $jwt): object
    {
        $key = getenv('JWT_KEY');

        if (empty($key)) {
            unset($jwt, $key);

            throw new ConfigException(
                'JWT_KEY not defined in your environment.'
            );
        }

        $config = config('Jwt');

        $decAlgs = $config->decAlgs;

        unset($config);

        if (empty($decAlgs)) {
            unset($jwt, $key, $config, $decAlgs);

            throw new ConfigException(
                '$decAlgs in your app/Config/Jwt.php file is invalid.'
            );
        }

        return FirebaseJwt::decode($jwt, $key, $decAlgs);
    }

    /**
     * Returns the payload of the JWT token.
     *
     * @param string $jwt JWT token to extract the payload.
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public static function payload(string $jwt): array
    {
        $jwtArray = explode('.', $jwt);

        unset($jwt);

        if (empty($jwtArray)) {
            unset($jwtArray);

            throw new InvalidArgumentException('Invalid JWT token.');
        }

        if (count($jwtArray) !== 3) {
            unset($jwtArray);

            throw new InvalidArgumentException('Invalid JWT token.');
        }

        $jwtPayloadEncoded = $jwtArray[1];

        unset($jwtArray);

        if (!is_string($jwtPayloadEncoded)) {
            unset($jwtPayloadEncoded);

            throw new InvalidArgumentException('Invalid JWT token.');
        }

        $jwtPayloadDecoded = base64_decode($jwtPayloadEncoded, true);

        unset($jwtPayloadEncoded);

        if ($jwtPayloadDecoded === false) {
            unset($jwtPayloadDecoded);

            throw new InvalidArgumentException('Invalid JWT token.');
        }

        $payload = json_decode($jwtPayloadDecoded, true);

        unset($jwtPayloadDecoded);

        if (is_null($payload)) {
            throw new InvalidArgumentException('Invalid JWT token.');
        }

        return $payload;
    }

    /**
     * Returns the JWT token.
     *
     * @return string
     */
    public static function token(): string
    {
        $request = service('request');

        if (!$request->hasHeader('Authorization')) {
            unset($request);

            return '';
        }

        $header = $request->header('Authorization');

        unset($request);

        if (is_null($header)) {
            unset($header);

            return '';
        }

        $authorization = $header->getValue();

        unset($header);

        if (substr($authorization, 0, 7) !== 'Bearer ') {
            unset($authorization);

            return '';
        }

        $authorizationArray = explode(' ', $authorization);

        unset($authorization);

        if (count($authorizationArray) !== 2) {
            unset($authorizationArray);

            return '';
        }

        $jwt = $authorizationArray[1];

        unset(
            $authorizationArray[0],
            $authorizationArray[1],
            $authorizationArray[2],
            $authorizationArray
        );

        return $jwt;
    }
}
