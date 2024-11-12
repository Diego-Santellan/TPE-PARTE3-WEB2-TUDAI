# TPE - Parte 3: API REST

## Descripción del Proyecto
Se trata de una API REST pública que brinda integración con otros sistemas a través de varios servicios, utilizando una base de datos compartida con el trabajo anterior. Esta API se construyó para ser RESTfull y cumple con varios requerimientos funcionales y no funcionales.

### Requerimientos Funcionales
- La API ofrece servicios para listar, agregar, y modificar datos de una base de datos
- Los servicios permiten ordenar, filtrar, y paginar los resultados.
- Se asegura que se manejen los códigos de estado HTTP adecuados (200, 201, 400, 404, 500).
- La autenticación de token es implementada en los servicios para modificaciones de datos (POST, PUT).


## Funcionalidades

### Acceso Público
- **Listado de Propiedades**: Los usuarios pueden ver todas las propiedades cargadas en la base de datos.
- **Detalle de Propiedad**: Los usuarios pueden acceder a la información detallada de una propiedad específica.

### Acceso Administrador
- **Autenticación**: Los administradores deben tener un jwt para acceder a las funcionalidades de administración de datos.
- **Administración de Propiedades**: Los administradores pueden agregar y editar propiedades. Cada propiedad debe estar asociada a un dueño.



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

## endpoints

### 1. Obtener todas las propiedades:publico  revisado
- **Descripción:** Trae todas las propiedades junto con la información de sus dueños 
- **Endpoint:** `GET http://localhost/tpespecial3/api/property`
- **Código de Respuesta:** 200 (okey) o 500 si hay algun error de servidor

---

### 2. Obtener una propiedad específica y su dueño:publico revisado
- **Descripción:** Trae una propiedad específica junto con la información del dueño.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property/10`
- **Código de Respuesta:** 200

**Caso de error:** revisado
- **Descripción:** Propiedad no encontrada.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property/100000`
- **Código de Respuesta:** 404

---

### 3. Crear una nueva propiedad:privado revisado
- **Descripción:** Crea un nuevo recurso de propiedad.
- **Primero se debe loggear en el endpoint:** `GET http://localhost/tpespecial3/api/usuarios/token`

- **Auth basic:** `username: webadmin`
                  `password: admin`

- **segundo paso: ir al Endpoint:** `POST http://localhost/tpespecial3/api/property`
- **Headers:** 
  - `Content-Type: application/json`

- **Auth bearer:** se debe copiar el token obtenido en el paso 1

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

**Caso de error:**  revisado
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

### 4. Actualizar una propiedad existente: privado revisado
- **Descripción:** Actualiza los detalles de una propiedad existente.
- **Primero se debe loggear en el endpoint:** `GET http://localhost/tpespecial3/api/usuarios/token`

- **Auth basic:** `username: webadmin`
                  `password: admin`

- **segundo paso Endpoint:** `PUT http://localhost/tpespecial3/api/property/20`
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

**Caso de error:** revisado
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

### 5. Ordenar propiedades por columnas: publico
- **Descripción:** Ordena las propiedades por columnas específicas en orden ascendente o descendente.
- **Headers:** 
  - `Content-Type: application/json`

1. **Ordenar por PRECIO descendente: código de respuesta: 200 revisado**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=price&mode=DESC`

2. **Ordenar por PRECIO ascendente código de respuesta: 200 revisado**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=price&mode=ASC`

3. **Ordenar por ZONA descendente: código de respuesta: 200 revisado**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=zone&mode=DESC`

4. **Ordenar por ZONA ascendente: código de respuesta: 200 revisado**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=zone&mode=ASC`

5. **Ordenar por ID_PROPERTY  ascendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=id_property&mode=ASC`

6. **Ordenar por ID_PROPERTY  descendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=id_property&mode=DESC`

7. **Ordenar por DESCRIPTION ascendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=description&mode=ASC`

8. **Ordenar por DESCRIPTION descendente. código de respuesta: 200**
   - **Endpoint:**`GET http://localhost/tpespecial3/api/property?orderBy=description&mode=DESC`

9. **Ordenar por MODE ascendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=mode&mode=ASC`

10. **Ordenar por MODE descendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=mode&mode=DESC`

11. **Ordenar por CITY ascendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=city&mode=ASC`

12. **Ordenar por CITY escendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=city&mode=ASC`
   
12. **Ordenar por CITY escendente. código de respuesta: 200**
   - **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=city&mode=ASC`


GET http://localhost/tpespecial3/api/property?orderBy=city&mode=DESC


GET http://localhost/tpespecial3/api/property?orderBy=status&mode=ASC
GET http://localhost/tpespecial3/api/property?orderBy=status&mode=DESC



GET http://localhost/tpespecial3/api/property?orderBy=id_owner&mode=ASC
GET http://localhost/tpespecial3/api/property?orderBy=id_owner&mode=DESC


GET http://localhost/tpespecial3/api/property?orderBy=type&mode=ASC
GET http://localhost/tpespecial3/api/property?orderBy=type&mode=DESC



**Caso de error:** reever!!!!!!!!!!!!!!!!!!!!!!
- **Descripción:** Ordenar por una columna inexistente.
- **Endpoint:** `GET http://localhost/tpespecial3/api/property?orderBy=color&mode=DESC`

---

### 6. Filtrar propiedades:publico revisado
- **Descripción:** Filtra propiedades por criterios específicos.
- **Headers:** 
  - `Content-Type: application/json`

- **Filtros:**
  1. **Zona:** `GET http://localhost/tpespecial3/api/property?filterBy=zone&filter_value=centro`
  2. **Estado:** `GET http://localhost/tpespecial3/api/property?filterBy=status&filter_value=disponible`
  3. **Ciudad:** `GET http://localhost/tpespecial3/api/property?filterBy=city&filter_value=tandil`
  4. **Tipo de propiedad:** `GET http://localhost/tpespecial3/api/property?filterBy=type&filter_value=casa`
- **Código de Respuesta:** 200

**Caso de error (es un ejemplo):** revisado 
- **Endpoint:** `GET http://localhost/tpespecial3/api/property?filterBy=type&filter_value=casas`

---

### 7. Paginado revisado publico
- **Descripción:** Obtiene las propiedades con paginación.
- **Headers:** 
  - `Content-Type: application/json`
- **endpoint**`GET http://localhost/tpespecial3/api/property?quantity=3&numberPage=1`
- **Código de Respuesta para error de paginado:** 200

- **Caso de error (es un ejemplo):** `GET http://localhost/tpespecial3/api/property?quantity=10&numberPage=h`
- **Código de Respuesta para error de paginado:** 400

---

### 8. Autenticación con token
- **Descripción:** Obtiene un token de autenticación.
- **Endpoint:** `GET http://localhost/tpespecial3/api/usuarios/token`
- **Autenticación básica:** `webadmin` / `admin`

**Caso de error (es un ejemplo):**
 **Endpoint:** `GET http://localhost/tpespecial3/api/usuarios/token`
- **Autenticación básica:** `HOLAWEB2` / `admin` REEVER!!!!!!






## Agradecimientos

Queremos agradecer a los docentes y ayudantes de la cátedra, así como a la universidad pública por su apoyo.

## Integrantes

- [Diego Santellán](https://www.linkedin.com/in/diego-santellan/)
- [Lis Medina](https://www.linkedin.com/in/lis-medina/)