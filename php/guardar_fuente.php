<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '', 'itredspa_bd');

// Verificar conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Leer los datos enviados en la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$fuente = $data['fuente'];

// Guardar la fuente seleccionada en la tabla `fuentes`
$stmt = $mysqli->prepare("INSERT IGNORE INTO fuentes (nombre) VALUES (?)");
$stmt->bind_param("s", $fuente);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error al guardar la fuente.']);
}

$stmt->close();
$mysqli->close();