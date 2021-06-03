<?php

namespace Model;
class ActiveRecord{
    //Base de Datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = "";

    //Errores 
    protected static $errores = [];
    

    public function crear(){
        //Sanitizar los datos
        $atributos = $this->sanitizarDatos();
        //Insertar en la base de Datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .=  join(',', array_keys($atributos)); 
        $query .= " ) VALUES( "; 
        $query .= join(", ", array_values($atributos)); 
        $query .= " ) ";

        $resultado = self::$db->query($query);
        //Mensaje de exito
        if($resultado){
            //Redireccionar
            header('Location:/Login?resultado=1');
        }
    }
    public function actualizar(){
        //Sanitizar los datos
        $atributos = $this->sanitizarDatos();

        $valores = [];
        foreach($atributos as $key => $value){
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ',$valores);
        $query .= "WHERE id= '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);
        if($resultado){
            //Redireccionar
            header('Location: /public/admin?resultado=2');
        } 
    }
    //Eliminar un registro
    public function eliminar(){
        $query= "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado){
            $this->borrarImagen();
            header('location: /public/admin?resultado=3');
        }
    }

    //Identificar y unir los atributos de la BD
    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna){
            if($columna === "id") continue;
                $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }
    public function sanitizarDatos(){   
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ){
            $sanitizado[$key] = self::$db->quote($value);
        }
        return $sanitizado;
    }


    //Validacion
    public static function getErrores(){
        return static::$errores;
    }

    public function validar(){
        static::$errores = [];
        return static::$errores;
    }


    //Subida de archivos
    public function setImagen($imagen){
        //Elimina la imagen previa

        if(!is_null($this->id)){
            //Comprobar si existe el archivo
            $this->borrarImagen();
        }

        //Asignar al atributo imagen el nombre de la imagen
        if($imagen){
            $this->imagen = $imagen;
        }
    }
    //Elimina el archivo
    public function borrarImagen(){
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo){
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }


    //Definir la conexion a la BD
    public static function setDB($database){
        self::$db = $database;
    }
    //Lista todas las propiedades 
    public static function all(){
        $query = "SELECT * FROM " . static::$tabla ; //Static es para referenciar a una clase hija
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    //Obtiene determinado numero de registros
    public static function get($cantidad){
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad; //Static es para referenciar a una clase hija
        $resultado = self::consultarSQL($query);
        return $resultado;
    }


    public static function consultarSQL($query){
        //Consultar la base de datos
        $resultado = self::$db->query($query);

        //Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()){
            $array[] = static::crearObjeto($registro);
        }
    
        //Liberar la memoria
        $resultado->free();

        //Retornar los resultados
        return $array;
    }
    protected static function crearObjeto($registro){
        $objeto = new static;

        foreach($registro as $key => $value){
            if(property_exists($objeto, $key)){
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }
    //Busca un registro por su ID
    public static function find($id){
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }
    // Sincroniza el objeto en memoria con los cambios realizados por el usuario.
    public function sincronizar( $args = [] ){
        foreach($args as $key => $value){
            if(property_exists($this, $key) && !is_null($value)){
                $this->$key = $value;
            }
        }
    }
}
?>