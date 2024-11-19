# Informe1.2 - Backend

## Descripción
Este proyecto corresponde al informe 1.2 del desarrollo de un backend para una aplicación móvil. Se implementa una API REST usando PHP y MySQL para la gestión de usuarios, con autenticación segura utilizando JWT (JSON Web Token) y cifrado de contraseñas con bcryptjs. El backend está configurado en un entorno XAMPP, que incluye el servidor Apache y la base de datos MySQL.

## Tecnologías Utilizadas
- **PHP**: Lenguaje de programación utilizado para desarrollar el backend y la API REST.
- **MySQL**: Sistema de gestión de bases de datos utilizado para almacenar la información de los usuarios.
- **JWT (JSON Web Token)**: Utilizado para la autenticación segura de los usuarios.
- **bcryptjs**: Utilizado para cifrar las contraseñas de los usuarios.
- **XAMPP**: Paquete que incluye Apache y MySQL, utilizado como entorno de desarrollo local.
- **Postman / Thunder Client**: Herramientas utilizadas para realizar pruebas a las rutas de la API.

## Instrucciones de Instalación

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/tuusuario/Informe1.2-Backend.git

2. **Instalar XAMPP (si no lo tienes instalado)**:

- Descarga e instala XAMPP.
- Inicia los servicios de Apache y MySQL desde el panel de control de XAMPP.
3. **Configurar la base de datos**:

- Abre phpMyAdmin (http://localhost/phpmyadmin/).
- Crea una nueva base de datos llamada usuarios_db.
- Importa el archivo .sql incluido en el proyecto para crear las tablas necesarias.
4. **Configurar el entorno de desarrollo**:

Asegúrate de que los archivos de configuración de conexión a la base de datos en el backend (por ejemplo, en los scripts PHP) estén correctamente configurados con los datos de tu entorno (usuario, contraseña, host, etc.).
5. **Probar la API**:

Utiliza Postman o Thunder Client para probar las rutas de la API disponibles, como las de registro de usuario, inicio de sesión y gestión de usuarios.
Rutas de la API
POST /register: Permite registrar un nuevo usuario.
POST /login: Permite a un usuario iniciar sesión y obtener un token JWT para autenticación.
GET /users: Obtiene una lista de todos los usuarios registrados.
GET /user: Obtiene los detalles del usuario autenticado.
PUT /update: Permite actualizar los datos de un usuario registrado.
DELETE /delete: Permite eliminar un usuario del sistema.

# Integrantes
- Silvia Ivón Añasco Rivadeneira
- Sheylee Arielle Enriquez Hernandez
- Yorman Javier Oña Gamarra
- Alex Fernando Trejo Duque