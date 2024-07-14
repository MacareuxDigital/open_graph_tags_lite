<?php
namespace Concrete\Package\OpenGraphTagsLite\Src\Config;

use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Site\InstallationService;

/**
 * A service to get configuration values from the package or site.
 * This package used the database config initially, but now supports multisite configurations.
 * I can use the site config repository only, but I want to keep backwards compatibility.
 */
class ConfigService
{
    protected ?Package $package;
    protected ?Site $site;
    protected bool $multisiteEnabled = false;

    /**
     * @param \Concrete\Core\Package\PackageService $packageService
     * @param \Concrete\Core\Entity\Site\Site|null $site
     * @param \Concrete\Core\Site\InstallationService $installationService
     */
    public function __construct(PackageService $packageService, ?Site $site, InstallationService $installationService)
    {
        $this->package = $packageService->getClass('open_graph_tags_lite');
        $this->site = $site;
        $this->multisiteEnabled = $installationService->isMultisiteEnabled();
    }

    /**
     * If multisite is enabled, this method will return the configuration value from the site, then the package.
     * For backwards compatibility, get the config value from the database if it is not found in the config file.
     *
     * @param string $key The configuration key.
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed|null The configuration value or the default value if the key is not found.
     */
    public function get(string $key, $default = null)
    {
        if ($this->multisiteEnabled && $this->site !== null) {
            $value = $this->site->getConfigRepository()->get('ogp.' . $key, $default);
            if ($value !== null) {
                return $value;
            }
        }

        if ($this->package !== null) {
            $value = $this->package->getFileConfig()->get('concrete.ogp.' . $key, $default);
            if ($value !== null) {
                return $value;
            }

            $value = $this->package->getConfig()->get('concrete.ogp.' . $key, $default);
            if ($value !== null) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * If multisite is enabled, this method will save the configuration value to the site.
     * Otherwise, it will save the configuration value to the package.
     *
     * @param string $key The configuration key.
     * @param mixed $value The configuration value.
     * @return bool
     */
    public function set(string $key, $value): bool
    {
        $this->clear($key);

        if ($this->multisiteEnabled && $this->site !== null) {
            return $this->site->getConfigRepository()->save('ogp.' . $key, $value);
        }

        if ($this->package !== null) {
            return $this->package->getFileConfig()->save('concrete.ogp.' . $key, $value);
        }

        return false;
    }

    /**
     * Clear the configuration value from the site, package, and database.
     *
     * @param string $key The configuration key.
     * @return void
     */
    public function clear(string $key): void
    {
        if ($this->site !== null) {
            $this->site->getConfigRepository()->clear('ogp.' . $key);
        }
        $this->package->getFileConfig()->clear('concrete.ogp.' . $key);
        $this->package->getConfig()->clear('concrete.ogp.' . $key);
    }
}