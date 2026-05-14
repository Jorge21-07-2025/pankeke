<?php
$addr = "tcp://127.0.0.1:8081";
$errno = 0; $errstr = '';
$sock = @stream_socket_server($addr, $errno, $errstr);
var_dump($sock !== false, $errno, $errstr);
if ($sock) { fclose($sock); }
