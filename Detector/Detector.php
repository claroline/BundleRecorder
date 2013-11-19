<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\BundleRecorder\Detector;

class Detector
{
    private $baseDir;

    public function __construct($baseDir = null)
    {
        $this->baseDir = $baseDir;
    }

    public function detectBundles($path)
    {
        $path = $this->baseDir ? "{$this->baseDir}/{$path}" : $path;
        $iterator = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
        $filter = new FilterIterator($iterator);
        $items = new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);
        $bundles = array();

        foreach ($items as $item) {
            if (preg_match('#^(.+Bundle)\.php$#', $item->getBasename(), $matches)) {
                if (false !== strpos(file_get_contents($item->getPathname()), 'abstract class')) {
                    continue;
                }

                $fqcnParts = array($matches[1]);
                $pathParts = array_reverse(explode(DIRECTORY_SEPARATOR, $item->getPath()));

                foreach ($pathParts as $part) {
                    if (ctype_upper($part[0])) {
                        array_unshift($fqcnParts, $part);
                        continue;
                    }

                    break;
                }

                $bundles[] = implode('\\', $fqcnParts);
            }
        }

        return $bundles;
    }

    public function detectBundle($path)
    {
        $bundles = $this->detectBundles($path);

        if (1 !== $count = count($bundles)) {
            $msg = "Expected one bundle in class {$path}, {$count} found";
            $msg .= $count === 0 ? '.' :  ('(' . implode(', ', $bundles) .').');

            throw new \Exception($msg);
        }

        return $bundles[0];
    }
}
