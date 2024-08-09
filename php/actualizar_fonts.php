<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '', 'itredspa_bd');

// Verificar conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Directorio de destino para las fuentes
$targetDir = __DIR__ . '/../fuentes/';

// Tipos de archivo permitidos
$allowedExtensions = ['ttf', 'otf', 'pfb', 'pfm', 'woff', 'woff2', 'eot', 'svg', 'bdf', 'fnt', 'fon', 'pcf'];

$newFonts = [];

if (isset($_FILES['fileUpload']) && !empty($_FILES['fileUpload']['name'][0])) {
    foreach ($_FILES['fileUpload']['name'] as $key => $fileName) {
        $tempFile = $_FILES['fileUpload']['tmp_name'][$key];
        $targetFile = $targetDir . basename($fileName);
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        // Verificar si el tipo de archivo es permitido
        if (!in_array(strtolower($fileType), $allowedExtensions)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Tipo de archivo no permitido.']);
            exit;
        }

        // Mover el archivo al directorio de destino
        if (move_uploaded_file($tempFile, $targetFile)) {
            $fontName = pathinfo($fileName, PATHINFO_FILENAME);

            // Guardar el nombre de la fuente en la base de datos
            $stmt = $mysqli->prepare("INSERT INTO fuentes (nombre) VALUES (?)");
            $stmt->bind_param("s", $fontName);
            $stmt->execute();
            $stmt->close();

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

$mysqli->close();