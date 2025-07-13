<?php
session_start();
include('conexion.php');

// Inicializar el contador si no existe aún
if (!isset($_SESSION['contError'])) {
    $_SESSION['contError'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contrasena = $_POST["contrasena"];


    if (empty($email) || empty($contrasena)) {
        echo "<script>
                alert('Todos los campos son obligatorios.');
                window.location.href = '../templates/login.html';
            </script>";
    } else {
        $sql = "SELECT ID_USUARIO, NOMBRE FROM USUARIO WHERE email = ? AND contrasena = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $contrasena);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Credenciales válidas
            $row = $result->fetch_assoc();
            $_SESSION['ID_USUARIO'] = $row["ID_USUARIO"];
            
            // Reiniciar el contador de errores si inicia sesión correctamente
            $_SESSION['contError'] = 0;

            header("Location: ../src/paginaPrincipal.php");
            exit();
        } else {
            // Incrementar el contador de errores
            $_SESSION['contError']++;

            if ($_SESSION['contError'] < 3) {
                echo "<script>
                    alert('Credenciales inválidas. Inténtalo de nuevo.');
                    window.location.href = '../templates/login.html';
                </script>";
            } else {
                session_destroy();
                echo "<script>
                    alert('Superaste el límite de intentos. Por favor registrese');
                    window.location.href = '../templates/registro.html';
                </script>";
            }

            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
