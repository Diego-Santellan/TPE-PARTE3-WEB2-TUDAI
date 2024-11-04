<?php
require_once './config.php';
// crea una conexión a MySQL con los datos definidos en las constantes MYSQL_HOST, MYSQL_DB, MYSQL_USER, y MYSQL_PASS de config.php.

// Para asegurarte de que todos los modelos compartan la misma conexión a la base de datos, se  crea una clase padre para los modelos que contiene la conexión a la base de datos, y todos los modelos específicos heredarán de ella. 
class ModelConectDB
{ //clase padre
    protected $db; //atributo db que guarla la url para la conecxion a la DB

    public function __construct()
    {
        try {
            // 1. abro la conexion a la DB 
            $this->db = new PDO(
                "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8",
                MYSQL_USER,
                MYSQL_PASS
            );

            // Establecer el modo de error de PDO a excepción  
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_deploy();
        } catch (PDOException $e) {
            //$e->getMessage(): un método nativo de la clase Exception (de la cual PDOException hereda). Este método devuelve un mensaje de error que describe la excepción. Proporciona información específica sobre lo que salió mal, como un error de conexión, un error en la consulta SQL. 
            echo "Error de conexión: " . $e->getMessage();
            exit; // Termina la ejecución del script en caso de error
        }
    }

    private function _deploy()
    {
        $pass ='$2y$10$KRzkpwvb7sBn389por.7oOewkyw1KEJuqylEiF26PnEGSHYJXta8K';
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll();
        if (count($tables) == 0) {
            $sql = "          
           
            SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
            START TRANSACTION;
            SET time_zone = '+00:00';
            
            
        
            CREATE DATABASE IF NOT EXISTS `inmobiliaria` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
            USE `inmobiliaria`;
            
            
            CREATE TABLE IF NOT EXISTS `duenio` (
              `id_owner` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(50) NOT NULL,
              `phone` varchar(20) NOT NULL,
              `email` varchar(80) NOT NULL,
              PRIMARY KEY (`id_owner`),
              UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            
           
            
            INSERT INTO `duenio` (`id_owner`, `name`, `phone`, `email`) VALUES
            (3, 'lisandra', '15674231', 'nuevomaillis@gmail.com'),
            (19, 'Jose', '2494496102', 'jose@gmail.com'),
            (21, 'Mariano', '2494874411', 'mariano@gmail.com'),
            (23, 'Mariana', '2494496100', 'mariana@gmail.com'),
            (25, 'Fernanda Gonzales', '2494496133', 'fer@gmail.com'),
            (26, 'Kenai', '1548754', 'kenai@mail.com'),
            (28, 'Alicia', '248471', 'ali@gmail.com'),
            (30, 'maria marta', '1234676876897', 'mariamarta@gmail.com'),
            (31, 'joaquin santellan', '1234', 'j@mail.com');
            
            
            
            CREATE TABLE IF NOT EXISTS `propiedad` (
              `id_property` int(11) NOT NULL AUTO_INCREMENT,
              `type` varchar(20) NOT NULL,
              `zone` varchar(45) NOT NULL,
              `price` decimal(10,0) NOT NULL,
              `description` varchar(500) NOT NULL,
              `mode` varchar(20) NOT NULL,
              `status` varchar(20) NOT NULL,
              `city` varchar(45) NOT NULL,
              `id_owner` int(11) NOT NULL,
              PRIMARY KEY (`id_property`),
              KEY `id_duenioFK` (`id_owner`)
            ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            
           
            
            INSERT INTO `propiedad` (`id_property`, `type`, `zone`, `price`, `description`, `mode`, `status`, `city`, `id_owner`) VALUES
            (6, 'Departamento', 'Centro', 200000, 'Departamento luminoso', 'Alquiler', 'Alquilado', 'Tandil', 3),
            (10, 'casa', 'centro', 12134, 'nueva descripcion de la casita ', 'venta', 'vendido', 'tandil', 28),
            (13, 'Lote', 'calvario', 85000, 'Terreno amplio en área residencial', 'venta', 'Disponible', 'tandil ', 19),
            (14, 'departamento', 'uncas', 1234, 'departamento en zona uncas precioso', 'venta', 'vendido', 'tandil', 25),
            (15, 'lot', 'centro', 7888, 'Hermoso lote para construccion servicios incluidos ', 'sell', 'rented', 'azul', 30),
            (16, 'quinta', 'ferrocarril', 342, 'descripcion de la quinta del ferrocarril', 'venta', 'disponible', 'tandil', 30),
            (17, 'lote', 'ferro', 9876, 'lore excepcional ', 'alquiler', 'vendido', 'tandil', 25),
            (18, 'casa', 'dique', 12000, 'casa quinta', 'venta', 'vendido', 'tandil', 28);
            
            
            CREATE TABLE IF NOT EXISTS `users` (
              `username` varchar(20) NOT NULL,
              `password` varchar(60) NOT NULL,
              `id_user` int(11) NOT NULL AUTO_INCREMENT,
              PRIMARY KEY (`id_user`),
              UNIQUE KEY `username` (`username`)
            ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
          
            INSERT INTO `users` (`username`, `password`, `id_user`) VALUES
            ('webadmin', ".$pass.", 7);
            
          
            
            ALTER TABLE `propiedad`
              ADD CONSTRAINT `propiedad_ibfk_1` FOREIGN KEY (`id_owner`) REFERENCES `duenio` (`id_owner`);
            COMMIT;
            ";


            $this->db->query($sql);
        }
    }
}


//configuración de cómo PDO (PHP Data Objects) maneja los errores que pueden ocurrir durante las operaciones de base de datos. Al establecer el modo de error a excepción, estás indicando a PDO que, en caso de un error, lance una excepción en lugar de manejarlo silenciosamente o retornar un código de error.
// ¿Por qué es importante? Manejo de errores más claro: Al lanzar excepciones, puedes capturarlas y manejarlas en tu código, lo que te permite saber exactamente qué salió mal, Durante el desarrollo, es mucho más fácil detectar y solucionar problemas cuando se lanzan excepciones.  Seguridad: Un manejo adecuado de errores puede ayudar a prevenir que se filtren detalles sensibles del sistema en los mensajes de error.
// Si no estableces el modo de error y ocurre un problema al ejecutar una consulta SQL, PDO podría devolver false, y tendrías que verificar manualmente si hubo un error. Sin embargo, al establecer el modo de error a excepción, puedes usar un bloque try-catch para capturar el error:

// Modos de error de PDO
    // PDO ofrece diferentes modos de error que puedes establecer:
    
    // PDO::ERRMODE_SILENT: (predeterminado) No genera errores. Debes verificar manualmente si la operación fue exitosa.
    
    // PDO::ERRMODE_WARNING: Genera advertencias, pero no lanza excepciones.
    
    // PDO::ERRMODE_EXCEPTION: Lanza excepciones, lo que permite un manejo más sencillo y efectivo de los errores.
    
    // establecer el modo de error de PDO a excepción es una buena práctica que te ayuda a gestionar los errores de manera más efectiva y a mantener un código más robusto y mantenible.