<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\BundleRecorder;

class Operation
{
    const INSTALL = 'install';
    const UPDATE = 'update';
    const UNINSTALL = 'uninstall';
    const BUNDLE_CORE = 'core';
    const BUNDLE_PLUGIN = 'plugin';

    private $type;
    private $bundleFqcn;
    private $bundleType;
    private $fromVersion;
    private $toVersion;
    private $dependencies;

    public function __construct($type, $bundleFqcn, $bundleType)
    {
        if (!in_array($type, array(self::INSTALL, self::UPDATE, self::UNINSTALL))) {
            throw new \InvalidArgumentException(
                'Operation type must be an Operation::* class constant'
            );
        }

        if ($bundleType !== self::BUNDLE_CORE && $bundleType !== self::BUNDLE_PLUGIN) {
            throw new \InvalidArgumentException(
                'Bundle type must be an Operation::BUNDLE_* class constant'
            );
        }

        $this->type = $type;
        $this->bundleFqcn = $bundleFqcn;
        $this->bundleType = $bundleType;
        $this->dependencies = array();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getBundleFqcn()
    {
        return $this->bundleFqcn;
    }

    public function getBundleType()
    {
        return $this->bundleType;
    }

    public function setFromVersion($version)
    {
        $this->fromVersion = $version;
    }

    public function getFromVersion()
    {
        return $this->fromVersion;
    }

    public function setToVersion($version)
    {
        $this->toVersion = $version;
    }

    public function getToVersion()
    {
        return $this->toVersion;
    }

    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;
    }

    public function getDependencies()
    {
        return $this->dependencies;
    }
}
