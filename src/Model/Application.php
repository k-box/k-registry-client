<?php

namespace OneOffTech\KLinkRegistryClient\Model;

/**
 * Application is information returned by the registry on success.
 *
 * Class Application
 */
class Application extends AbstractModel
{
    /**
     * @return int
     */
    public function getAppId(): int
    {
        return $this->data['app_id'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * @return string
     */
    public function getAppUrl(): string
    {
        return $this->data['app_url'];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->data['email'];
    }

    /**
     * @return string[]
     */
    public function getPermissions()
    {
        return $this->data['permissions'];
    }

    /**
     * hasPermission is a helper function to quickly determine
     * if an application has a specific permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission)
    {
        $perms = $this->getPermissions();

        if (in_array($permission, $perms, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasPermissions(array $permissions)
    {
        $perms = $this->getPermissions();

        // check if the inquired $permissions is a subset
        // of the available $perms for the application
        if (array_diff($permissions, $perms)) {
            // there is a difference of one or more elements, which means
            // that the inquired permissions are not a subset.
            return false;
        }

        // if no difference is returned, all inquired $permissions fit inside the
        // available $perms.
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected static function getFields()
    {
        return ['name', 'app_url', 'app_id', 'permissions', 'email'];
    }
}
