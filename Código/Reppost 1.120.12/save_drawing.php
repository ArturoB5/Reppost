<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

header('Content-Type: application/json');

// Obtener datos enviados desde el frontend (imagen en base64, ID de usuario, y metadatos)
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que la solicitud contiene los datos necesarios
if (!isset($_SESSION['member_id']) || !isset($data['image'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado o sin datos']);
    exit;
}

$member_id = $_SESSION['member_id'];
$imageData = $data['image'];
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Desconocido';

// Obtener otros metadatos enviados desde el frontend
$brushSize = isset($data['brushSize']) ? $data['brushSize'] : 'Desconocido';
$strokeColor = isset($data['strokeColor']) ? $data['strokeColor'] : 'Desconocido';
$backgroundColor = isset($data['backgroundColor']) ? $data['backgroundColor'] : 'Desconocido';
$drawingTime = isset($data['drawingTime']) ? $data['drawingTime'] : 'Desconocido';

// Validar que la imagen esté en formato base64 de tipo PNG
if (!preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $imageData)) {
    echo json_encode(['success' => false, 'error' => 'Formato de imagen inválido']);
    exit;
}

// Eliminar encabezado base64 y decodificar la imagen
$imageData = str_replace('data:image/png;base64,', '', $imageData);
$imageData = base64_decode($imageData);

// Verificar que la imagen se decodificó correctamente
if ($imageData === false) {
    echo json_encode(['success' => false, 'error' => 'Error al decodificar la imagen']);
    exit;
}

// Generar nombre único para la imagen usando la fecha y ID del miembro
$timestamp = time();
$filename = "dibujo_{$timestamp}_{$member_id}.png";

// Generar los metadatos (aquí no se guardan en el servidor, solo se pasan al frontend)
$metadataContent = "Usuario ID: $member_id\n";
$metadataContent .= "Nombre: $userName\n";
$metadataContent .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
$metadataContent .= "Archivo: $filename\n";
$metadataContent .= "Dimensiones: 800x600\n"; // Aquí puedes poner las dimensiones reales si lo deseas
$metadataContent .= "Tamaño del Pincel: $brushSize px\n";
$metadataContent .= "Color de Trazo: $strokeColor\n";
$metadataContent .= "Color de Fondo: $backgroundColor\n";
$metadataContent .= "IP: No disponible (Descarga local)\n";

// Aquí no guardamos la imagen en el servidor ni los metadatos, solo los preparamos para descargar
echo json_encode([
    'success' => true,
    'image' => $filename,    // Nombre del archivo de la imagen
    'metadata' => $metadataContent // Metadatos para la descarga
]);
