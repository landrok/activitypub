<?php

namespace ActivityPhpTest;

use PHPUnit\Framework\TestCase;
use ActivityPhp\Version;

/**
 * Tests ActivityPhp\Version
 */
class VersionTest extends TestCase
{
    public function testGetVersion()
    {
        // PHPUnit >= 9
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression(
                '/\d.\d.\d/',
                Version::getVersion()
            );
        // PHPUnit < 9
        } else {
            $this->assertRegExp(
                '/\d.\d.\d/',
                Version::getVersion()
            );
        }
    }
}
