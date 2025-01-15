<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $passwordErrors = [];
    try {
        if ($conn === null) {
            throw new Exception("Error de conexión a la base de datos.");
        }
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
        // Crear un hash de la nueva contraseña y la cctualiza en la base de datos
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE members SET password = :password WHERE member_id = :member_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':member_id', $session_id);
        if ($stmt->execute()) {
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
    } catch (PDOException $e) {
        echo "<script>
                alert('Error en la base de datos: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Acceso no autorizado.');
            window.history.back();
          </script>";
}
