<?php
session_start();

include('conexion.php');

// Definir una variable para almacenar el ID del usuario
$idUsuarioLogueado = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contrasena = $_POST["contrasena"];

    $sql = "SELECT ID_USUARIO, NOMBRE FROM USUARIO WHERE email = ? AND contrasena = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Las credenciales son válidas, obtén el ID del usuario
        $row = $result->fetch_assoc();
        $idUsuarioLogueado = $row["ID_USUARIO"];

        // Puedes utilizar $idUsuarioLogueado en otras partes de tu código

        // Redirecciona a la página de inicio
        header("Location: ../templates/paginaPrincipal.html");
        exit();
    } else {
        echo "Credenciales inválidas. Inténtalo de nuevo.";
    }

    // Cierra la conexión a la base de datos
    $stmt->close();
    $conn->close();
}

?>