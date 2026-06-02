<?php
/**
 * PIXIS IMAGE ADJUSTER
 * Centra cualquier imagen en un lienzo de 1200x630 para evitar recortes en Facebook.
 */

$imgUrl = isset($_GET['url']) ? $_GET['url'] : '';
// --- REGISTRO DE EVIDENCIA (PASO 1) ---
$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
$logData = date('Y-m-d H:i:s') . " | META_IMG REQUEST | UA: " . $ua . " | URL: " . $imgUrl . "\n";
@file_put_contents(__DIR__ . '/data/scrapers.log', $logData, FILE_APPEND);
// -------------------------------------
if (!$imgUrl) exit;

// 1. Cargar la imagen original
$info = @getimagesize($imgUrl);
if (!$info) exit;

$width = $info[0];
$height = $info[1];
$type = $info[2];

switch ($type) {
    case IMAGETYPE_JPEG: $source = imagecreatefromjpeg($imgUrl); break;
    case IMAGETYPE_PNG:  $source = imagecreatefrompng($imgUrl); break;
    case IMAGETYPE_WEBP: $source = imagecreatefromwebp($imgUrl); break;
    default: exit;
}

// 2. Crear el lienzo de Facebook (1200x630)
$canvasW = 1200;
$canvasH = 630;
$canvas = imagecreatetruecolor($canvasW, $canvasH);

// Fondo blanco
$white = imagecolorallocate($canvas, 255, 255, 255);
imagefill($canvas, 0, 0, $white);

// 3. Calcular dimensiones para centrar (contain)
$ratio = min($canvasW / $width, $canvasH / $height);
$newW = round($width * $ratio);
$newH = round($height * $ratio);

$posX = ($canvasW - $newW) / 2;
$posY = ($canvasH - $newH) / 2;

// 4. Copiar y redimensionar
imagecopyresampled($canvas, $source, $posX, $posY, 0, 0, $newW, $newH, $width, $height);

// 5. Salida
header('Content-Type: image/jpeg');
imagejpeg($canvas, null, 90);

// Limpiar
imagedestroy($canvas);
imagedestroy($source);
?>
