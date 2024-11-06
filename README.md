# Trabajo Práctico Especial - WEB 2 - TUDAI - UNICEN - Parte 3

Este proyecto consiste en la continuación del desarrollo de un sitio web dinámico para la gestión y visualización de propiedades inmobiliarias, basado en el modelo de datos propuesto en la **Parte 1**. Se implementaron nuevas funcionalidades y una sección privada para administrar los datos.

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

## Requerimientos Técnicos

- El sistema está basado en el patrón **MVC** para separar la lógica del negocio, las vistas y el acceso a los datos.
- Las vistas son generadas utilizando plantillas **PHTML**.
- El sitio utiliza **URL semánticas**.
- La base de datos se inicializa automáticamente si no existe, y se llenará con datos iniciales.
- La autenticación de usuarios utiliza contraseñas encriptadas con el algoritmo password hash.

## Modelo de Datos

El modelo de datos sigue la estructura definida en la primera parte con las siguientes tablas:

### Atributos de la tabla `duenio`:
- `id_owner`: `int(11)` (Primary key)
- `name`: `varchar(50)`
- `phone`: `varchar(20)`
- `email`: `varchar(80)` (único)

### Atributos de la tabla `propiedad`:
- `id_property`: `int(11)` (Primary key)
- `type`: `varchar(20)`
- `zone`: `varchar(45)`
- `price`: `decimal(10,0)`
- `description`: `varchar(500)`
- `mode`: `varchar(20)`
- `status`: `varchar(20)`
- `city`: `varchar(45)`
- `id_owner`: `int(11)` (Foreign key que referencia a la tabla `duenio`)

### Atributos de la tabla `users`:
- `username`: `varchar(20)` (Primary key)
- `password`: `varchar(60)`
- `id_user`: `int(11)` (Auto-increment)



### Requerimientos Funcionales:

## Modelo de Datos

Las entidades principales de la base de datos son **propiedades** y **dueños**. Para cada una se construyó una tabla con sus respectivos atributos. 

- **Tabla propiedades**: Contiene información sobre las propiedades inmobiliarias.
- **Tabla dueños**: Almacena información sobre los dueños de las propiedades.

La relación entre estas tablas es de **1 a N**, lo que significa que un dueño puede tener múltiples propiedades, pero una propiedad sólo puede pertenecer a un dueño.


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


## Agradecimientos

Queremos agradecer a los docentes y ayudantes de la cátedra, así como a la universidad pública por su apoyo.

## Integrantes

- [Diego Santellán](https://www.linkedin.com/in/diego-santellan/)
- [Lis Medina](https://www.linkedin.com/in/lis-medina/)




<!-- Rutas :  -->
1) Traer TODAS las propiedades y los dueños de las mismas y que el codigo de respuesta sea 200
http://localhost/tpespecial3/api/property

2) Traer UNA propiedad y el dueño de la misma y que el codigo de respuesta sea 200
http://localhost/tpespecial3/api/property/10

Traer UNA propiedad y el dueño de la misma  y que el codigo de respuesta sea 404
http://localhost/tpespecial3/api/property/100000

3) CREAR un recurso propiedad y que que el codigo de respuesta sea 201

http://localhost/tpespecial3/api/property

header: Content-Type:application/json
body: 
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


 CREAR un recurso propiedad y que te de codigo de respuesta 400:

 header: Content-Type:application/json

body {
    "typePropertyAdd": "casa",
    "zonePropertyAdd": "centro",
    "pricePropertyAdd": 12000,
    "descriptionPropertyAdd": "Una hermosa propiedad ubicada en el centro de la ciudad.",
    "modePropertyAdd": "venta",
    "statusPropertyAdd": "disponible",
    "cityPropertyAdd": "Mallorca",
    "id_ownerPropertyAdd": 1
}


4) Actualizar una propiedady que te de codigo de respuesta 200
http://localhost/tpespecial3/api/property/20

 header: Content-Type:application/json


body:
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






 Actualizar una propiedad y que te codigo de respuesta 400
 http://localhost/tpespecial3/api/property/2000
  header: Content-Type:application/json

body:
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


5)ORDENAR POR CUALQUIER COLUMNA YA SEA ASCENDENTE O DESCENDENTEMENTE :

ordenar por columna precio de forma descendente y que el codigo de respuesta sea :
http://localhost/tpespecial3/api/property?orderBy=price&mode=DESC

ordenar por columna precio de forma ascendente y que el codigo de respuesta sea:
http://localhost/tpespecial3/api/property?orderBy=price&mode=ASC

ordenar por columna zona de forma descendente y que el codigo de respuesta sea:
http://localhost/tpespecial3/api/property?orderBy=zone&mode=DESC

ordenar por columna zona de forma ascendente y que el codigo de respuesta sea:
http://localhost/tpespecial3/api/property?orderBy=zone&mode=ASC

Intentar ordenar por columna color (inexistente) de forma descendente que el codigo de respuesta sea: 
http://localhost/tpespecial3/api/property?orderBy=color&mode=DESC

 que el codigo de respuesta sea 200:
http://localhost/tpespecial3/api/property?filterBy=zone&filter_value=centro 

http://localhost/tpespecial3/api/property?filterBy=status&filter_value=disponible

http://localhost/tpespecial3/api/property?filterBy=city&filter_value=tandil

http://localhost/tpespecial3/api/property?filterBy=type&filter_value=casa


6) paginado 

con error http://localhost/tpespecial3/api/property?quantity=10&numberPage=h

5) HACER DEAFULT 