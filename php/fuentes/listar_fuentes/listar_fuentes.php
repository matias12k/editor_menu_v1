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
$targetDir = realpath(__DIR__ . '/../fuentes/') . '/'; // Ajustado para estar en el nivel superior

// Tipos de archivo permitidos
$allowedExtensions = ['ttf', 'otf', 'pfb', 'pfm', 'woff', 'woff2', 'eot', 'svg', 'bdf', 'fnt', 'fon', 'pcf'];

// Verificar si el directorio existe
if (!is_dir($targetDir)) {
    die('El directorio no existe.');
}

// Obtener la lista actual de fuentes en la base de datos
$existingFonts = [];
$query = "SELECT nombre FROM fuentes";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $existingFonts[] = $row['nombre'];
    }
}

// Escanear el directorio de fuentes y agregar las que no están en la base de datos
$files = array_diff(scandir($targetDir), ['..', '.']);

foreach ($files as $file) {
    $fileType = pathinfo($file, PATHINFO_EXTENSION);
    $fontName = pathinfo($file, PATHINFO_FILENAME);

    if (in_array(strtolower($fileType), $allowedExtensions) && !in_array($fontName, $existingFonts)) {
        if ($stmt = $mysqli->prepare("INSERT INTO fuentes (nombre) VALUES (?)")) {
            $stmt->bind_param("s", $fontName);
            $stmt->execute();
            $stmt->close();
        } else {
            die('Error al preparar la consulta: ' . $mysqli->error);
        }
    }
}

// Obtener la lista completa de fuentes desde la base de datos
$query = "SELECT nombre FROM fuentes";
$result = $mysqli->query($query);

$fuentes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fuentes[] = $row['nombre'];
    }
}

$mysqli->close();

// Devolver la lista de fuentes en formato JSON
header('Content-Type: application/json');
echo json_encode($fuentes);