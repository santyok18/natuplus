<?php
// Incluye el archivo de conexión a la base de datos
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los datos del formulario
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
    $contrasena = isset($_POST["password"]) ? $_POST["password"] : "";
    $dni = isset($_POST["dni"]) ? $_POST["dni"] : "";
    $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : "";
    $cod_postal = isset($_POST["cod_postal"]) ? $_POST["cod_postal"] : "";
    $direccion = isset($_POST["direccion"]) ? $_POST["direccion"] : "";
    $tipoUsuario = 1;

    // Verifica que los campos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($email) || empty($contrasena) || empty($dni) || empty($telefono) || empty($cod_postal) || empty($direccion)) {
        echo "<script>
                alert('Todos los campos son obligatorios.');
                window.location.href = '../templates/registro.html';
            </script>";
    } else {

        // Inserta el usuario en la base de datos
        $sql = "INSERT INTO USUARIO (email, nombre, apellido, contrasena, dni, telefono, cp, direccion, tipo_usuario) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $email, $nombre, $apellido, $contrasena, $dni, $telefono, $cod_postal, $direccion, $tipoUsuario);

        if ($stmt->execute()) {
            echo "<script>
                alert('Usuario registrado correctamente.');
                window.location.href = '../templates/login.html';
            </script>";
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }

        // Cierra la conexión a la base de datos
        $stmt->close();   
    }
    $conn->close();
}
?>