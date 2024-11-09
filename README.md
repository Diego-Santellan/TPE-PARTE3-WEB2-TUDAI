# TPE - Parte 3: API REST

## Descripción del Proyecto

Se trata de una API REST pública que brinda integración con otros sistemas a través de varios servicios, utilizando una base de datos compartida con el trabajo anterior. Esta API se construyó para ser RESTfull y cumple con varios requerimientos funcionales y no funcionales.

### Requerimientos Funcionales
- La API ofrece servicios para listar, agregar, y modificar datos de una base de datos compartida.
- Los servicios permiten ordenar, filtrar, y paginar los resultados.
- Se asegura que se manejen los códigos de estado HTTP adecuados (200, 201, 400, 404).
- La autenticación de token es implementada en los servicios para modificaciones de datos (POST, PUT).


## Funcionalidades

### Acceso Público

- **Listado de Propiedades**: Los usuarios pueden ver todas las propiedades cargadas en la base de datos.
- **Detalle de Propiedad**: Los usuarios pueden acceder a la información detallada de una propiedad específica.
- **Listado de Dueños**: Los usuarios pueden visualizar todos los dueños registrados.
- **Listado de Propiedades por Dueño**: Los usuarios pueden filtrar las propiedades por cada dueño.

### Acceso Administrador

- **Autenticación**: Los administradores deben loguearse para acceder a las funcionalidades de administración de datos.
- **Administración de Propiedades**: Los administradores pueden agregar, editar y eliminar propiedades. Cada propiedad debe estar asociada a un dueño.
- **Administración de Dueños**: Los administradores pueden agregar, editar y eliminar dueños.
- **Cerrar Sesión**: Los administradores pueden desloguearse del sistema.



## Diagrama de Datos

![Modelo Entidad-Relación](./images/modeloentidadrelacion.png)
![Modelo Entidad-Relación Alternativo](./images/alternativo.png)

## Instalación y Configuración

1. **Importar la base de datos**:
   Importar el archivo `inmobiliaria.sql` en PHPMyAdmin para cargar la estructura y datos iniciales.

# Usuario admin
username: `webadmin`
password: `admin`

# ruta: 
http://localhost/inmobiliaria/ 

## Rutas

### 1. Obtener todas las propiedades y sus dueños
 **Descripción:** Trae todas las propiedades junto con la información de los dueños.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property`
- **Código de Respuesta:** 200

---

### 2. Obtener una propiedad específica y su dueño
- **Descripción:** Trae una propiedad específica junto con la información del dueño.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property/10`
- **Código de Respuesta:** 200

**Caso de error:**
- **Descripción:** Propiedad no encontrada.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property/100000`
- **Código de Respuesta:** 404

---

### 3. Crear una nueva propiedad
- **Descripción:** Crea un nuevo recurso de propiedad.
- **Endpoint:** `POST http://localhost/tpespecial3/api/property`
- **Headers:** 
  - `Content-Type: application/json`
- **Body:**
    ```json
    {
        "typePropertyAdd": "casa",
        "zonePropertyAdd": "centro",
        "pricePropertyAdd": 12000,
        "descriptionPropertyAdd": "Una hermosa propiedad ubicada en el centro de la ciudad.",
        "modePropertyAdd": "venta",
        "statusPropertyAdd": "disponible",
        "cityPropertyAdd": "barceloneta",
        "id_ownerPropertyAdd": 3
    }
    ```
- **Código de Respuesta:** 201

**Caso de error:**
- **Descripción:** Error en la creación del recurso.
- **Headers:** 
  - `Content-Type: application/json`
- **Body:**
    ```json
    {
        "typePropertyAdd": "casa",
        "zonePropertyAdd": "centro",
        "pricePropertyAdd": 12000,
        "descriptionPropertyAdd": "Una hermosa propiedad ubicada en el centro de la ciudad.",
        "modePropertyAdd": "venta",
        "statusPropertyAdd": "disponible",
        "cityPropertyAdd": "Mallorca",
        "id_ownerPropertyAdd": 1
    }
    ```
- **Código de Respuesta:** 400

---

### 4. Actualizar una propiedad existente
- **Descripción:** Actualiza los detalles de una propiedad existente.
- **Endpoint:** `PUT http://localhost/tpespecial3/api/property/20`
- **Headers:** 
  - `Content-Type: application/json`
- **Body:**
    ```json
    {
        "typePropertyEdit": "departamento",
        "zonePropertyEdit": "uncas",
        "pricePropertyEdit": 12000,
        "descriptionPropertyEdit": "Una hermosa propiedad ubicada en el centro de la ciudad.",
        "modePropertyEdit": "alquiler",
        "statusPropertyEdit": "disponible",
        "cityPropertyEdit": "valencia",
        "id_ownerPropertyEdit": 3
    }
    ```
- **Código de Respuesta:** 200

**Caso de error:**
- **Descripción:** Error en la actualización de la propiedad.
- **Endpoint:** `PUT http://localhost/tpespecial3/api/property/2000`
- **Headers:** 
  - `Content-Type: application/json`
- **Body:**
    ```json
    {
        "typePropertyEdit": "departamento",
        "zonePropertyEdit": "uncas",
        "pricePropertyEdit": 12000,
        "descriptionPropertyEdit": "Una hermosa propiedad ubicada en el centro de la ciudad.",
        "modePropertyEdit": "alquiler",
        "statusPropertyEdit": "disponible",
        "cityPropertyEdit": "valencia",
        "id_ownerPropertyEdit": 300
    }
    ```
- **Código de Respuesta:** 400

---

### 5. Ordenar propiedades por columnas
- **Descripción:** Ordena las propiedades por columnas específicas en orden ascendente o descendente.

1. **Ordenar por precio descendente:**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=price&mode=DESC`

2. **Ordenar por precio ascendente:**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=price&mode=ASC`

3. **Ordenar por zona descendente:**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=zone&mode=DESC`

4. **Ordenar por zona ascendente:**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=zone&mode=ASC`

**Caso de error:**
- **Descripción:** Ordenar por una columna inexistente.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=color&mode=DESC`

---

### 6. Filtrar propiedades
- **Descripción:** Filtra propiedades por criterios específicos.
- **Filtros Disponibles:**
  1. **Zona:** `GET http://localhost/tpespecial3/api/property?filterBy=zone&filter_value=centro`
  2. **Estado:** `GET http://localhost/tpespecial3/api/property?filterBy=status&filter_value=disponible`
  3. **Ciudad:** `GET http://localhost/tpespecial3/api/property?filterBy=city&filter_value=tandil`
  4. **Tipo de propiedad:** `GET http://localhost/tpespecial3/api/property?filterBy=type&filter_value=casa`
- **Código de Respuesta:** 200

---

### 7. Paginado
- **Descripción:** Obtiene las propiedades con paginación.
- **Endpoint con error:** `GET http://localhost/tpespecial3/api/property?quantity=10&numberPage=h`
- **Código de Respuesta para error de paginado:** 400

---

### 8. Autenticación con token
- **Descripción:** Obtiene un token de autenticación.
- **Endpoint:** `POST http://localhost/tpespecial3/api/usuarios/token`
- **Autenticación básica:** `webadmin` / `admin`

--- 

## Nota
En caso de errores o valores no especificados en los filtros o paginación, el sistema aplicará valores por defecto.
```

5) HACER DEAFULT 


## Agradecimientos

Queremos agradecer a los docentes y ayudantes de la cátedra, así como a la universidad pública por su apoyo.

## Integrantes

- [Diego Santellán](https://www.linkedin.com/in/diego-santellan/)
- [Lis Medina](https://www.linkedin.com/in/lis-medina/)