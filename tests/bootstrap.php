<?php

require dirname(__DIR__, 1) . '/vendor/autoload.php';

if (substr(php_uname(), 0, 7) !== 'Windows') {
    
    $host = 'localhost';
    $port = 8000;

    // Starts webserver
    $command = sprintf(
        'php -S %s:%d  %s >/dev/null 2>&1 & echo $!',
        $host,
        $port,
        dirname(__DIR__, 1) . '/tests/WebServer/router.php'
    );
     
    // Execute the command and store the process ID
    $output = array(); 
    exec($command, $output);
    $pid = (int) $output[0];
     
    echo sprintf(
        '%s - Web server started on %s:%d with PID %d', 
        date('r'),
        $host, 
        $port, 
        $pid
    ) . PHP_EOL;
     
    // Kill the web server when the process ends
    register_shutdown_function(function() use ($pid) {
        echo sprintf('%s - Killing WebServer process with ID %d', date('r'), $pid) . PHP_EOL;
        exec('kill ' . $pid);
    });
}
