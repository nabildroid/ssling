<?php
function scanPorts($target)
{
    $commonPorts = array(
        21,    // FTP
        22,    // SSH
        23,    // Telnet
        25,    // SMTP
        80,    // HTTP
        110,   // POP3
        143,   // IMAP
        443,   // HTTPS
        3306,  // MySQL
        8080   // Common alternative HTTP port
    );

    $results = array();
    $sockets = array();

    foreach ($commonPorts as $port) {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_nonblock($socket);
        $connection = @socket_connect($socket, $target, $port);

        if ($connection === true) {
            $results[$port] = 'open';
            socket_close($socket);
        } elseif ($connection === false && socket_last_error($socket) != SOCKET_EINPROGRESS) {
            $results[$port] = 'closed';
            socket_close($socket);
        } else {
            $sockets[$port] = $socket;
        }
    }

    // Wait for all non-blocking sockets to complete or timeout
    $null = null;
    $write = $sockets;
    $except = $sockets;
    if (socket_select($null, $write, $except, 1) > 0) {
        foreach ($write as $port => $socket) {
            $results[$port] = 'open';
            socket_close($socket);
        }
        foreach ($except as $port => $socket) {
            $results[$port] = 'closed';
            socket_close($socket);
        }
    }

    return $results;
}



function fastPortScanner($target)
{


    $ports = array();
    for ($i = 0; $i < 10; $i++) {
        $openPorts = scanPorts($target);
        foreach ($openPorts as $port => $result) {
            if (!isset($ports[$port])) {
                $ports[$port] = $result;
            }
        }
    }

    $commonPorts = array(
        21,    // FTP
        22,    // SSH
        23,    // Telnet
        25,    // SMTP
        80,    // HTTP
        110,   // POP3
        143,   // IMAP
        443,   // HTTPS
        3306,  // MySQL
        8080   // Common alternative HTTP port
    );

    // add common ports to the list of ports to scan and set them to "close" if they are not already set
    foreach ($commonPorts as $port) {
        if (!isset($ports[$port])) {
            $ports[$port] = 'closed';
        }
    }

    // sort the ports array
    ksort($ports);

    return $ports;
}
