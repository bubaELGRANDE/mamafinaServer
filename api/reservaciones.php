<?php
    require_once('../helpers/database.php');
    require_once('../helpers/validator.php');
    require_once('../models/reservaciones.php');


    
    if (isset($_GET['action'])) {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
        session_start(); 
        // Se instancia la clase correspondi ente. 
        $model = new reservaciones;
        // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
        $result = array('status' => 0, 'error' => 0, 'message' => null, 'exception' => null);
        // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
            // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
            switch ($_GET['action']) {
                case 'reservacionesMasRecientes':
                    if ($result['dataset'] = $model->reservacionesMasRecientes()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'No hay registros';
                        }
                    }
                    break;
                case 'graficaTipo':
                    if ($result['dataset'] = $model->reservacionPorTipo()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'No hay registros';
                        }
                    }
                    break;
                case 'create':

                    $_POST = $model->validateForm($_POST);
                    //Datos de reserva
                    if($model->setFecha($_POST['infechaini'])){
                        if($model->setHora($_POST['horarios'])){
                            if($model->setPersonas($_POST['personas'])){
                                //Datos del Usuario
                                if($model->setNombre(   $_POST['nombres'])){
                                    if($model->setApellidos($_POST['apellidos'])){
                                        if($model->setCorreo($_POST['correo'])){
                                            if($model->setTelefono($_POST['telefono'])){
                                                //Tipo de Reservacion
                                                if($model->setTipo($_POST['tipo'])){
                                                    //Validaciones
                                                    if($model->obtenerHorarios()){
                                                        if($model->addDatosPer()){
                                                            if($data = $model->obtenerIdDatosPer($_POST['nombres'])){
                                                                foreach($data as $data){
                                                                    if($model->createReservacio($data['id_datos_cliente'])){
                                                                        $result['status'] = 1;
                                                                        $result['message'] = 'Su reservación a sido realizada con exito, pronto nos comunicaremos para seguir con el proceso.';
                                                                    }
                                                                    else{
                                                                        $result['exception'] = dataBase::getException();
                                                                    }
                                                                }   
                                                            }
                                                            else{
                                                                $result['exception'] = 'Erro el ingresar datos personales';
                                                            }
                                                        }
                                                        else{
                                                            $result['exception'] = dataBase::getException();
                                                        }
                                                    }
                                                    else{
                                                        $result['exception'] = 'En el dia seleccionado no hay horas disponibles, Porfavor seleccione otra fecha.';   
                                                    }
                                                }
                                                else{
                                                    $result['exception'] = 'Erro al obtener el tipo de reservación.';
                                                }
                                            }
                                            else{
                                                $result['exception'] = 'El telefono ingresado es incorrecta';
                                            }
                                        }
                                        else{
                                            $result['exception'] = 'El Correo  ingresado es incorrecta';
                                        }
                                    }
                                    else{
                                        $result['exception'] = 'El Apellidos  ingresado es incorrecta';
                                    }
                                }
                                else{
                                    $result['exception'] = 'El nombre  ingresado es incorrecta';
                                }
                            }
                            else{
                                $result['exception'] = 'El numero de personas ingresada es incorrecta';
                            }
                        }
                        else{
                            $result['exception'] = 'La Hora ingresada es incorrecta';
                        }
                    }
                    else{
                        $result['exception'] = 'La fecha ingresada es incorrecta';
                    }
                break;
                case 'update':
                    $_POST = $model->validateForm($_POST);
                    if($model->setDesc($_POST['desc'])){
                        if($model->update($_POST['id'])){
                            $result['status'] = 1;
                            $result['message'] = 'Reservación actualizada';
                        }
                        else{
                            $result['exception'] = dataBase::getException();
                        }
                    }
                    else{
                        $result['exception'] = 'Error al mandar descripción';
                    }
                    break;
                default:
                    $result['exception'] = 'Acción no disponible dentro de la sesión';
            }
        
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Recurso no disponible'));
    }
    
?>