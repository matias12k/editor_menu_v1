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
    mkdir($targetDir, 0777, true);
}

// Variables para almacenar nuevas fuentes
$newFonts = [];

// Procesar archivos subidos
if (isset($_FILES['fileUpload']) && !empty($_FILES['fileUpload']['name'][0])) {
    foreach ($_FILES['fileUpload']['name'] as $key => $fileName) {
        $tempFile = $_FILES['fileUpload']['tmp_name'][$key];
        $targetFile = $targetDir . basename($fileName);
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        // Verificar tipo de archivo
        if (in_array(strtolower($fileType), $allowedExtensions)) {
            if (move_uploaded_file($tempFile, $targetFile)) {
                $fontName = pathinfo($fileName, PATHINFO_FILENAME);
                $newFonts[] = $fontName;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Error al mover el archivo.']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Tipo de archivo no permitido.']);
            exit;
        }
    }

    // Leer el archivo JSON actual y agregar nuevas fuentes
    $existingFonts = file_exists($fontsFile) ? json_decode(file_get_contents($fontsFile), true) : [];
    $updatedFonts = array_merge($existingFonts, $newFonts);
    file_put_contents($fontsFile, json_encode($updatedFonts));

    header('Content-Type: application/json');
    echo json_encode(['newFonts' => $newFonts]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No se recibieron archivos.']);
}