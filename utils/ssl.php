<?php

function getSSLCertificate($url)
{
    $context = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);
    $socket = stream_socket_client("ssl://{$url}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);

    if (!$socket) {
        return null;
    } else {
        $params = stream_context_get_params($socket);
        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
        fclose($socket);

        $notAfter = date_create_from_format('ymdHis\Z', $cert['validTo']);
        $domain = $cert['extensions']['subjectAltName'];
        $issuer = $cert['issuer']['O'];

        $expires = $notAfter->format('Y-m-d H:i:s');

        // reminder is two days before the expires
        $reminder = date('Y-m-d', strtotime('-2 days', strtotime($expires)));
        return [
            "domain" => $domain,
            "issuer" => $issuer,
            "expires" => $expires,
            "reminder" => $reminder,

        ];
    }
}
