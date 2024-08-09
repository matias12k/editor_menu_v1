<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Directorio de destino para las fuentes
$targetDir = __DIR__ . '/../fuentes/';
$fontsFile = $targetDir . 'fonts.json';

// Tipos de archivo permitidos
$allowedExtensions = ['ttf', 'otf', 'pfb', 'pfm', 'woff', 'woff2', 'eot', 'svg', 'bdf', 'fnt', 'fon', 'pcf'];

// Verificar si el directorio existe
if (!is_dir($targetDir)) {
    echo 'El directorio no existe.';
    exit;
}

// Obtener lista actual de fuentes
$existingFonts = [];

// Escanear directorio de fuentes y actualizar la lista
$files = array_diff(scandir($targetDir), array('..', '.'));

foreach ($files as $file) {
    $fileType = pathinfo($file, PATHINFO_EXTENSION);
    if (in_array(strtolower($fileType), $allowedExtensions)) {
        $fontName = pathinfo($file, PATHINFO_FILENAME);
        if (!in_array($fontName, $existingFonts)) {
            $existingFonts[] = $fontName;
        }
    }
}

// Guardar la lista actualizada de fuentes
file_put_contents($fontsFile, json_encode($existingFonts));

echo 'Lista de fuentes actualizada.';