<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Directorio de destino para las fuentes
$targetDir = __DIR__ . '/../fuentes/';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$newFonts = [];
if (isset($_FILES['fileUpload']) && !empty($_FILES['fileUpload']['name'][0])) {
    foreach ($_FILES['fileUpload']['name'] as $key => $fileName) {
        $tempFile = $_FILES['fileUpload']['tmp_name'][$key];
        $targetFile = $targetDir . basename($fileName);
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        // Tipos de archivo permitidos
        $allowedExtensions = ['ttf', 'otf', 'pfb', 'pfm', 'woff', 'woff2', 'eot', 'svg', 'bdf', 'fnt', 'fon', 'pcf'];
        if (!in_array(strtolower($fileType), $allowedExtensions)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Tipo de archivo no permitido.']);
            exit;
        }

        // Mover archivo al directorio de destino
        if (move_uploaded_file($tempFile, $targetFile)) {
            $fontName = pathinfo($fileName, PATHINFO_FILENAME);
            $newFonts[] = $fontName;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al mover el archivo al directorio de destino.']);
            exit;
        }
    }
    header('Content-Type: application/json');
    echo json_encode(['newFonts' => $newFonts]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No se recibieron archivos para subir.']);
}