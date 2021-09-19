<?php

/**
 * This file is part of the canaliza/ci-auth-jwt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\ThirdParty\CiAuthJwt;

use Exception;
use Firebase\JWT\JWT as FirebaseJwt;

/**
 * Authentication helper.
 *
 * @author Afonso Gloeden <afonso@canaliza.com.br>
 */
class Auth
{
    /**
     * Check if header request contains a valid JWT.
     *
     * @return boolean
     */
    public static function check(): bool
    {
        $jwt = Jwt::token();

        if (empty($jwt)) {
            unset($jwt);

            return false;
        }

        try {
            $key = getenv('JWT_KEY');

            if (empty($key)) {
                unset($jwt, $key);

                return false;
            }

            $decAlgs = config('Jwt')->decAlgs;

            if (empty($decAlgs)) {
                unset($jwt, $key, $decAlgs);

                return false;
            }

            FirebaseJwt::decode($jwt, $key, $decAlgs);

            unset($jwt, $key, $decAlgs);
        } catch (Exception $e) {
            unset($jwt);

            if (isset($key)) {
                unset($key);
            }

            if (isset($decAlgs)) {
                unset($decAlgs);
            }

            return false;
        }

        return true;
    }
}
