<?php
/*
    * This file is part of sebastian/environment.
    *
    * (c) Sebastian Bergmann <sebastian@phpunit.de>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SebastianBergmann\Environment;

/**
 * Utility class for HHVM/PHP environment handling.
 */
final class Runtime
{

    /**
     * @var string
     */
    private static $binary;

    /**
     * Returns true when Xdebug is supported or
     * the runtime used is PHPDBG.
     */
    public function canCollectCodeCoverage(): bool
    {
        return $this->hasXdebug() || $this->hasPHPDBGCodeCoverage();

    }//end canCollectCodeCoverage()

    /**
     * Returns true when OPcache is loaded and opcache.save_comments=0 is set.
     *
     * Code taken from Doctrine\Common\Annotations\AnnotationReader::__construct().
     */
    public function discardsComments(): bool
    {
        if (\extension_loaded('Zend Optimizer+') && (\ini_get('zend_optimizerplus.save_comments') === '0' || \ini_get('opcache.save_comments') === '0')) {
            return true;
        }

        if (\extension_loaded('Zend OPcache') && \ini_get('opcache.save_comments') == 0) {
            return true;
        }

        return false;

    }//end discardsComments()

    /**
     * Returns the path to the binary of the current runtime.
     * Appends ' --php' to the path when the runtime is HHVM.
     */
    public function getBinary(): string
    {
        // HHVM
        if (self::$binary === null && $this->isHHVM()) {
            // @codeCoverageIgnoreStart
            if ((self::$binary = \getenv('PHP_BINARY')) === false) {
                self::$binary = PHP_BINARY;
            }

            self::$binary = \escapeshellarg(self::$binary) . ' --php' .
                ' -d hhvm.php7.all=1';
            // @codeCoverageIgnoreEnd
        }

        if (self::$binary === null && PHP_BINARY !== '') {
            self::$binary = \escapeshellarg(PHP_BINARY);
        }

        if (self::$binary === null) {
            // @codeCoverageIgnoreStart
            $possibleBinaryLocations = [
                PHP_BINDIR . '/php',
                PHP_BINDIR . '/php-cli.exe',
                PHP_BINDIR . '/php.exe',
            ];

            foreach ($possibleBinaryLocations as $binary) {
                if (\is_readable($binary)) {
                    self::$binary = \escapeshellarg($binary);
                    break;
                }
            }

            // @codeCoverageIgnoreEnd
        }

        if (self::$binary === null) {
            // @codeCoverageIgnoreStart
            self::$binary = 'php';
            // @codeCoverageIgnoreEnd
        }

        return self::$binary;

    }//end getBinary()

    public function getNameWithVersion(): string
    {
        return $this->getName() . ' ' . $this->getVersion();

    }//end getNameWithVersion()

    public function getName(): string
    {
        if ($this->isHHVM()) {
            // @codeCoverageIgnoreStart
            return 'HHVM';
            // @codeCoverageIgnoreEnd
        }

        if ($this->isPHPDBG()) {
            // @codeCoverageIgnoreStart
            return 'PHPDBG';
            // @codeCoverageIgnoreEnd
        }

        return 'PHP';

    }//end getName()

    public function getVendorUrl(): string
    {
        if ($this->isHHVM()) {
            // @codeCoverageIgnoreStart
            return 'http://hhvm.com/';
            // @codeCoverageIgnoreEnd
        }

        return 'https://secure.php.net/';

    }//end getVendorUrl()

    public function getVersion(): string
    {
        if ($this->isHHVM()) {
            // @codeCoverageIgnoreStart
            return HHVM_VERSION;
            // @codeCoverageIgnoreEnd
        }

        return PHP_VERSION;

    }//end getVersion()

    /**
     * Returns true when the runtime used is PHP and Xdebug is loaded.
     */
    public function hasXdebug(): bool
    {
        return ($this->isPHP() || $this->isHHVM()) && \extension_loaded('xdebug');

    }//end hasXdebug()

    /**
     * Returns true when the runtime used is HHVM.
     */
    public function isHHVM(): bool
    {
        return \defined('HHVM_VERSION');

    }//end isHHVM()

    /**
     * Returns true when the runtime used is PHP without the PHPDBG SAPI.
     */
    public function isPHP(): bool
    {
        return !$this->isHHVM() && !$this->isPHPDBG();

    }//end isPHP()

    /**
     * Returns true when the runtime used is PHP with the PHPDBG SAPI.
     */
    public function isPHPDBG(): bool
    {
        return PHP_SAPI === 'phpdbg' && !$this->isHHVM();

    }//end isPHPDBG()

    /**
     * Returns true when the runtime used is PHP with the PHPDBG SAPI
     * and the phpdbg_*_oplog() functions are available (PHP >= 7.0).
     *
     * @codeCoverageIgnore
     */
    public function hasPHPDBGCodeCoverage(): bool
    {
        return $this->isPHPDBG();

    }//end hasPHPDBGCodeCoverage()

}//end class
