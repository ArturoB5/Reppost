<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $passwordErrors = [];

    try {
        if ($conn === null) {
            throw new Exception("Error de conexión a la base de datos.");
        }
        // Verificar la contraseña actual
        $query = "SELECT password FROM members WHERE member_id = :member_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':member_id', $session_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || !password_verify($current_password, $user['password'])) {
            echo "<script>
                    alert('La contraseña actual es incorrecta.');
                    window.history.back();
                  </script>";
            exit;
        }
        // Validar la nueva contraseña
        if (strlen($new_password) < 8) {
            $passwordErrors[] = "La contraseña debe tener al menos 8 caracteres.";
        }
        if (!preg_match('/[A-Z]/', $new_password)) {
            $passwordErrors[] = "La contraseña debe tener al menos una letra mayúscula.";
        }
        if (!preg_match('/[a-z]/', $new_password)) {
            $passwordErrors[] = "La contraseña debe tener al menos una letra minúscula.";
        }
        if (!preg_match('/[0-9]/', $new_password)) {
            $passwordErrors[] = "La contraseña debe tener al menos un número.";
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
            $passwordErrors[] = "La contraseña debe tener al menos un carácter especial.";
        }
        if ($new_password !== $confirm_password) {
            $passwordErrors[] = "Las contraseñas no coinciden.";
        }
        if (!empty($passwordErrors)) {
            $errors = implode(" - ", $passwordErrors);
            echo "<script>
                    alert('Error en la contraseña: $errors');
                    window.history.back();
                  </script>";
            exit;
        }
        // Actualizar la contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE members SET password = :password WHERE member_id = :member_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $updateStmt->bindParam(':member_id', $session_id, PDO::PARAM_INT);
        if ($updateStmt->execute()) {
            echo "<script>
                    alert('Contraseña actualizada exitosamente.');
                    window.location = 'config_preferences.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Hubo un error al actualizar tu contraseña.');
                    window.history.back();
                  </script>";
        }
    } catch (Exception $e) {
        echo "<script>
                alert('Error: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Acceso no autorizado.');
            window.history.back();
          </script>";
}
