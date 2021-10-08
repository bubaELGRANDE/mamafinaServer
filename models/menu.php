<?php
    class menu extends Validator{
        private $id;
        private $idDesc;
        private $pla;
        private $desc;
        private $fecha;
        private $be;

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

        public function setPlatillo($value){

            if ($this->validateNaturalNumber($value)) {
                $this->pla = $value; 
                return true;
            } else {
                return false;
            }
        }

        public function setBebida($value){

            if ($this->validateNaturalNumber($value)) {
                $this->be = $value; 
                return true;
            } else {
                return false;
            }
        }

        public function setDesc($value){

            if ($value != null) {
                $this->desc = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setFecha($value){

            if ($this->validateDate($value)) {
                $this->fecha = $value;
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
 
        public function getPro()
        {
            return $this->pro;
        }


        public function readAllMenu() {
            $sql = 'SELECT * FROM menu';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function readAll() {
            $sql = 'SELECT * FROM menu_desc';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        

        public function readAllMenuPublic() {
            $sql = 'SELECT * FROM menu ORDER BY fecha DESC LIMIT 1;';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function readAllMenuDesc($id) {
            $sql = 'SELECT * FROM menu_desc WHERE id_menu = ?';
            $params = array($id);
            return dataBase::getRows($sql, $params);
        }

        public function readProducto($id) {
            $sql = 'SELECT nombre_platillo FROM platillos WHERE id_platillo = ?';
            $params = array($id);
            return dataBase::getRow($sql, $params);
        }

        public function readBebida($id) {
            $sql = 'SELECT nombre_bebida FROM bebidas WHERE id_bebida = ?';
            $params = array($id);
            return dataBase::getRow($sql, $params);
        }

        public function createRow(){
            try{
                $fecha = date("Y").'-'.date("m").'-'.date("d") ;

                $sql = 'INSERT INTO menu(fecha) VALUES (?)';
                $params = array($fecha);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function addRowB($id,$value){
            try{
                $sql = 'INSERT INTO menu_desc(id_bebida,id_menu) VALUES (?,?)';
                $params = array($value,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }
        public function addRowP($id,$value){
            try{
                $sql = 'INSERT INTO menu_desc(id_platillos,id_menu) VALUES (?,?)';
                $params = array($value,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function update($id){
            try{
                $sql = 'UPDATE menu
                SET descripcion = ?
                WHERE id_menu = ? ';

                $params = array($this->desc,$id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function readMenuplatillo()
        {
            $sql = 'SELECT pl.nombre_platillo , pl.descripcion, me.fecha 
            FROM platillos pl , menu_desc men,menu me where men.id_menu=me.id_menu  and men.id_platillos=pl.id_platillo and fecha=?';
            $params = array($this->fecha);
            return Database::getRows($sql, $params);
        }

        public function delete($id){
            try{
                $sql = 'DELETE FROM menu_desc WHERE id_menu_desc = ?';
                $params = array($id);
                return dataBase::executeRow($sql, $params);
            } catch (Exception $error){

                die("Error al update datos, acconunt/Models: ".$error ->getMessage()); 
            }
        }

        public function graficaTopPlatillos(){
            $sql = 'SELECT nombre_platillo, count(id_menu_desc) cantidad from menu_desc 
            INNER JOIN platillos
            ON menu_desc.id_platillos = platillos.id_platillo
            group by nombre_platillo order by nombre_platillo desc
            LIMIT 10';
            $params = null;
            return dataBase::getRows($sql, $params);
        }


        public function graficaTopBebidas(){
            $sql = 'SELECT nombre_bebida, count(id_menu_desc) cantidad from menu_desc 
            INNER JOIN bebidas
            ON menu_desc.id_bebida = bebidas.id_bebida
            group by nombre_bebida order by nombre_bebida desc
            LIMIT 10';
            $params = null;
            return dataBase::getRows($sql, $params);
        }
    }
?>