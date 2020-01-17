<?php

require dirname(__DIR__) . '/vendor/autoload.php';

if (substr(php_uname(), 0, 7) !== 'Windows') {

    $webservers = [
        'local' => 8000,
        'distant' => 8001,
    ];

    // Starts web servers
    foreach ($webservers as $name => $port) {
        $outputFile = sys_get_temp_dir() . '/ap-test-server-' . $name . '.output';
        $pidFile = getPidFile($name);

        if (null !== $oldPid = getPid($pidFile)) {
            killServer($name, $oldPid);
            unlink($pidFile);
        }

        // Starts webserver
        $command = sprintf(
            'php -S %s:%d %s > %s & echo $! > %s',
            'localhost',
            $port,
            __DIR__ . "/WebServer/{$name}/router.php",
            $outputFile,
            $pidFile
        );

        // Execute the command and store the process ID
        $output = [];
        exec($command);

        $pid = getPid($pidFile);

        echo sprintf(
            '%s - %s web server started on %s:%d with PID %d', 
            date('r'),
            $name,
            'localhost',
            $port,
            $pid
        ) . PHP_EOL;
    }

    // Kill web servers when the process ends
    register_shutdown_function(function() use ($webservers) {
        foreach ($webservers as $name => $port) {
            $pidFile = getPidFile($name);
            $pid = getPid($pidFile);

            killServer($name, $pid);
            unlink($pidFile);
        }
    });
}

function killServer($name, $pidId): void
{
    echo sprintf(
            '%s - Killing %s web server process with ID %d...',
            date('r'),
            $name,
            $pidId
        );

    exec(sprintf('/bin/kill %d 2> /dev/null', $pidId));

    echo ' done!' . PHP_EOL;
}

function getPid(string $pidFile): ?int
{
    if (!is_file($pidFile)) {
        return null;
    }

    $pidId = (int) file_get_contents($pidFile);

    return 0 === $pidId ? null : $pidId;
}

function getPidFile($name): string
{
    return sys_get_temp_dir() . '/ap-test-server-' . $name . '.pid';
}