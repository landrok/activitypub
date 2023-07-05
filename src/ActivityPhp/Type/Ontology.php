<?php

declare(strict_types=1);

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Type;

use Exception;

/**
 * \ActivityPhp\Type\Ontology is an abstract class for
 * Ontology management.
 *
 * @since 0.4.0
 */
abstract class Ontology
{
    /**
     * A list of officially supported ontologies by their names and
     * associated classes.
     *
     * @var array
     */
    private static $internals = [
        'bookwyrm' => Ontology\BookWyrm::class,
        'lemmy' => Ontology\Lemmy::class,
        'mastodon' => Ontology\Mastodon::class,
        'peertube' => Ontology\Peertube::class,
    ];

    /**
     * A list of ontologies loaded by implementers, by their names and
     * associated definitions.
     *
     * @var array
     */
    private static $externals = [];

    /**
     * Allowed ontologies in current context. A list of keys that refers
     * to self::$internals and self::$externals definitions
     *
     * @var array
     */
    private static $loaded = [];

    /**
     * Clear all ontologies definitions and loaded array
     */
    public static function clear(): void
    {
        self::$externals = [];

        foreach (self::$loaded as $name) {
            self::unload($name);
        }
    }

    /**
     * Add an ontology definition in the pool.
     * Useful to define custom ontology classes on the fly
     *
     * @param string $name  Ontology name.
     * @param string $class Types definitions
     */
    public static function add(string $name, string $class, bool $load = true): void
    {
        // Reserved keyword
        if ($name === '*') {
            throw new Exception(
                "Name '{$name}' is a reserved keyword"
            );
        }

        // Class exists
        if (! class_exists($class)) {
            throw new Exception(
                "Class '{$class}' does not exist"
            );
        }

        // Class implements OntologyBase
        if (! method_exists($class, 'getDefinition')) {
            throw new Exception(
                "Class '{$class}' MUST implement "
                . OntologyInterface::class . ' interface.'
            );
        }

        // Put in the external stack if needed
        self::$externals[$name] = $class;

        // Load if needed
        if ($load) {
            self::load($name);
        }
    }

    /**
     * Load an ontology as an active one.
     *
     * @param  string $name Ontology name.
     * @throws \Exception if ontology has not been defined
     */
    public static function load(string $name): void
    {
        $ontologies = [];

        if ($name === '*') {
            $ontologies = self::$internals + self::$externals;
        } else {
            // externals (override)
            if (isset(self::$externals[$name])) {
                $ontologies[$name] = self::$externals[$name];
            } elseif (isset(self::$internals[$name])) {
                $ontologies[$name] = self::$internals[$name];
            } else {
                // Not found
                throw new Exception(
                    "Ontology '{$name}' has not been defined"
                );
            }
        }

        foreach ($ontologies as $name => $ontology) {
            Dialect::add($name, $ontology::getDefinition());
            if (! array_search($name, self::$loaded)) {
                array_push(self::$loaded, $name);
            }
        }
    }

    /**
     * Unload an ontology.
     *
     * @param  string $name Ontology name.
     */
    public static function unload(string $name): void
    {
        self::$loaded = array_filter(
            self::$loaded,
            static function ($value) use ($name): bool {
                return $value !== $name
                    && $name !== '*';
            }
        );

        Dialect::unload($name);
    }
}
