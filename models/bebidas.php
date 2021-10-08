<?php
    class Bebidas extends Validator{
        private $id;
        private $nombre;
        private $desc;
        private $precio;
        private $tipo;
        private $img;
        private $ruta = '../../resources/img/bebidas/';

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
            $sql = 'SELECT b.id_bebida,b.nombre_bebida,b.descripcion,b.precio, b.id_tipo_bebida,t.tipo_bebida,b.img 
                    FROM bebidas b ,tipo_bebidas t
                    WHERE b.id_tipo_bebida = t.id_tipo_bebida';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function readOne($value) {
            $sql = 'SELECT b.id_bebida,b.nombre_bebida,b.descripcion,b.precio, b.id_tipo_bebida,t.tipo_bebida,b.img
                    FROM bebidas b ,tipo_bebidas t
                    WHERE b.id_tipo_bebida = t.id_tipo_bebida
                    AND b.id_bebida = ?';
            $params = array($value);
            return dataBase::getRows($sql, $params);
        }

        public function createRow(){
            try{
                $sql = 'INSERT INTO bebidas(nombre_bebida,descripcion,precio,id_tipo_bebida,img)
                        VALUES (?,?,?,?,?)';
                $params = array($this->nombre, $this->desc, $this->precio,$this->tipo,$this->img);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function updateImg($id){
            try{
                $sql = 'UPDATE bebidas
                        SET nombre_bebida = ?,descripcion = ?,precio = ?,id_tipo_bebida = ?,img =?
                        WHERE id_bebida = ?';
                $params = array($this->nombre, $this->desc, $this->precio,$this->tipo,$this->img,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function update($id){
            try{
                $sql = 'UPDATE bebidas
                        SET nombre_bebida = ?,descripcion = ?,precio = ?,id_tipo_bebida = ?
                        WHERE id_bebida = ?';
                $params = array($this->nombre, $this->desc, $this->precio,$this->tipo,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function readTipos() {
            $sql = 'SELECT id_tipo_bebida, tipo_bebida 
                    FROM tipo_bebidas';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function readBebidasTipo()
        {
            $sql = 'SELECT nombre_bebida, descripcion, tipo_bebida, precio
                    FROM bebidas INNER JOIN tipo_bebidas USING(id_tipo_bebida) 
                    WHERE id_tipo_bebida = ?
                    ORDER BY tipo_bebida ASC';
            $params = array($this->tipo);
            return dataBase::getRows($sql, $params);
        }
        public function Bebidamascara()
        {
            // Se hace la consullta para llevar a cabo la acción
            $sql = 'SELECT nombre_bebida, precio from bebidas order by precio desc limit 10';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function bebidaBetween($max,$min){
            $sql = 'SELECT id_bebida,nombre_bebida,descripcion,tipo_bebida,precio FROM bebidas
            INNER JOIN tipo_bebidas
            ON bebidas.id_tipo_bebida = tipo_bebidas.id_tipo_bebida
            WHERE precio BETWEEN ? AND ?
            ORDER BY precio ASC;';

            $params = array($min,$max);

            return dataBase::getRows($sql, $params);
        }

        public function bebidaMenorA($x){
            $sql = 'SELECT id_bebida,nombre_bebida,descripcion,tipo_bebida,precio FROM bebidas
            INNER JOIN tipo_bebidas
            ON bebidas.id_tipo_bebida = tipo_bebidas.id_tipo_bebida
            WHERE precio <= ?
            ORDER BY precio ASC;';

            $params = array($x);

            return dataBase::getRows($sql, $params);
        }

        public function bebidaMayorA($x){
            $sql = 'SELECT id_bebida,nombre_bebida,descripcion,tipo_bebida,precio FROM bebidas
            INNER JOIN tipo_bebidas
            ON bebidas.id_tipo_bebida = tipo_bebidas.id_tipo_bebida
            WHERE precio >= ?
            ORDER BY precio ASC;';

            $params = array($x);

            return dataBase::getRows($sql, $params);
        }

        public function bebidasReport(){
            $sql = 'SELECT id_bebida,nombre_bebida,descripcion,tipo_bebida,precio FROM bebidas
            INNER JOIN tipo_bebidas
            ON bebidas.id_tipo_bebida = tipo_bebidas.id_tipo_bebida
            ORDER BY precio ASC;';

            $params = null;
            return dataBase::getRows($sql, $params);
        }
    }

?>