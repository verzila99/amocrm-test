<?php


namespace App\Actions;

use League\OAuth2\Client\Token\AccessToken;

class TokenActions
{

    /**
     * @param array $accessToken
     */
    public static function saveToken($accessToken, $token_file)
    {
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            $data = [
                'accessToken' => $accessToken['accessToken'],
                'expires' => $accessToken['expires'],
                'refreshToken' => $accessToken['refreshToken'],
                'baseDomain' => $accessToken['baseDomain'],
            ];

            file_put_contents($token_file, json_encode($data));
        } else {
            exit('Invalid access token ' . var_export($accessToken, true));
        }
    }

    /**
     * @return AccessToken
     */
    public static function getToken($apiClient, $token_file)
    {
        if (!file_exists($token_file)) {
            try {
                $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode(env('AMO_AUTH_CODE'));

                self::saveToken([
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $apiClient->getAccountBaseDomain(),
                ], $token_file);

                return $accessToken;
            } catch (\Exception $e) {
                die((string) $e);
            }


        }
        $accessToken = json_decode(file_get_contents($token_file), true);

        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            $accessToken = new AccessToken([
                'access_token' => $accessToken['accessToken'],
                'refresh_token' => $accessToken['refreshToken'],
                'expires' => $accessToken['expires'],
                'baseDomain' => $accessToken['baseDomain'],
            ]);

            if ($accessToken->hasExpired()) {
                try {
                    $accessToken = $apiClient->getOAuthClient()->getAccessTokenByRefreshToken($accessToken);

                    self::saveToken([
                        'accessToken' => $accessToken->getToken(),
                        'refreshToken' => $accessToken->getRefreshToken(),
                        'expires' => $accessToken->getExpires(),
                        'baseDomain' => $apiClient->getAccountBaseDomain(),
                    ], $token_file);

                    return $accessToken;

                } catch (\Exception $e) {
                    die((string) $e);
                }

            }
            return $accessToken;
        } {
            exit('Invalid access token ' . var_export($accessToken, true));
        }
    }
}