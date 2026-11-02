# Veterinaria Patitas Felices — sitio web con turnos online

Sitio desarrollado por **Berasoft S.R.L.** (empresa de servicios informáticos, Berazategui).
Proyecto final 7°2 · E.E.S.T. N°4 Berazategui · Juan Cruz Ceresani.

Hecho **solo con HTML, CSS y PHP**, sin JavaScript ni frameworks, con base de datos **MySQL**.

## Qué hace

| Quién | Qué puede hacer |
|---|---|
| Visitante | Ver servicios y precios, mandar una consulta, crear cuenta |
| Cliente | Sacar turno (día → horarios libres), ver y cancelar sus turnos |
| Administrador | Ver turnos por día, marcarlos atendidos o cancelarlos, bloquear clientes, cambiar precios, ver consultas y accesos, descargar todo en CSV |

La atención al cliente es por **WhatsApp**: botón fijo en todas las páginas y enlaces directos desde el panel de administración.

### Reglas de los turnos
- Lunes a viernes de 9 a 18 h, sábados de 9 a 13 h, un turno cada 30 minutos. Domingos cerrado.
- Desde mañana y hasta 30 días adelante.
- No se puede reservar un horario ocupado (se controla al mostrar y otra vez al guardar).
- Máximo 2 turnos reservados por cliente.
- El cliente cancela solo sus turnos y hasta el día anterior.

### Seguridad
- Contraseñas guardadas con `password_hash` (bcrypt), nunca en texto.
- Todas las consultas a la base con **sentencias preparadas** (evita inyección SQL).
- Todo lo que se muestra pasa por `h()` = `htmlspecialchars` (evita XSS).
- Código **CSRF** en todos los formularios.
- `session_regenerate_id` al ingresar.
- Bloqueo de 15 minutos después de 5 contraseñas incorrectas.
- Registro de cada intento de ingreso con fecha e IP (tabla `accesos`).

## Archivos

```
index.php        Inicio: servicios con precios y cómo sacar turno
turnos.php       Sacar, ver y cancelar turnos (requiere ingresar)
contacto.php     Formulario de consulta
ingresar.php     Inicio de sesión          registro.php  Crear cuenta
salir.php        Cerrar sesión
admin/index.php  Panel de administración  admin/exportar.php  Descargar CSV
inc/conexion.php Conexión PDO a MySQL
inc/funciones.php Funciones comunes: sesión, CSRF, ingreso, horarios
inc/cabecera.php / inc/pie.php  Parte de arriba y de abajo de todas las páginas
css/estilos.css  Diseño (se adapta al celular)
database.sql     Crea la base, las 5 tablas y los datos de prueba
```

## Base de datos (`patitas_felices`)

- `usuarios` (clientes y admin)
- `servicios` (con precio)
- `turnos`: usuario → `usuarios`, servicio → `servicios`
- `consultas`
- `accesos`

## Usuarios de prueba

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@patitasfelices.test | Admin2026 |
| Cliente | sofia@correo.test | Demo2026 |

## Cómo correrlo en la computadora (XAMPP)

1. Instalar **XAMPP** y abrir el *XAMPP Control Panel*. Iniciar **Apache** y **MySQL**.
2. Copiar la carpeta `07_Web_Patitas_Felices` dentro de `C:\xampp\htdocs\` y renombrarla `patitas`.
3. Entrar a **http://localhost/phpmyadmin**, ir a la pestaña **Importar**, elegir `database.sql` y apretar **Importar**. Se crea la base `patitas_felices`.
4. Abrir **http://localhost/patitas/**.

No hace falta configurar nada más: `inc/conexion.php` ya usa `root` sin contraseña, como viene XAMPP.
(Con Laragon es igual: carpeta en `C:\laragon\www\` y la base se importa con HeidiSQL.)

## Cómo publicarlo en internet (hosting gratis con PHP y MySQL)

Ejemplo con **InfinityFree** (sirve cualquier hosting con PHP 8 y MySQL):

1. Crear una cuenta y una web nueva (te da un subdominio gratis).
2. En el panel, en **MySQL Databases**, crear una base. Anotar *host*, *nombre*, *usuario* y *contraseña*.
3. Abrir **phpMyAdmin** desde el panel e importar `database.sql`.
   En los hostings gratis no se puede crear una base desde el archivo. Por eso primero hay que **borrar las 3 primeras líneas que empiezan con `DROP DATABASE`, `CREATE DATABASE` y `USE`**, y después importar dentro de la base que creó el hosting.
4. Editar `inc/conexion.php` y poner esos 4 datos en lugar de `localhost`, `patitas_felices`, `root` y `''`.
5. Subir todos los archivos a la carpeta `htdocs` con el **Administrador de archivos** del panel o con FileZilla (FTP).
6. Entrar al subdominio. **Cambiar la contraseña del administrador** creando una cuenta nueva o actualizando `clave_hash` con un `password_hash` nuevo.
