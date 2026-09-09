# GreenCycle

GreenCycle es una aplicación web gamificada donde cada persona usuaria administra un vivero digital. Cada árbol tiene un tipo de semilla, un nivel, salud, progreso y un estado, y evoluciona según reglas de tiempo aplicadas por el servidor. Al alcanzar el nivel máximo, un árbol puede cosecharse para obtener monedas e invertirlas en la tienda.


## Alcance actual (Sprint 1)

### Objetivo del sprint
Implementar la funcionalidad básica para registrar y consultar árboles plantados dentro de la plataforma.

### Endpoints implementados

**1. Listar todos los árboles plantados**
- **Método:** `GET`
- **Ruta:** `/trees`
- **Descripción:** Devuelve una vista simple con el listado de todos los árboles registrados en el sistema.
- **Estado:** Funcional, probado desde el navegador.

**2. Buscar un árbol específico por ID**
- **Método:** `GET`
- **Ruta:** `/trees/{id}`
- **Descripción:** Devuelve una vista simple con la información de un árbol en particular, según el ID enviado en la URL.
- **Estado:** Funcional, probado desde el navegador.

**3. Plantar un árbol (crear registro)**
- **Método:** `POST`
- **Ruta:** `/trees`
- **Descripción:** Recibe el nombre del árbol y el ID del usuario que lo planta, valida los datos y crea el registro en la base de datos.
- **Estado:** Funcional, probado con Thunder Client.

### Fuera de alcance en este sprint
- Edición y eliminación de árboles (`update` / `destroy`).
- Interfaz visual para el formulario de creación (por ahora solo se prueba vía Thunder Client).
- Documentación formal del API (colección de Postman/Insomnia pendiente).
- Autenticación o restricción de rutas.

## Tecnologías

- PHP 8.5
- Laravel 13
- Laravel Herd
- Laravel Sanctum (autenticación)
- Jobs/Queues de Laravel (procesos asíncronos y Scheduler)
- Eloquent ORM
- Blade
- HTML5 semántico
- CSS3 (Flexbox/Grid, animaciones @keyframes)
- JavaScript ES6+ (Fetch API, setInterval/setTimeout)
- Vite
- PostgreSQL en Neon
- PHPUnit
- Laravel Pint
- GitHub Actions
- Render

## Requisitos locales

Antes de instalar el proyecto, asegúrese de contar con:

- Windows 10 o una versión posterior.
- Laravel Herd.
- PHP 8.5.
- Composer 2.
- Node.js 26.
- npm.
- Git.
- Visual Studio Code.
- Una cuenta de Neon.
- Una cuenta de Render.

No es necesario instalar Docker ni PostgreSQL localmente.

## Instalación local

**1. Clonar el repositorio**

```powershell
git clone https://github.com/yonguent/GreenCycle.git
Set-Location GreenCycle
```

**2. Aplicar la configuración de Laravel Herd**

```powershell
herd init
herd isolate 8.5
```

**3. Seleccionar la versión de Node.js**

```powershell
nvm use
```

**4. Instalar las dependencias**

```powershell
composer install
npm ci
```

**5. Crear la configuración local**

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

**6. Configurar la base de datos**

Abra el archivo `.env` y agregue la conexión correspondiente a la rama `development` de Neon:

```
DB_CONNECTION=pgsql
DB_URL="URL_DE_NEON_DEVELOPMENT"
DB_SSLMODE=require
```

**7. Limpiar la configuración almacenada**

```powershell
php artisan optimize:clear
```

**8. Ejecutar las migraciones y seeders**

```powershell
php artisan migrate --seed
```

**9. Iniciar Vite**

```powershell
npm run dev
```

Mantenga esta terminal abierta mientras desarrolla la aplicación.

**10. Abrir la aplicación**

```
http://greencycle.test
```

## Configuración

Variables de entorno necesarias en `.env` (sin usar valores reales/secretos):

```
APP_NAME=GreenCycle
APP_ENV=local
APP_KEY=[generado automáticamente por php artisan key:generate]
APP_URL=http://greencycle.test

DB_CONNECTION=pgsql
DB_URL="[cadena de conexión de Neon development]"
DB_SSLMODE=require
```

> Nunca deben publicarse ni incluirse en el repositorio: el archivo `.env`, las credenciales de Neon, el valor de `APP_KEY`, tokens de GitHub, contraseñas, claves de API ni ninguna cadena de conexión real. Antes de un commit, revise los archivos modificados con `git status`.

## Ejecución

Con Laravel Herd corriendo y `npm run dev` activo, la aplicación queda disponible en:

```
http://greencycle.test
```

## Pruebas / verificación

Antes de crear un commit o un pull request:

```powershell
# Ejecutar las pruebas automatizadas
php artisan test

# Comprobar el formato del código PHP
.\vendor\bin\pint --test

# Corregir el formato automáticamente
.\vendor\bin\pint

# Crear los recursos de producción
npm run build
```

## Ambientes

| Ambiente | Aplicación | Base de datos |
|---|---|---|
| Desarrollo | Laravel Herd | Neon development |
| Pruebas | PHPUnit | SQLite en memoria |
| Producción | Render | Neon production |

## API

Documentación/colección del API: `[pendiente — agregar enlace si se arma una colección de Postman/Insomnia]`

**Endpoints disponibles en Sprint 1:**
## API

### Crear árbol (Tree)

**Endpoint:** `POST /trees`

**Descripción:** Crea un nuevo registro de árbol asociado a un usuario.

**Controlador:** `TreeController@store`

**Parámetros (body, JSON o form-data):**

| Parámetro | Tipo    | Requerido | Descripción                                  |
|-----------|---------|-----------|-----------------------------------------------|
| `name`    | string  | Sí        | Nombre del árbol. Máximo 255 caracteres.      |
| `user_id` | integer | Sí        | ID del usuario dueño del árbol. Debe existir en la tabla `users`. |

**Respuesta exitosa (201 Created):**

```json
{
  "id": 1,
  "name": "Roble",
  "user_id": 3,
  "created_at": "2026-09-09T12:00:00.000000Z",
  "updated_at": "2026-09-09T12:00:00.000000Z"
}
```

**Respuesta de error (422 Unprocessable Entity):**

Se retorna si falta algún campo requerido o no cumple las validaciones (por ejemplo, `user_id` que no existe en la base de datos).

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "user_id": ["The selected user id is invalid."]
  }
}
```

**Ejemplo de uso (curl):**

```bash
curl -X POST http://localhost/trees \
  -H "Content-Type: application/json" \
  -d '{"name": "Roble", "user_id": 3}'
```
Por el momento, ninguno de los Endpoints cumple al 100% con el formato API, ya que no nos encontrábamos tan familiarizados con el formato.

| Método | Ruta | Propósito |
|---|---|---|
| GET | /api/trees | Listar los árboles del usuario autenticado |
| GET | /api/trees/{tree} | Consultar el detalle de un árbol propio |
| POST | /api/trees | Plantar un árbol indicando el tipo de semilla |

Todas las rutas anteriores requieren autenticación. El cliente no define valores internos como nivel, salud, estado o fechas; estos son calculados y controlados por el servidor.

## Flujo de trabajo

```powershell
# Actualizar la rama principal
git switch main
git pull origin main

# Crear una rama de trabajo
git switch -c feature/nombre-de-la-funcionalidad

# Guardar los cambios
git add .
git commit -m "feat: agregar nombre de la funcionalidad"

# Publicar la rama
git push -u origin feature/nombre-de-la-funcionalidad
```

El pull request se fusiona mediante squash tras revisión de código y comprobaciones automáticas (GitHub Actions).

## Equipo y atribuciones

**Integrantes:**

- John Wen Torres
- Gabriel Mora Hernández

**Repositorio:** https://github.com/yonguent/GreenCycle.git (rama principal `main`)

**Recursos e IA utilizados:**

-----------------------------------------

