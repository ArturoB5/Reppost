<?php
include('Controller/Backend/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos JSON enviados
    $data = json_decode(file_get_contents('php://input'), true);
    $imageData = $data['image'] ?? null;

    if ($imageData) {
        // Convierte los datos Base64 en un archivo de imagen
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);

        // Define la ubicación donde se guardará la imagen
        $memberId = $_SESSION['id']; // ID del usuario (debe estar en la sesión)
        $imageName = "photo_" . time() . ".png";
        $imagePath = "View/Images/gallery-uploads/" . $imageName;

        // Guarda la imagen como un archivo
        if (file_put_contents($imagePath, $imageBinary)) {
            // Inserta los datos en la tabla `photos`
            $stmt = $conn->prepare("INSERT INTO photos (location, member_id) VALUES (:location, :member_id)");
            $stmt->bindParam(':location', $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(':member_id', $memberId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Imagen subida exitosamente.']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la imagen en la base de datos.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar la imagen en el servidor.']);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'No se proporcionaron datos de imagen.']);
exit;
