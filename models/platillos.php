<?php
    class platillos extends Validator{
        private $id;
        private $nombre;
        private $desc;
        private $precio;
        private $tipo;
        private $img;
        private $ruta = '../../resources/img/platillos/';

        /*
        *   Métodos para validar y asignar valores de los atributos.
        */
        public function setId($value){

            if ($this->validateNaturalNumber($value)) {
                $this->id = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setNombre($value){

            if ($this->validateAlphabetic($value, 1, 50)) {
                $this->nombre = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setDesc($value){

            if ($value != NULL) {
                $this->desc = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setTipo($value){

            if ($this->validateNaturalNumber($value)) {
                $this->tipo = $value;
                return true;
            } else {
                return false;
            }
        }
        public function setPrecio($value){

            if ($this->validateMoney($value)) {
                $this->precio = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setImg($file){
            if ($file != null) {
                $name = $file['name'];
                $this->img = $name;
                return true;
            } else {
                return false;
            }
        }

        /*
        *   Métodos para obtener valores de los atributos.
        */
        public function getId()
        {
            return $this->id;
        }

        public function getNombres()
        {
            return $this->nombre;
        }

        public function getDesc()
        {
            return $this->desc;
        }

        public function getTipo()
        {
            return $this->tipo;
        }

        public function getImg()
        {
            return $this->img;
        }
        public function getRuta(){
            return $this->ruta;
        }


        public function readAll() {
            $sql = 'SELECT p.id_platillo,p.nombre_platillo,p.precio,p.descripcion,p.id_tipo_platillos,t.tipo_platillo,p.img
            FROM platillos p, tipo_platillos t
            WHERE p.id_tipo_platillos = t.id_tipo_plt';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function readAll2(){
            $sql ='SELECT id_tipo_plt,tipo_platillo
            FROM tipo_platillos';
        $params = null;
        return Database::getRows($sql, $params);
    }


        

        public function readOne($value) {
            $sql = 'SELECT p.id_platillo,p.nombre_platillo,p.precio,p.descripcion,p.id_tipo_platillos,t.tipo_platillo,p.img
            FROM platillos p, tipo_platillos t
            WHERE p.id_tipo_platillos = t.id_tipo_plt
            AND  p.id_platillo = ?';
            $params = array($value);
            return dataBase::getRows($sql, $params);
        }

        public function createRow(){
            try{
                $sql = 'INSERT INTO platillos(nombre_platillo,descripcion,precio,id_tipo_platillos,img)
                VALUES(?,?,?,?,?)';
                $params = array($this->nombre, $this->desc, $this->precio,$this->tipo,$this->img);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function updateImg($id){
            try{
                $sql = 'UPDATE platillos
                SET nombre_platillo = ?, descripcion = ? , precio = ? , id_tipo_platillos = ? , img = ?
                WHERE id_platillo = ?';
                $params = array($this->nombre, $this->desc, $this->precio,$this->tipo,$this->img,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function update($id){
            try{
                $sql = 'UPDATE platillos
                SET nombre_platillo = ?, descripcion = ? , precio = ? , id_tipo_platillos = ?
                WHERE id_platillo = ?';
                $params = array($this->nombre, $this->desc, $this->precio,$this->tipo,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }
        public function Platillomascaro()
    {
        // Se hace la consullta para llevar a cabo la acción
        $sql = 'SELECT nombre_platillo, precio from platillos order by precio desc limit 10';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readPlatillotipo()
    {
        $sql = 'SELECT nombre_platillo,descripcion,precio,tipo_platillo 
        FROM platillos pl,tipo_platillos tp where tp.id_tipo_plt=pl.id_tipo_platillos and tp.id_tipo_plt=? ';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
}

?>