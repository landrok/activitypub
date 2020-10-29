<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp;

/**
 * \ActivityPhp\Version handles current version of this package
 */ 
abstract class Version
{
    /**
     * Get ActivityPhp version
     */
    public static function getVersion(): string
    {
        $filename = dirname(__DIR__, 2) . '/composer.json';

        self::checkFile($filename);

        $composer = json_decode(
            file_get_contents($filename)
        );

        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($composer->version) && is_string($composer->version)) {
                return $composer->version;
            }
        }

        return 'Undefined';
    }

    /**
     * get root namespace
     */
    public static function getRootNamespace(): string
    {
        return __NAMESPACE__;
    }

    /**
     * Check that given filename is a string and is readable
     *
     * @throws \Exception if filename is not a string
     *                 or if filename is not a file
     *                 or if file is not readable
     */
    public static function checkFile(string $filename): void
    {
        // Must be a string
        if (!is_string($filename)) {
            throw new Exception(
                "FILE_ERROR Filename must be a string. Given: "
                . gettype($filename)
            );
        }

        // Must be readable
        if (!is_readable($filename)) {
            throw new Exception(
                "FILE_ERROR Filename '$filename' is not readable"
            );
        }

        // Must be a file
        if (!is_file($filename)) {
            throw new Exception(
                "FILE_ERROR Filename '$filename' must be a file"
            );
        }
    }
}
