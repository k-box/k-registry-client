<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Model\Application;
use Psr\Http\Message\ResponseInterface;

final class AccessApi extends HttpApi
{

    const ACCESS_ACTION = 'application/access';

    /**
     * @param string $token
     * @param string $appUrl
     * @param array $permissions
     * @return Application
     */
    public function getApplication(string $token, string $appUrl, array $permissions) {
        if (empty($appUrl) || empty($token)) {
            throw new InvalidArgumentException(
                'Application URL or Token cannot be empty.'
            );
        }

        $response = $this->httpPost($this->url(self::ACCESS_ACTION), [
            'app_url' => $appUrl,
            'permissions' => $permissions,
            'auth_token' => $token,
        ]);

        if ($response->getStatusCode() !== 200) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Application::class);
    }

    /**
     * @param string $appUrl
     * @param array $permissions
     * @param string $token
     * @return bool
     */
    public function hasPermissions(string $token, string $appUrl, array $permissions) {
        $appInfo = $this->getApplication($token, $appUrl, $permissions);

        // TODO: Implement logic
        return false;
    }

    private function arrayIsSubset(array $array1, array $array2) {
        if (array_intersect($array1, $array2) == $array1) {
            return true;
        }
        return false;
    }

}
