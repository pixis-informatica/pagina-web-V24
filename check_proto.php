<?php
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NOT SET') . "\n";
echo "HTTP_X_FORWARDED_PROTO: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET') . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "BASE_URL: " . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]") . "\n";
