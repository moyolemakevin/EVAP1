# Flujo de comunicación cliente-servidor (login EVAP1)

1. **Render cliente**  
   - El navegador solicita `index.php` (GET).  
   - El servidor devuelve HTML, CSS y JS. No se exponen credenciales ni lógica de autenticación.

2. **Validación en cliente**  
   - El usuario completa el formulario.  
   - `assets/login.js` verifica formato (usuario alfanumérico 4-30, contraseña >=8).  
   - Si hay errores, se previene el envío y se muestra la lista sin tocar el servidor.

3. **Envió de credenciales (POST)**  
   - El formulario envía `username` y `password` vía `POST` a `authenticate.php`.  
   - El navegador incluye la cookie de sesión generada por PHP si ya existía.

4. **Validación en servidor**  
   - `authenticate.php` valida nuevamente longitud y patrón.  
   - Se obtiene una conexión PDO (`config/db.php`).  
   - Se ejecuta un **Prepared Statement**:  
     `SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1;`  
     Esto evita SQL Injection al no interpolar la entrada directamente en el SQL.
   - Se compara el hash con `password_verify`.

5. **Manejo de sesión**  
   - Si las credenciales son correctas, se llama `session_regenerate_id(true)` para evitar fijación de sesión.  
   - Se guardan en `$_SESSION` el `user_id`, `username` y la marca de tiempo.

6. **Respuesta al cliente**  
   - En éxito: redirección HTTP 302 a `dashboard.php`, donde se muestra contenido protegido.  
   - En fallo: se guardan mensajes de error en la sesión y se redirige a `index.php`, que los despliega.

7. **Acceso a recursos protegidos**  
   - `dashboard.php` verifica `$_SESSION['user_id']`; si falta, redirige a `index.php`.

8. **Cierre de sesión**  
   - `logout.php` acepta `POST`, borra la cookie de sesión y destruye la sesión, luego redirige a `index.php`.

## Consideraciones de seguridad
- Validación doble (cliente/servidor) para UX y seguridad.  
- Prepared statements con `PDO::ATTR_EMULATE_PREPARES = false` para proteger contra SQLi.  
- Regeneración de ID de sesión tras login.  
- Mensajes de error genéricos para no filtrar qué campo falló.  
- Contraseñas almacenadas solo como hash (`password_hash`, `password_verify`).
