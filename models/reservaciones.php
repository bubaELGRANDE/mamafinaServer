<?php 

    class reservaciones extends Validator{

        //reservaciones
        private $id;
        private $descripcion;
        private $estado;
        private $tipo;

        public function setId($value){

            if ($this->validateNaturalNumber($value)) {
                $this->id = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setDesc($value){

            if ($value != NULL) {
                $this->descripcion = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setEstado($value){

            if ($this->validateNaturalNumber($value)) {
                $this->estado = $value;
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

        private $fecha;
        private $horarios;
        private $personas;

        public function setFecha($value){

            if ($this->validateDate($value)) {
                $this->fecha = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setHora($value){

            if ($value != NULL) {
                $this->horarios = $value;
                return true;
            } else {
                return false;
            }
        }

        public function setPersonas($value){

            if ($this->validateNaturalNumber($value)) {
                $this->personas = $value;
                return true;
            } else {
                return false;
            }
        }


        //datos cliente
        private $idCliente;
        private $nombre;
        private $apellido;
        private $telefono;
        private $email;


        public function setIdCliente($value){

            if ($this->validateNaturalNumber($value)) {
                $this->idCliente = $value;
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


        public function setApellidos($value){

            if ($this->validateAlphabetic($value, 1, 50)) {
                $this->apellido = $value;
                return true;
            } else {
                return false;
            }
        }
        public function setCorreo($value)
        {
            if ($this->validateEmail($value)) {
                $this->email = $value;
                return true;
            } else {
                return false;
            }
        }
        public function setTelefono($value)
        {
            if ($this->validateNaturalNumber($value)) {
                $this->telefono = $value;
                return true;
            } else {
                return false;
            }
        }
        //set reservaciones

        public function obtenerHorarios() {
            $sql = 'SELECT horarios,tipo FROM reservaciones WHERE dia = ?';
            $params = array($this->fecha);
            if($data = dataBase::getRows($sql, $params)){

                $similar = 0;

                foreach($data as $data){
                    if($data['horarios'] == $this->horarios && $data['tipo'] != 3){
                        $similar  = 1;
                    }
                    else{
                        $similar = 0;
                    }
                }
                if($similar == 0){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return true;
            }
        }

        public function addDatosPer(){
            $sql = "INSERT INTO datos_cliente(nombres,apellidos,telefono,email)
            VALUES (?,?,?,?)";
            $params = array($this->nombre,$this->apellido,$this->telefono,$this->email);
            return dataBase::executeRow($sql, $params);
        }
        
        public function obtenerIdDatosPer($name){
            $sql = 'SELECT id_datos_cliente FROM datos_cliente WHERE nombres = ?';
            $params = array($this->nombre);
            return dataBase::getRows($sql, $params);
        }

        public function createReservacio($value){
            $hoy = date("Y").'-'.date("m").'-'.date("d") ;
            $sql = 'INSERT INTO reservaciones(horario,dia,personas,tipo,estado,datos)
            VALUES(?,?,?,?,?,?)';
            $params = array($this->horarios,$this->fecha,$this->personas,$this->tipo,1,$value);

            return dataBase::executeRow($sql, $params);
        }

        public function readAll() {
            $sql = '	SELECT r.id_reservacion,r.descripcion,r.horario,r.dia,r.personas,t.tipo_reserv,e.estado_reserv,c.nombres,c.apellidos,c.telefono,c.email
            FROM reservaciones r, datos_cliente c, tipo_reserv t , estado_reserv e
            WHERE r.tipo = t.id_tipo_reserv
            AND	r.estado = e.id_estado_reserv
            AND r.datos = c.id_datos_cliente';
            $params = null;
            return dataBase::getRows($sql, $params);
        }

        public function update($value){
            $sql = 'UPDATE reservaciones
            SET descripcion = ?, estado = ?
            WHERE id_reservacion = ?';
            $params = array($this->descripcion,4,$value);
            return dataBase::getRows($sql, $params);
        }

        public function reservacionesMasRecientes()
        {
        $sql = 'SELECT dia, count(id_reservacion) cantidad from reservaciones  group by dia order by dia desc limit 10';
        $params = null;
        return Database::getRows($sql, $params);
        }

        public function readReservacionesocasion()
        {
            $sql = 'SELECT descripcion, dia, horario, personas
            FROM reservaciones 
            WHERE id_reservacion=?
            ORDER BY dia asc';
            $params = array($this->id);
            return Database::getRows($sql, $params);
        }

        public function reservacionPorTipo()
        {
            $sql = 'SELECT tipo_reserv, count(id_reservacion) cantidad from reservaciones 
            INNER JOIN tipo_reserv
            ON reservaciones.tipo = tipo_reserv.id_tipo_reserv
            group by tipo_reserv order by tipo_reserv desc';
            $params = null;
            return Database::getRows($sql, $params);
        }
    }

?>