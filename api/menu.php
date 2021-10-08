<?php
    require_once('../helpers/database.php');
    require_once('../helpers/validator.php');
    require_once('../models/menu.php');
    require_once('../models/bebidas.php'); 
    require_once('../models/platillos.php');

    if (isset($_GET['action'])) {
        $model = new menu;
        $model1 = new platillos;
        $model2 = new bebidas;

        $result = array('status' => 0, 'error' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
        $menu[] = null;
        $menuCont = 0;
        switch ($_GET['action']) {
            case 'read':
                if($menu = $model->readAllMenuPublic()){
                    foreach($menu as $menu){
                        if($desc = $model->readAllMenuDesc($menu['id_menu'])){
                            foreach($desc as $desc){
                                if($desc['id_platillos'] != null){
                                    if($data = $model1->readOne($desc['id_platillos'])){
                                        foreach($data as $data){
                                            $menu[$menuCont] = '
<div class="col s6">
    <div class="card">
        <div class="card-image waves-effect waves-block waves-light">
            <img class="activator" src="../resources/img/platillos/'.$data['img'].'">
        </div>
        <div class="card-content">
            <span class="card-title activator grey-text text-darken-4">'.$data['nombre_platillo'].'</span>
            <p>'.$data['descripcion'].'</p>
            <p>$'.$data['precio'].'</p>
        </div>
    </div>
</div>
                                        ';
                                        }
                                        
                                        $menuCont++;
                                        $result['status'] = 1;
                                    }
                                }
                                else{
                                    if($data2 = $model2->readOne($desc['id_bebida'])){
                                        foreach( $data2 as $data2){
                                            $menu[$menuCont] = '
<div class="col s6">
    <div class="card">
        <div class="card-image waves-effect waves-block waves-light">
            <img class="activator" src="../resources/img/bebidas/'.$data2['img'].'">
        </div>
        <div class="card-content">
            <span class="card-title activator grey-text text-darken-4">'.$data2['nombre_bebida'].'</span>
            <p>'.$data2['descripcion'].'</p>
            <p>$'.$data2['precio'].'</p>
        </div>
    </div>  
</div>
                                            ';
                                        }    
                                        $menuCont++;
                                        $result['status'] = 1;
                                    }
                                }
                            }
                        }
                    }
                } else{
                    if (database::getException()) {
                        $result['exception'] = database::getException();
                    } else {
                        $result['exception'] = 'No hay registros';
                    }
                }
                $result['dataset'] = $menu;
                break;
        }

        header('content-type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        print(json_encode($result));
    } else {
        print(json_encode('Recurso no disponible'));
    }
?>