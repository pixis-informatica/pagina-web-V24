<?php
/**
 * PIXIS ROUTER
 * Apache sirve index.php antes que index.html por defecto (DirectoryIndex).
 * Este archivo detecta bots de redes sociales y los envía a share.php.
 * Los usuarios humanos reciben index.html normalmente, sin diferencia.
 */

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Detectar CUALQUIER bot/crawler de redes sociales
$isBot = preg_match('/WhatsApp|facebookexternalhit|Facebot|Twitterbot|Discordbot|LinkedInBot|TelegramBot|Slackbot|Googlebot|bingbot/i', $userAgent);

// Detectar si hay un parámetro de contenido compartido
$hasContent = isset($_GET['producto']) || isset($_GET['banner']) || isset($_GET['categoria']);

if ($isBot && $hasContent) {
    // Bot de red social con parámetro → servir metadata desde share.php
    include __DIR__ . '/share.php';
    exit;
}

// Usuario humano (o bot sin parámetro) → servir index.html tal cual
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Type: text/html; charset=UTF-8');
readfile(__DIR__ . '/index.html');
?>
