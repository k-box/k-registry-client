<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use OneOffTech\KLinkRegistryClient\Exception\ApplicationVerificationException;
use OneOffTech\KLinkRegistryClient\Exception\HydrationException;
use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Model\Application;

final class AccessApi extends HttpApi
{
    const ACCESS_ACTION = 'application.authenticate';

    /**
     * @param string $secret
     * @param string $appUrl
     * @param array  $permissions
     *
     * @throws OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException         if $secret or $appUrl are empty strings
     * @throws OneOffTech\KLinkRegistryClient\Exception\ApplicationVerificationException if the application don't exists or the permissions are not supported by the application
     *
     * @return Application
     */
    public function getApplication(string $secret, string $appUrl, array $permissions = [])
    {
        if (empty($appUrl) || empty($secret)) {
            throw new InvalidArgumentException(
                'Application URL or Secret cannot be empty.'
            );
        }

        $response = $this->httpPost($this->url(self::ACCESS_ACTION), [
            'app_url' => $appUrl,
            'permissions' => $permissions,
            'app_secret' => $secret,
        ]);

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
     * @param string $appUrl
     * @param array  $permissions
     * @param string $secret
     *
     * @return bool
     */
    public function hasPermissions(string $secret, string $appUrl, array $permissions)
    {
        try {
            $appInfo = $this->getApplication($secret, $appUrl, $permissions);

            return true;
        } catch (ApplicationVerificationException $ex) {
        }

        return false;
    }
}
