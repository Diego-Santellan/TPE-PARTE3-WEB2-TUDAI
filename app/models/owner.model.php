<?php
require_once './app/models/modelConectDB.php';
// CLASS
class OwnerModel extends ModelConectDB
{ // Cada modelo hijo hereda de la clase padre la conexion a la DB,seria el paso 1 para no reprtir codigo, la clase padre abre la conexion a la bd

    public function getAll()
    {
        // 2. Ejecuto la consulta
        $query = $this->db->prepare('SELECT * FROM duenio');
        $query->execute();

        // 3. Obtengo los datos en un arreglo de objetos
        $owners = $query->fetchAll(PDO::FETCH_OBJ);

        return $owners;
    }

    public function get($id)
    {
        // 2. Ejecuto la consulta
        $query = $this->db->prepare('SELECT * FROM duenio WHERE id_owner = ?');
        $query->execute([$id]);

        // 3. Obtengo los datos en un objeto 
        $owner = $query->fetch(PDO::FETCH_OBJ);

        return $owner; // Si no encuentra ningún registro en la base de datos con el ID proporcionado, fetch() devolverá false.
    }

    public function delete($id)
    {
        // 2. Ejecuto la consulta
        $query = $this->db->prepare('DELETE FROM duenio WHERE id_owner = ?');
        $query->execute([$id]);
        
      
    }
    public function update($id, $name, $phone, $email)
    {
        // 2. Ejecuto la consulta Modificar registros de una tabla en 2 pasos apra evitar la inyeccion de datos          
        $query = $this->db->prepare('UPDATE duenio SET  name = ?, phone = ?, email = ? WHERE id_owner = ?');
        $query->execute([$name, $phone, $email, $id]);
    }

    public function add($name, $phone, $email)
    {
        // 2. Ejecuto la consulta insertar registros de una tabla en 2 pasos apra evitar la inyeccion de datos          
        $query = $this->db->prepare('INSERT INTO duenio (name, phone, email) VALUES (?, ?, ?)');

        $query->execute([$name, $phone, $email]);
        $id = $this->db->lastInsertId();

        return $id;
    }
    public function HasProperties($id)
    {
        $query = $this->db->prepare('SELECT * FROM propiedad WHERE id_owner = ?');
        
        // Ejecutar la consulta
        $query->execute([$id]);
    
        
        $properties = $query->fetchAll(PDO::FETCH_OBJ);

        if(count($properties)>0){
            return true;
        } else{
            return false;
        }
    }
}
