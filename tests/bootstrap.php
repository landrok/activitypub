<?php

require dirname(__DIR__) . '/vendor/autoload.php';

if (substr(php_uname(), 0, 7) !== 'Windows') {

    $webservers = [
        'local' => [
            'host'   => 'localhost',
            'port'   => 8000,
        ],
        'distant' => [
            'host'   => 'localhost',
            'port'   => 8001,
        ],
    ];

    // Starts web servers
    foreach ($webservers as $name => $config) {

        extract($config);

        // Starts webserver
        $command = sprintf(
            'php -S %s:%d %s >/dev/null 2>&1 & echo $!',
            $host,
            $port,
            __DIR__ . "/WebServer/{$name}/router.php"
        );

        // Execute the command and store the process ID
        $output = array();
        exec($command, $output);
        $pid = (int) $output[0];

        echo sprintf(
            '%s - %s web server started on %s:%d with PID %d',
            date('r'),
            $name,
            $host,
            $port,
            $pid
        ) . PHP_EOL;

        $webservers[$name]['pid'] = $pid;
    }

    // Kill web servers when the process ends
    register_shutdown_function(function() use ($webservers) {
        foreach ($webservers as $name => $config) {
            extract($config);
            echo sprintf(
                '%s - Killing %s web server process with ID %d',
                date('r'),
                $name,
                $pid
            ) . PHP_EOL;
            exec('kill ' . $pid);
        }
    });
}
