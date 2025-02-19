<?php
require_once './app/models/modelConectDB.php';
// CLASS
class PropertyModel extends ModelConectDB
{ // Cada modelo hijo hereda de la clase padre la conexion a la DB,seria el paso 1 para no reprtir codigo, la clase padre abre la conexion a la bd



    public function getAll()
    {
        // 2. Ejecuto la consulta
        $query = $this->db->prepare('SELECT * FROM propiedad');
        $query->execute();

        // 3. Obtengo los datos en un arreglo de objetos
        $properties = $query->fetchAll(PDO::FETCH_OBJ);

        return $properties;
    }


    public function getAllOrder($nombreCol, $mode)
    {

        // 2. Ejecuto la consulta
        $sql = "SELECT * FROM propiedad ORDER BY $nombreCol $mode";
        $query = $this->db->prepare($sql);
        $query->execute();

        // 3. Obtengo los datos en un arreglo de objetos
        $properties = $query->fetchAll(PDO::FETCH_OBJ);

        return $properties;
    }

    public function countProperties()
    {


        $query = $this->db->prepare(' SELECT COUNT(*) AS total_propiedad FROM propiedad');
        $query->execute();

        // 3. Obtengo los datos $query->fetch(PDO::FETCH_ASSOC): Esto obtiene el resultado de la consulta como un arreglo asociativo.
        // ['total_propiedad']: Accedemos a la columna total_propiedad del arreglo, que contiene el número total de registros en la tabla propiedad
        $total = $query->fetch(PDO::FETCH_ASSOC)['total_propiedad'];

        return $total;
    }

    public function getPagination($quantity, $numberPage)
    {
        //5-10-15         2 


        $inicioDeConsulta = ($numberPage - 1) * $quantity; //-1 por que traigo los datos en un arreglo 

        // 2. Ejecuto la consulta
        $sql = "SELECT * FROM propiedad LIMIT $inicioDeConsulta, $quantity";
        $query = $this->db->prepare($sql);
        $query->execute();

        // 3. Obtengo los datos en un arreglo de objetos
        $properties = $query->fetchAll(PDO::FETCH_OBJ);

        return $properties;
    }


    public function getAllFilter($filter_by, $filter_value)
    {
        // 2. Ejecuto la consulta
        $sql = "SELECT * FROM propiedad WHERE $filter_by = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$filter_value]);

        // 3. Obtengo los datos en un arreglo de objetos
        $properties = $query->fetchAll(PDO::FETCH_OBJ);

        return $properties;
    }

    public function getPropertiesForOwner($id_owner)
    {
        // traer todas las propiedades donde el dueño es quien llega por params 
        $query = $this->db->prepare('SELECT * FROM propiedad WHERE id_owner=?');
        $query->execute([$id_owner]);

        $propertiesOwner = $query->fetchAll(PDO::FETCH_OBJ);
        return $propertiesOwner;
    }

    public function get($id)
    {
        // 2. Ejecuto la consulta
        $query = $this->db->prepare('SELECT * FROM propiedad WHERE id_property = ?');
        $query->execute([$id]);

        // 3. Obtengo los datos en un arreglo de objetos
        $property = $query->fetch(PDO::FETCH_OBJ);

        return $property; // Si no encuentra ningún registro en la base de datos con el ID proporcionado, fetch() devolverá false.
    }

    public function delete($id)
    {
        // 2. Ejecuto la consulta
        $query = $this->db->prepare('DELETE FROM propiedad WHERE id_property = ?');
        $query->execute([$id]);
    }
    public function update($id_property, $type, $zone, $price, $description, $mode, $status, $city, $id_owner)
    {
        // 2. Ejecuto la consulta Modificar registros de una tabla en 2 pasos apra evitar la inyeccion de datos          
        $query = $this->db->prepare('UPDATE propiedad SET  type = ?, zone = ?, price = ?, description = ?, mode = ?, status = ?, city = ?, id_owner = ? WHERE id_property = ?'); //nombre de las cols de la db
        $query->execute([$type, $zone, $price, $description, $mode, $status, $city, $id_owner, $id_property]); //variables donde guaarde lo que viene por post

        $rowModifies = $query->rowCount();

        return $rowModifies;
    }

    public function add($type, $zone, $price, $description, $mode, $status, $city, $id_owner)
    {
        // 2. Ejecuto la consulta insertar registros de una tabla en 2 pasos apra evitar la inyeccion de datos          
        $query = $this->db->prepare('INSERT INTO propiedad (type, zone, price, description, mode, status, city, id_owner) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

        $query->execute([$type, $zone, $price, $description, $mode, $status, $city, $id_owner]);
        $id = $this->db->lastInsertId();

        return $id;
    }
}
