<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Model\Application;
use Psr\Http\Message\ResponseInterface;

final class AccessApi extends HttpApi
{
    /**
     * @param string $appUrl
     * @param array $permissions
     * @param string $token
     * @return Application
     */
    public function getApplication(string $appUrl, array $permissions, string $token) {
        if (empty($appUrl) || empty($token)) {
            throw new InvalidArgumentException(
                'Application URL or Token cannot be empty.'
            );
        }

        $response = $this->httpPost('/application/access', [
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
    public function hasPermissions(string $appUrl, array $permissions, string $token) {
        $appInfo = $this->getApplication($appUrl, $permissions, $token);

        // TODO: Implement logic
        return false;
    }

}
