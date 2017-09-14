<?php

namespace OneOffTech\KLinkRegistryClient\Api;

use Psr\Http\Message\ResponseInterface;
use OneOffTech\KLinkRegistryClient\Model\Application;
use OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException;
use OneOffTech\KLinkRegistryClient\Exception\ApplicationVerificationException;
use OneOffTech\KLinkRegistryClient\Exception\HydrationException;

final class AccessApi extends HttpApi
{

    const ACCESS_ACTION = 'application/access';

    /**
     * @param string $token
     * @param string $appUrl
     * @param array $permissions
     * @return Application
     * @throws OneOffTech\KLinkRegistryClient\Exception\InvalidArgumentException if $token or $appUrl are empty strings
     * @throws OneOffTech\KLinkRegistryClient\Exception\ApplicationVerificationException if the application don't exists or the permissions are not supported by the application
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
            throw new ApplicationVerificationException('Application cannot be verified. Please check token and permissions');
        }
        
        try
        {
            return $this->hydrator->hydrate($response, Application::class);
        }
        catch(HydrationException $ex){
            throw new ApplicationVerificationException('Application cannot be verified. Unexpected response from the server.');
        }

    }

    /**
     * @param string $appUrl
     * @param array $permissions
     * @param string $token
     * @return bool
     */
    public function hasPermissions(string $token, string $appUrl, array $permissions) {
        try
        {

            $appInfo = $this->getApplication($token, $appUrl, $permissions);

            return true;

        }
        catch(ApplicationVerificationException $ex){}
        
        return false;
    }

    private function arrayIsSubset(array $array1, array $array2) {
        if (array_intersect($array1, $array2) == $array1) {
            return true;
        }
        return false;
    }

}
