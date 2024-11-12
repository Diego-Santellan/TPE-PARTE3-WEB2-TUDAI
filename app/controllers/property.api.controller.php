<?php
/* REQUERIMOS MODELO Y VISTA : el controller es un pasamanos*/
require_once './app/models/property.model.php';
require_once './app/models/owner.model.php';
require_once './app/views/json.view.php';
require_once './libs/jwt.php';

//CLASE: cada componente del MVC es un clase y los métodos lógicos van dentro de cada clase 
class PropertyApiController
{
    // ATRIBUTOS PRIVADOS
    private $model;
    private $modelOwner;
    private $view;

    // estas opciones podrian estar dese una db o archivo 
    private $optionsTypeProperty = ["casa", "departamento", "lote", "quinta"];
    private $optionsModeProperty = ["venta", "alquiler"];
    private $optionsStatusProperty = ["vendido", "alquilado", "disponible"];
    private $optionsModeAvailable = ["ASC", "DESC"];

    private $optionsColumnsAvailable = ['id_property', 'type', 'zone', 'price', 'description', 'mode', 'status', 'city', 'id_owner'];

    private $optionsFilterAvailable = ['type', 'status', 'city', 'zone', 'id_owner'];

    // CONSTRUCTOR
    public function __construct()
    {
        //requiere dos modelos (propiedades y owner)
        $this->model = new PropertyModel();
        $this->modelOwner = new OwnerModel();
        $this->view = new JSONView();
    }

    // MÉTODOS O FUNCIONES DE LA CLASE  

    public function getAll($req, $res)
    {
        $errors = [];
        $orderBy = '';
        $mode = '';
        // api/property?orderBy=columna && mode=asc o desc
        if (isset($req->query->orderBy) && isset($req->query->mode) && !empty($req->query->orderBy) && !empty($req->query->mode)) { //si estan seteados los parametros

            if (!$this->validOption(($req->query->orderBy),  $this->optionsColumnsAvailable)) { //si no es opc valida
                return $this->view->response('No se puede ordenar por esa caracteristica(inexistente)', 404);
            } else { //si ingresan opcion valida orderBy
                $orderBy = $req->query->orderBy;

                if (!$this->validOption(($req->query->mode), $this->optionsModeAvailable)) { //si no es modo(ascendente o desendente) valido
                    $errors[] = 'No existe esa modo de orden: solo ascendete y descendente';
                } else { //si ingresan opcion valida mode
                    $mode = $req->query->mode;
                    $properties = $this->model->getAllOrder($orderBy, $mode);
                }
            }

            if (count($errors) > 0) {
                $errorsString = implode(', ', $errors);
                $error_msj = "error: ocurrio un problema al obtener los datos: " . $errorsString;
                return $this->view->response([$error_msj, 400]);
            }
        } else if (isset($req->query->filterBy) && !empty($req->query->filterBy)) {            // api/property?filterBy=zone && filter_value=centro

            if (!$this->validOption(($req->query->filterBy), $this->optionsFilterAvailable)) { //si no es opc valida
                $errors[] = 'No se puede filtrar por esa caracteristica(inexistente)';
            } else {
                $filter_by = $req->query->filterBy;
                $filter_value = $req->query->filter_value;

                $properties = $this->model->getAllFilter($filter_by, $filter_value);
                if (!$properties) {
                    return $this->view->response(['Error' => 'No existen propiedades con esa caracteristica'], 400); //400 bad request 
                }
            }

            if (count($errors) > 0) {
                $errorsString = implode(', ', $errors);
                $error_msj = "error: ocurrio un problema al obtener los datos: " . $errorsString;
                return $this->view->response([$error_msj, 400]);
            }
        } else if (isset($req->query->quantity) && isset($req->query->numberPage) && !empty($req->query->quantity) && !empty($req->query->numberPage)) {


            $totalProperties = $this->model->countProperties();
            if (!$totalProperties) {
                return $this->view->response('Error: No hay propiedades que traer', 500);
            }

            // lo que llega es de tipo numero , es mayor que 0 y menor que 99.999?

            if ($req->query->quantity > $totalProperties || $req->query->quantity < 0) {
                return $this->view->response('Error: fuera de rango cantidad a traer', 400);
            }

            if ($req->query->numberPage > ($totalProperties / intval($req->query->quantity)) || $req->query->numberPage < 0) {
                return $this->view->response('Error: fuera de rango numero de pagina', 400);
            }

            if ((!is_numeric($req->query->quantity)) || (!is_numeric($req->query->numberPage))) {
                return $this->view->response('Error: Debe ingresar un numero ', 400);
            }

            $quantity = $req->query->quantity;
            $numberPage = $req->query->numberPage;

            $properties = $this->model->getPagination($quantity, $numberPage);
            if (!$properties) {
                return $this->view->response('Error: No hay propiedades que traer', 500);
            } else {
                return $this->view->response($properties, 200);
            }
        } else {
            // obtengo propiedades de la DB 
            $properties = $this->model->getAll();
        }



        //verifico si trajo las propiedadesde la db
        if (!$properties) {
            $errors[] = 'Ocurrio un problema al obtener los datos de las propiedades';
        } else {
            //hacemos un foreach para buscar cada dueño de las propiedades en la db y asignarselos
            foreach ($properties as $property) {
                $owner = $this->modelOwner->get($property->id_owner); //buscamos el owner en la base de datos
                if (!$owner) { //en caso de que no se encontrara el dueño avisar
                    $owner = 'no registrado, probablemente fue eliminado';
                }
                $property->duenio = $owner->name; //le asignamos el nombre del duenio a 7la propiedad
            }
        }

        // //uso el modelo de owners para traer todos los dueños -> lo usabamos en el tp2 para el filtrado en la vista
        // $owners = $this->modelOwner->getAll();

        // if (!$owners) {
        //     $errors[] = 'Ocurrio un problema al obtener los datos de los dueños';
        // }

        if (count($errors) > 0) {
            return $this->view->response(['error' => 'Ocurrio un problema al obtener los datos: ' . implode(', ', $errors)], 500);
        }
        //guardo la información en un arreglo
        $response = [
            'propiedades' => $properties
        ];

        // mando las propiedades y dueños a la vista en forma de arreglo
        return $this->view->response($response, 200);
    }


    // public function getPropertiesForOwner()
    // {

    //     $optionFilterId = $_GET['filterOwner']; //guardo el valor selecciondo por el us

    //     // obtengo todas las propiedades de la DB 
    //     $properties = $this->model->getPropertiesForOwner($optionFilterId);
    //     //uso el modelo de owners para traer todos los dueños
    //     $owners = $this->modelOwner->getAll();
    //     // mando las propiedades a la vista y todos los dueños
    //     return $this->view->showProperties($properties, $owners);
    // }


    public function get($req, $res)
    {
        // obtengo el id de la tarea desde la ruta
        $id = $req->params->id;

        // obtengo una propiedad de la DB
        $property = $this->model->get($id);

        if ($property) { //si trae propiedades 
            //capturo el id_owner de esa propiedad
            $idOwner = $property->id_owner;
            //busco el owner por medio del id_owner
            $owner = $this->modelOwner->get($idOwner);
            $property->duenio = $owner->name;

            $response = [
                'propiedad' => $property,
            ];

            // mando la propiedad y el dueño a la vista 
            return $this->view->response($response);
        } else {
            return $this->view->response('Error: No se encontró la propiedad', 404);
        }
    }

    // public function deleteProperty($id)
    // {

    //     // obtengo una propiedad de la DB 
    //     $property = $this->model->get($id);

    //     // chequear si existe lo que se quiere borrar 
    //     if (!$property) { //no existe ,retorna null
    //         return $this->view->showError("No Existe la propiedad con el id: $id ");
    //     } //se puede eliminar una propiedad si tiene duenio

    //     $this->model->delete($id);
    //     header('Location: ' . BASE_URL); /* PARA REDIRIJIR AL HOME UNA VEZ ELIMINADA  la propiedad */
    //     exit();
    // }

    public function update($req, $res)
    {
        if (!$res->user) {
            return $this->view->response("No autorizado", 401);
        }



        $errors = [];

        $id = $req->params->id;

        // obtengo un propiedad de la DB 
        $property = $this->model->get($id);
        // chequear si existe lo que se quiere modificar  
        if (!$property) {
            return $this->view->response('Error:No Existe la propiedad', 404);
        }

        // tomar datos del form ingresados por el usuario y validarlos (funcion del contoller)
        if (!isset($req->body->typePropertyEdit) || is_null($req->body->typePropertyEdit) || trim($req->body->typePropertyEdit) === '') {
            $errors[] = "El campo tipo es requerido";
        }
        //verificar si ingresa una opcion no valida en el select del input type 
        if (!$this->validOption($req->body->typePropertyEdit, $this->optionsTypeProperty)) {
            $errors[] = "No seleccionó una opción válida para el campo tipo";
        }
        //Validar que el tipo no exceda los 20 caracteres
        if (strlen($req->body->typePropertyEdit) > 20) {
            $errors[] = "El campo tipo no puede exceder los 20 caracteres";
        }
        // Validar que sólo contenga letras y espacios
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->typePropertyEdit)) {
            $errors[] = "El campo tipo sólo puede contener letras y espacios";
        }

        if (!isset($req->body->zonePropertyEdit) || is_null($req->body->zonePropertyEdit) || trim($req->body->zonePropertyEdit) === '') {
            $errors[] = "El campo zona es requerido";
        }
        //Validar que no exceda los 45 caracteres
        if (strlen($req->body->zonePropertyEdit) > 45) {
            $errors[] = "El campo zona no puede exceder los 45 caracteres";
        }
        // validar que sean letras, espacios o números
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $req->body->zonePropertyEdit)) {
            $errors[] = "El campo sólo admite letras, espacios o números";
        }

        if (!isset(($req->body->pricePropertyEdit)) || is_null(intval($req->body->pricePropertyEdit)) || trim(intval($req->body->pricePropertyEdit)) === '') {
            $errors[] = "El campo precio es requerido";
        }
        //Validar que sea un número comprendido en un rango validos y que tenga un máximo de 10 dígitos
        if (intval($req->body->pricePropertyEdit) < 0 || intval($req->body->pricePropertyEdit) > 9999999999) {
            $errors[] = "El campo precio esta fuera de rango, tiene que ser mayor a 0 y tener un maximo de 10 digitos.";
        }

        // la descripcion es requerida
        if (!isset($req->body->descriptionPropertyEdit) || is_null($req->body->descriptionPropertyEdit) || trim($req->body->descriptionPropertyEdit) === '') {
            $errors[] = "El campo descripción es requerido";
        }
        // Validar que la descripción no exceda los 500 caracteres
        if (strlen($req->body->descriptionPropertyEdit) > 500) {
            $errors[] = "El campo descripción no puede exceder los 500 caracteres";
        }

        // modo es requerido
        if (!isset($req->body->modePropertyEdit) || is_null($req->body->modePropertyEdit) || trim($req->body->modePropertyEdit) === '') {
            $errors[] = "El modo es requerido";
        }
        //verificar si ingresa una opcion no valida en el select del input MODE 
        if (!$this->validOption($req->body->modePropertyEdit, $this->optionsModeProperty)) {
            $errors[] = "No seleccionó una opción válida para el campo modo";
        }
        //Validar que el modo no exceda los 20 caracteres
        if (strlen($req->body->modePropertyEdit) > 20) {
            $errors[] = "El campo modo no puede exceder los 20 caracteres";
        }
        // Validar que sólo contenga letras y espacios
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->modePropertyEdit)) {
            $errors[] = "El campo modo sólo puede contener letras y espacios";
        }

        if (!isset($req->body->statusPropertyEdit) || is_null($req->body->statusPropertyEdit) || trim($req->body->statusPropertyEdit) === '') {
            $errors[] = "El campo estado es requerido";
        }

        if (strlen($req->body->statusPropertyEdit) > 20) {
            $errors[] = "El campo estado no puede exceder los 20 caracteres";
        }

        //verificar si ingresa una opcion no valida en el select del input MODE 
        if (!$this->validOption($req->body->statusPropertyEdit, $this->optionsStatusProperty)) {
            $errors[] = "No seleccionó una opción válida para el campo estado";
        }

        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->statusPropertyEdit)) {
            $errors[] = "El campo estado sólo puede contener letras y espacios";
        }

        //   city es requerida
        if (!isset($req->body->cityPropertyEdit) || is_null($req->body->cityPropertyEdit) || trim($req->body->cityPropertyEdit) === '') {
            $errors[] = "El campo ciudad es requerido";
        }
        //Validar que ciyu no exceda los 45 caracteres
        if (strlen($req->body->cityPropertyEdit) > 45) {
            $errors[] = "El campo ciudad no puede exceder los 45 caracteres";
        }
        // Validar que sólo contenga letras y espacios
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->cityPropertyEdit)) {
            $errors[] = "El campo ciudad sólo puede contener letras y espacios";
        }

        if (!isset($req->body->id_ownerPropertyEdit) || is_null($req->body->id_ownerPropertyEdit) || trim($req->body->id_ownerPropertyEdit) === '') {
            $errors[] = "El campo dueño es requerido";
        }
        // existe el owner en la bd ? 
        if (!$this->modelOwner->get($req->body->id_ownerPropertyEdit)) {
            $errors[] = "El dueño no existe en la base de datos ";
        }

        // Si hay errores, mostrarlos
        if (count($errors) > 0) {
            // $errosString = implode(", ", $errors); //convierto el areglo de errores a string
            return $this->view->response($errors, 400); //400: error faltan completar datos     
        } else {
            // si los datos del usuario pasaron todas las validaciones 
            $type = $req->body->typePropertyEdit;
            $zone = $req->body->zonePropertyEdit;
            $price = intval($req->body->pricePropertyEdit); //quedarse solo con la parte entera 
            $description = $req->body->descriptionPropertyEdit;
            $mode = $req->body->modePropertyEdit;
            $status = $req->body->statusPropertyEdit;
            $city = $req->body->cityPropertyEdit;
            $id_owner = $req->body->id_ownerPropertyEdit;

            $rowModified = $this->model->update($id, $type, $zone, $price, $description, $mode, $status, $city, $id_owner);

            // no se modifico nungun campo 
            if (!$rowModified) {
                $this->view->response('Error: No se pudo modificar', 500);
            }

            // obtengo la propiedad modificada y la devuelvo en la respuesta
            $property = $this->model->get($id);
            $this->view->response($property, 200);
        }
    }

    //chequear si la opción ingresada por el usuario es un valor para el select valido , le paso el campo y los valores posibles 
    public function validOption($field, $optionsField)
    {
        $valid = false;
        for ($i = 0; $i < count($optionsField); $i++) {
            if ($optionsField[$i] == $field) {
                $valid = true;
            }
        }
        return $valid;
    }

    public function create($req, $res)
    {
        if (!$res->user) {//verificacion de que haya un usuario logueado
            return $this->view->response("No autorizado", 401);
        }
        $errors = [];

        // tomar datos del form ingresados por el usuario y validarlos (funcion  del contoller)
        if (!isset($req->body->typePropertyAdd) || is_null($req->body->typePropertyAdd) || trim($req->body->typePropertyAdd) === '') {
            $errors[] = "El campo tipo es requerido";
        }
        if (!$this->validOption($req->body->typePropertyAdd, $this->optionsTypeProperty)) {
            $errors[] = "No seleccionó una opción válida para el campo tipo";
        }
        if (strlen($req->body->typePropertyAdd) > 20) {
            $errors[] = "El campo tipo no puede exceder los 20 caracteres";
        }
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->typePropertyAdd)) {
            $errors[] = "El campo tipo sólo puede contener letras y espacios";
        }

        if (!isset($req->body->zonePropertyAdd) || is_null($req->body->zonePropertyAdd) || trim($req->body->zonePropertyAdd) === '') {
            $errors[] = "El campo zona es requerido";
        }
        //Validar que no exceda los 45 caracteres
        if (strlen($req->body->zonePropertyAdd) > 45) {
            $errors[] = "El campo zona no puede exceder los 45 caracteres";
        }
        // validar que sean letras, espacios o números
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $req->body->zonePropertyAdd)) {
            $errors[] = "El campo sólo admite letras, espacios o números";
        }


        // Verificar si el campo precio existe, no es null, ni vacío
        if (!isset(($req->body->pricePropertyAdd)) || is_null(intval($req->body->pricePropertyAdd)) || trim(intval($req->body->pricePropertyAdd)) === '') {
            $errors[] = "El campo precio es requerido";
        }
        //Validar que sea un número comprendido en un rango validos y que tenga un máximo de 10 dígitos
        if (intval($req->body->pricePropertyAdd) < 0 || intval($req->body->pricePropertyAdd) > 9999999999) {
            $errors[] = "El campo precio esta fuera de rango, tiene que ser mayor a 0 y tener un maximo de 10 digitos.";
        }

        //  la descripcion es requerida
        if (!isset($req->body->descriptionPropertyAdd) || is_null($req->body->descriptionPropertyAdd) || trim($req->body->descriptionPropertyAdd) === '') {
            $errors[] = "El campo descripción es requerido";
        }
        // Validar que la descripción no exceda los 500 caracteres
        if (strlen($req->body->descriptionPropertyAdd) > 500) {
            $errors[] = "El campo descripción no puede exceder los 500 caracteres";
        }

        //   modo es requerido
        if (!isset($req->body->modePropertyAdd) || is_null($req->body->modePropertyAdd) || trim($req->body->modePropertyAdd) === '') {
            $errors[] = "El modo es requerido";
        }
        //verificar si ingresa una opcion no valida en el select del input MODE 
        if (!$this->validOption($req->body->modePropertyAdd, $this->optionsModeProperty)) {
            $errors[] = "No seleccionó una opción válida para el campo modo";
        }
        //Validar que el modo no exceda los 20 caracteres
        if (strlen($req->body->modePropertyAdd) > 20) {
            $errors[] = "El campo modo no puede exceder los 20 caracteres";
        }
        // Validar que sólo contenga letras y espacios
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->modePropertyAdd)) {
            $errors[] = "El campo modo sólo puede contener letras y espacios";
        }

        if (!isset($req->body->statusPropertyAdd) || is_null($req->body->statusPropertyAdd) || trim($req->body->statusPropertyAdd) === '') {
            $errors[] = "El campo estado es requerido";
        }
        //Validar que el status no exceda los 20 caracteres
        if (strlen($req->body->statusPropertyAdd) > 20) {
            $errors[] = "El campo estado no puede exceder los 20 caracteres";
        }
        //verificar si ingresa una opcion no valida en el select del input MODE 
        if (!$this->validOption($req->body->statusPropertyAdd, $this->optionsStatusProperty)) {
            $errors[] = "No seleccionó una opción válida para el campo estado";
        }
        // Validar que sólo contenga letras y espacios
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->statusPropertyAdd)) {
            $errors[] = "El campo estado sólo puede contener letras y espacios";
        }

        //  la city es requerida
        if (!isset($req->body->cityPropertyAdd) || is_null($req->body->cityPropertyAdd) || trim($req->body->cityPropertyAdd) === '') {
            $errors[] = "El campo ciudad es requerido";
        }
        //Validar que ciyu no exceda los 45 caracteres
        if (strlen($req->body->cityPropertyAdd) > 45) {
            $errors[] = "El campo ciudad no puede exceder los 45 caracteres";
        }
        // Validar que sólo contenga letras y espacios
        if (!preg_match("/^[A-Za-z\s]+$/", $req->body->cityPropertyAdd)) {
            $errors[] = "El campo ciudad sólo puede contener letras y espacios";
        }

        if (!isset($req->body->id_ownerPropertyAdd) || is_null($req->body->id_ownerPropertyAdd) || trim($req->body->id_ownerPropertyAdd) === '') {
            $errors[] = "El campo dueño es requerido";
        }
        // existe el owner en la bd ? 
        if (!$this->modelOwner->get($req->body->id_ownerPropertyAdd)) {
            $errors[] = "El dueño no existe en la base de datos ";
        }

        // Si hay errores, mostrarlos
        if (count($errors) > 0) {
            // $errosString = implode(", ", $errors); //convierto el areglo de errores a string
            return $this->view->response($errors, 400); //400: error faltan completar datos     
        } else {
            // si los datos del usuario pasaron todas las validaciones 
            $type = $req->body->typePropertyAdd;
            $zone = $req->body->zonePropertyAdd;
            $price = intval($req->body->pricePropertyAdd); //quedarse solo con la parte entera 
            $description = $req->body->descriptionPropertyAdd;
            $mode = $req->body->modePropertyAdd;
            $status = $req->body->statusPropertyAdd;
            $city = $req->body->cityPropertyAdd;
            $id_owner = $req->body->id_ownerPropertyAdd;

            $id =  $this->model->add($type, $zone, $price, $description, $mode, $status, $city, $id_owner);
            if (!$id) {
                return $this->view->response('error al insertar propiedad', 500);
            }

            //buena practica es devolver el recurso insertado(api)
            $property = $this->model->get($id);
            return $this->view->response($property, 201); //rec creado con exito

        }
    }
}
