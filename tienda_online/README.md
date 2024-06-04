# Tienda Online

Proyecto de tienda en línea en PHP y MySQL, desarrollado en el canal de YouTube Códigos de Programación.

## Requerimientos 📋

- Servidor web (Apache Server).
- PHP 7.1 o superior.
- MySQL 5.6 o superior, controladores de PDO.
- Cuenta de Paypal Business
- Cuenta de Mercado Pago
- Cuenta de correo electronico

Nota: Se puede ejecutar en paquetes binarios como XAMPP, WampServer, Mamp Server, LAMP, hosting, entro otros.

## Instalación y configuración

1. Copiar la carpeta "tienda_online" al DocumentRoot del servidor web regularmente es la carpeta htdocs, www o public_html.
2. Crear una base de datos en MySQL con cotejamiento `utf8_spanish_ci` o `utf8mb4_unicode_ci`, en este ejemplo utilizaremos el nombre tienda_online.
3. Importamos el archivo "tienda_online.sql" a la base de datos creada.
4. Para agregar los datos de MySQL abra el archivo `config/database.php` con un editor de texto y establezca la configuración de la base de datos.
```
    private $hostname = "localhost";
    private $database = "tienda_online";
    private $username = "usuario_de_mysql";
    private $password = "password_de_usuario_de_mysql";
    private $charset = "utf8";
```
Nota: Esta configuración es de acceso a MySQL, se debe agregar los datos propios.

5. Para agregar los datos de configuración del sistema abra el archivo `config/config.php` con un editor de texto.

	Deberá agregar los datos de la URL base de la tienda y los datos de cifrado.

6. Abrir en un navegador web la ruta del servidor web y la aplicación. http://localhost/tienda_online o http://direccion_ip/tienda_online

7. Repetir el paso 5 pero con el archivo `admin/config/config.php` para configuración del panel de administración.

	Para abrir el panel de administración deberá ingresar al final de la url `admin`. http://localhost/tienda_online/admin

## Dependencias utilizadas:
 - PHP
 - MySQL
 - Bootstrap v5.1.3
 - Font Awesome v5.15.4
 - PHPMailer v6.5.3
 - MercadoPago SDK v2.6.2
 - FPDF v1.86
 - Chart.js v4.4.2
 - CKEditor 5

## Autores ✒️
- **Marco Robles** - *Desarrollo* - [mroblesdev](https://github.com/mroblesdev)
- Ver vídeo del desarrollo de este proyecto [playlist](https://www.youtube.com/playlist?list=PL-Mlm_HYjCo-Odv5-wo3CCJ4nv0fNyl9b)
