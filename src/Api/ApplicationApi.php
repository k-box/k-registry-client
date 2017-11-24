<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use OneOffTech\KLinkRegistryClient\Exception\ApplicationVerificationException;
use OneOffTech\KLinkRegistryClient\Exception\HydrationException;
use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Model\Application;

final class ApplicationApi extends HttpApi
{
    // The Endpoint is invalid, but that's a fault from the API endpoint itself!
    const GET_APPLICATION = '/application.authenticate';

    /**
     * @param string      $secret
     * @param string      $appUrl
     * @param array       $permissions
     * @param string|null $requestId   the request ID, if null a random ID will be generated
     *
     * @return Application If $secret or $appUrl are empty strings
     */
    public function getApplication(string $secret, string $appUrl, array $permissions = [], string $requestId = null)
    {
        if (empty($appUrl) || empty($secret)) {
            throw new InvalidArgumentException(
                'Application URL or Secret cannot be empty.'
            );
        }

        $response = $this->httpRpcPost(self::GET_APPLICATION, [
            'app_url' => $appUrl,
            'permissions' => $permissions,
            'app_secret' => $secret,
        ], $requestId);

        if (200 !== $response->getStatusCode()) {
            throw new ApplicationVerificationException('Application cannot be verified. Please check secret and permissions');
        }

        try {
            return $this->hydrator->hydrate($response, Application::class);
        } catch (HydrationException $ex) {
            throw new ApplicationVerificationException('Application cannot be verified. Unexpected response from the server.');
        }
    }

    /**
     * @deprecated
     *
     * @param string      $secret
     * @param string      $appUrl
     * @param array       $permissions
     * @param string|null $requestId
     *
     * @return bool
     */
    public function hasPermissions(string $secret, string $appUrl, array $permissions, string $requestId = null)
    {
        try {
            $appInfo = $this->getApplication($secret, $appUrl, $permissions, $requestId);

            return true;
        } catch (ApplicationVerificationException $ex) {
        }

        return false;
    }
}
