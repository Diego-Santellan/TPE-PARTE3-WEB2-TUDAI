<?php
/* REQUERIMOS MODELO Y VISTA : el controller es un pasamanos*/
require_once './app/models/property.model.php';
require_once './app/models/owner.model.php';
require_once './app/views/json.view.php';

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

    // CONSTRUCTOR
    public function __construct()
    {
        // este controler requiere dos modelos (propiedads y owner)
        $this->model = new PropertyModel();
        $this->modelOwner = new OwnerModel();
        $this->view = new JSONView();
    }

    // MÉTODOS O FUNCIONES DE LA CLASE  

    public function getAll($req, $res)
    {
        try {
            // obtengo propiedades de la DB 
            $properties = $this->model->getAll();
            //uso el modelo de owners para traer todos los dueños
            $owners = $this->modelOwner->getAll();

            //guardo la informacion en un arreglo
            $response = [
                'propiedades' => $properties,
                'dueños' => $owners
            ];

            // mando las propiedades a la vista y todos los dueños
            return $this->view->response($response);
        } catch (Exception $e) {
            return $this->view->response([
                'error' => 'Ocurrió un problema al obtener los datos: ' . $e->getMessage()
            ], 500);
        }
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


    public function get($req,$res)
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

                
                $response = [
                    'propiedad' => $property,
                    'dueño' => $owner
                ];
                // mando la propiedad y el dueño a la vista 
                return $this->view->response($response);
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

    // public function updateProperty($id)
    // {
    //     $errors = [];

    //     // obtengo un propiedad de la DB 
    //     $property = $this->model->get($id);
    //     // chequear si existe lo que se quiere modificar  
    //     if (!$property) {
    //         return $this->view->showError("No Existe la propiedad con el id: $id ");
    //     }

    //     // tomar datos del form ingresados por el usuario y validarlos , funcion importante del contoller 



    //     // VALIDACIONES TYPE
    //     // Verificar si el campo existe, no es null, ni vacío
    //     if (!isset($_POST['typePropertyEdit']) || is_null($_POST['typePropertyEdit']) || trim($_POST['typePropertyEdit']) === '') {
    //         $errors[] = "El campo tipo es requerido";
    //     }
    //     //verificar si ingresa una opcion no valida en el select del input type 
    //     if (!$this->validOption($_POST['typePropertyEdit'], $this->optionsTypeProperty)) {
    //         $errors[] = "No seleccionó una opción válida para el campo tipo";
    //     }
    //     //Validar que el tipo no exceda los 20 caracteres
    //     if (strlen($_POST['typePropertyEdit']) > 20) {
    //         $errors[] = "El campo tipo no puede exceder los 20 caracteres";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['typePropertyEdit'])) {
    //         $errors[] = "El campo tipo sólo puede contener letras y espacios";
    //     }

    //     // VALIDACIONES ZONE
    //     // Verificar si el campo existe, no es null, ni vacío
    //     if (!isset($_POST['zonePropertyEdit']) || is_null($_POST['zonePropertyEdit']) || trim($_POST['zonePropertyEdit']) === '') {
    //         $errors[] = "El campo zona es requerido";
    //     }
    //     //Validar que el zone no exceda los 45 caracteres
    //     if (strlen($_POST['zonePropertyEdit']) > 45) {
    //         $errors[] = "El campo zona no puede exceder los 45 caracteres";
    //     }
    //     // validar que sean letras, espacios o números
    //     if (!preg_match("/^[a-zA-Z0-9\s]+$/", $_POST['zonePropertyEdit'])) {
    //         $errors[] = "El campo zona solo admite letras, espacios o números";
    //     }



    //     // *********    VALIDACIONES PRECIO     *********//
    //     // Verificar si el campo existe, no es null, ni vacío
    //     if (!isset($_POST['pricePropertyEdit']) || is_null(intval($_POST['pricePropertyEdit'])) || trim(intval($_POST['pricePropertyEdit'])) === '') {
    //         $errors[] = "El campo precio es requerido";
    //     }
    //     //Validar que sea un número comprendido en un rango validos y que tenga un máximo de 10 dígitos
    //     if (intval($_POST['pricePropertyEdit']) < 0 || intval($_POST['pricePropertyEdit']) > 9999999999) {
    //         $errors[] = "El campo precio esta fuera de rango, tiene que ser mayor a 0 y tener un maximo de 10 digitos.";
    //     }



    //     // *********     VALIDACIONES DESCRIPTION   *********//
    //     // que la descripcion sea requerida
    //     if (!isset($_POST['descriptionPropertyEdit']) || is_null($_POST['descriptionPropertyEdit']) || trim($_POST['descriptionPropertyEdit']) === '') {
    //         $errors[] = "El campo descripción  es requerido";
    //     }
    //     // Validar que la descripción no exceda los 500 caracteres
    //     if (strlen($_POST['descriptionPropertyEdit']) > 500) {
    //         $errors[] = "El campo descripción no puede exceder los 500 caracteres";
    //     }



    //     //*********     VALIDACIONES MODE   *********//
    //     // que la modo sea requerida
    //     if (!isset($_POST['modePropertyEdit']) || is_null($_POST['modePropertyEdit']) || trim($_POST['modePropertyEdit']) === '') {
    //         $errors[] = "El modo es requerido";
    //     }
    //     //verificar si ingresa una opcion no valida en el select del input MODE 
    //     if (!$this->validOption($_POST['modePropertyEdit'], $this->optionsModeProperty)) {
    //         $errors[] = "No seleccionó una opción válida para el campo mode";
    //     }
    //     //Validar que el modo no exceda los 20 caracteres
    //     if (strlen($_POST['modePropertyEdit']) > 20) {
    //         $errors[] = "El campo modo no puede exceder los 20 caracteres";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['modePropertyEdit'])) {
    //         $errors[] = "El campo modo sólo puede contener letras y espacios";
    //     }



    //     //*********     VALIDACIONES STATUS     *********//
    //     // que la status sea requerida
    //     if (!isset($_POST['statusPropertyEdit']) || is_null($_POST['statusPropertyEdit']) || trim($_POST['statusPropertyEdit']) === '') {
    //         $errors[] = "El campo estado es requerido";
    //     }
    //     //Validar que el status no exceda los 20 caracteres
    //     if (strlen($_POST['statusPropertyEdit']) > 20) {
    //         $errors[] = "El campo estado no puede exceder los 20 caracteres";
    //     }
    //     //verificar si ingresa una opcion no valida en el select del input status 
    //     if (!$this->validOption($_POST['statusPropertyEdit'], $this->optionsStatusProperty)) {
    //         $errors[] = "No seleccionó una opción válida para el campo estado";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['statusPropertyEdit'])) {
    //         $errors[] = "El campo estado sólo puede contener letras y espacios";
    //     }



    //     //*********     VALIDACIONES CITY     *********//
    //     // que la status sea requerida
    //     if (!isset($_POST['cityPropertyEdit']) || is_null($_POST['cityPropertyEdit']) || trim($_POST['cityPropertyEdit']) === '') {
    //         $errors[] = "El campo ciudad es requerido";
    //     }
    //     //Validar que ciyu no exceda los 45 caracteres
    //     if (strlen($_POST['cityPropertyEdit']) > 45) {
    //         $errors[] = "El campo ciudad no puede exceder los 45 caracteres";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['cityPropertyEdit'])) {
    //         $errors[] = "El campo ciudad sólo puede contener letras y espacios";
    //     }

    //     //*********     VALIDACIONES ID_OWNER     *********//

    //     if (!isset($_POST['id_ownerPropertyEdit']) || is_null($_POST['id_ownerPropertyEdit']) || trim($_POST['id_ownerPropertyEdit']) === '') {
    //         $errors[] = "El campo dueño es requerido";
    //     }
    //     // existe el owner en la bd ? 
    //     if (!$this->modelOwner->get($_POST['id_ownerPropertyEdit'])) {
    //         $errors[] = "El dueño no existe en la base de datos ";
    //     }




    //     if (count($errors) > 0) {
    //         $errosString = implode(", ", $errors); //convierto el areglo de errores a string
    //         return $this->view->showError($errosString);
    //     } // si los datos del usuario pasaron todas las validaciones 
    //     else {

    //         $type = $_POST['typePropertyEdit']; //name del formulario 
    //         $zone = $_POST['zonePropertyEdit'];
    //         $price = intval($_POST['pricePropertyEdit']); //quedarse sólo con la parte entera 
    //         $description = $_POST['descriptionPropertyEdit'];
    //         $mode = $_POST['modePropertyEdit'];
    //         $status = $_POST['statusPropertyEdit'];
    //         $city = $_POST['cityPropertyEdit'];
    //         $id_owner = $_POST['id_ownerPropertyEdit'];

    //         $this->model->update($id, $type, $zone, $price, $description, $mode, $status, $city, $id_owner);
    //         header('Location: ' . BASE_URL);
    //         exit();
    //     }
    // }

    // // chequear si la opción ingresada por el usuario es un valor para el select valido , le paso el campo y los valores posibles 
    // public function validOption($field, $optionsField)
    // {
    //     $valid = false;
    //     for ($i = 0; $i < count($optionsField); $i++) {
    //         if ($optionsField[$i] == $field) {
    //             $valid = true;
    //         }
    //     }
    //     return $valid;
    // }
    // public function addProperty()
    // {
    //     $errors = [];

    //     // tomar datos del form ingresados por el usuario y validarlos , funcion importante del contoller 


    //     // VALIDACIONES TYPE
    //     // Verificar si el campo existe, no es null, ni vacío
    //     if (!isset($_POST['typePropertyAdd']) || is_null($_POST['typePropertyAdd']) || trim($_POST['typePropertyAdd']) === '') {
    //         $errors[] = "El campo tipo es requerido";
    //     }
    //     //verificar si ingresa una opcion no valida en el select del input type 
    //     if (!$this->validOption($_POST['typePropertyAdd'], $this->optionsTypeProperty)) {
    //         $errors[] = "No seleccionó una opción válida para el campo tipo";
    //     }
    //     //Validar que el tipo no exceda los 20 caracteres
    //     if (strlen($_POST['typePropertyAdd']) > 20) {
    //         $errors[] = "El campo tipo no puede exceder los 20 caracteres";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['typePropertyAdd'])) {
    //         $errors[] = "El campo tipo sólo puede contener letras y espacios";
    //     }

    //     // VALIDACIONES ZONE
    //     // Verificar si el campo existe, no es null, ni vacío
    //     if (!isset($_POST['zonePropertyAdd']) || is_null($_POST['zonePropertyAdd']) || trim($_POST['zonePropertyAdd']) === '') {
    //         $errors[] = "El campo zona es requerido";
    //     }
    //     //Validar que no exceda los 45 caracteres
    //     if (strlen($_POST['zonePropertyAdd']) > 45) {
    //         $errors[] = "El campo zona no puede exceder los 45 caracteres";
    //     }
    //     // validar que sean letras, espacios o números
    //     if (!preg_match("/^[a-zA-Z0-9\s]+$/", $_POST['zonePropertyAdd'])) {
    //         $errors[] = "El campo sólo admite letras, espacios o números";
    //     }


    //     // *********    VALIDACIONES PRECIO     *********//
    //     // Verificar si el campo existe, no es null, ni vacío
    //     if (!isset(($_POST['pricePropertyAdd'])) || is_null(intval($_POST['pricePropertyAdd'])) || trim(intval($_POST['pricePropertyAdd'])) === '') {
    //         $errors[] = "El campo precio es requerido";
    //     }
    //     //Validar que sea un número comprendido en un rango validos y que tenga un máximo de 10 dígitos
    //     if (intval($_POST['pricePropertyAdd']) < 0 || intval($_POST['pricePropertyAdd']) > 9999999999) {
    //         $errors[] = "El campo precio esta fuera de rango, tiene que ser mayor a 0 y tener un maximo de 10 digitos.";
    //     }



    //     // *********     VALIDACIONES DESCRIPTION   *********//
    //     // que la descripcion sea requerida
    //     if (!isset($_POST['descriptionPropertyAdd']) || is_null($_POST['descriptionPropertyAdd']) || trim($_POST['descriptionPropertyAdd']) === '') {
    //         $errors[] = "El campo descripción es requerido";
    //     }
    //     // Validar que la descripción no exceda los 500 caracteres
    //     if (strlen($_POST['descriptionPropertyAdd']) > 500) {
    //         $errors[] = "El campo descripción no puede exceder los 500 caracteres";
    //     }



    //     //*********     VALIDACIONES MODE   *********//
    //     // que la modo sea requerida
    //     if (!isset($_POST['modePropertyAdd']) || is_null($_POST['modePropertyAdd']) || trim($_POST['modePropertyAdd']) === '') {
    //         $errors[] = "El modo es requerido";
    //     }
    //     //verificar si ingresa una opcion no valida en el select del input MODE 
    //     if (!$this->validOption($_POST['modePropertyAdd'], $this->optionsModeProperty)) {
    //         $errors[] = "No seleccionó una opción válida para el campo mode";
    //     }
    //     //Validar que el modo no exceda los 20 caracteres
    //     if (strlen($_POST['modePropertyAdd']) > 20) {
    //         $errors[] = "El campo modo no puede exceder los 20 caracteres";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['modePropertyAdd'])) {
    //         $errors[] = "El campo modo sólo puede contener letras y espacios";
    //     }



    //     //*********     VALIDACIONES STATUS     *********//
    //     // que el status sea requerida
    //     if (!isset($_POST['statusPropertyAdd']) || is_null($_POST['statusPropertyAdd']) || trim($_POST['statusPropertyAdd']) === '') {
    //         $errors[] = "El campo estado es requerido";
    //     }
    //     //Validar que el status no exceda los 20 caracteres
    //     if (strlen($_POST['statusPropertyAdd']) > 20) {
    //         $errors[] = "El campo estado no puede exceder los 20 caracteres";
    //     }
    //     //verificar si ingresa una opcion no valida en el select del input MODE 
    //     if (!$this->validOption($_POST['statusPropertyAdd'], $this->optionsStatusProperty)) {
    //         $errors[] = "No seleccionó una opción válida para el campo estado";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['statusPropertyAdd'])) {
    //         $errors[] = "El campo estado sólo puede contener letras y espacios";
    //     }



    //     //*********     VALIDACIONES CITY     *********//
    //     // que la status sea requerida
    //     if (!isset($_POST['cityPropertyAdd']) || is_null($_POST['cityPropertyAdd']) || trim($_POST['cityPropertyAdd']) === '') {
    //         $errors[] = "El campo ciudad es requerido";
    //     }
    //     //Validar que ciyu no exceda los 45 caracteres
    //     if (strlen($_POST['cityPropertyAdd']) > 45) {
    //         $errors[] = "El campo ciudad no puede exceder los 45 caracteres";
    //     }
    //     // Validar que sólo contenga letras y espacios
    //     if (!preg_match("/^[A-Za-z\s]+$/", $_POST['cityPropertyAdd'])) {
    //         $errors[] = "El campo ciudad sólo puede contener letras y espacios";
    //     }



    //     //*********     VALIDACIONES ID_OWNER     *********//

    //     if (!isset($_POST['id_ownerPropertyAdd']) || is_null($_POST['id_ownerPropertyAdd']) || trim($_POST['id_ownerPropertyAdd']) === '') {
    //         $errors[] = "El campo dueño es requerido";
    //     }
    //     // existe el owner en la bd ? 
    //     if (!$this->modelOwner->get($_POST['id_ownerPropertyAdd'])) {
    //         $errors[] = "El dueño no existe en la base de datos ";
    //     }

    //     // Si hay errores, mostrarlos
    //     if (count($errors) > 0) {
    //         $errosString = implode(", ", $errors); //convierto el areglo de errores a string
    //         return $this->view->showError($errosString);
    //     } else { // si los datos del usuario pasaron todas las validaciones 

    //         $type = $_POST['typePropertyAdd'];
    //         $zone = $_POST['zonePropertyAdd'];
    //         $price = intval($_POST['pricePropertyAdd']); //quedarse solo con la parte entera 
    //         $description = $_POST['descriptionPropertyAdd'];
    //         $mode = $_POST['modePropertyAdd'];
    //         $status = $_POST['statusPropertyAdd'];
    //         $city = $_POST['cityPropertyAdd'];
    //         $id_owner = $_POST['id_ownerPropertyAdd'];

    //        $id=  $this->model->add($type, $zone, $price, $description, $mode, $status, $city, $id_owner);
    //        if(!$id){
    //         return $this->view->showError('error al insertar propiedad');
    //        } 
    //        header('Location: ' . BASE_URL);
    //         exit();
    //     }
    // }

    // public function showError($error)
    // {
    //     return $this->view->showError($error);
    // }
}
