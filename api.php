<?php

require_once __DIR__ . '/classes/Provider.php';
require_once __DIR__ . '/classes/Response.php';
require_once __DIR__ . '/classes/ProviderRepository.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        //parses the query variables into the params array
        parse_str($_SERVER['QUERY_STRING'], $params);

        if(isset($params['id'])) {
            //Get by id
            $id=trim(strip_tags($params['id']));
            $provider = ProviderRepository::select($id);
            if($provider) {
                $response = array('result'=>'OK', 'data'=>$provider->toArray());
            } else {
                $response = array('result'=>'Error', 'data'=>'Provider not found');
            }
        } else {
            //Get all
            $result = ProviderRepository::getAll();
            $resultArray = [];
            foreach ($result as $object){
                $resultArray[] = $object->toArray();
            }
            $response = array('result'=>'OK', 'data'=>$resultArray);
        }

        echo Response::result(200, $response);

        break;
    case 'POST':
        $params = json_decode(file_get_contents("php://input"),true);
        if(!isset($params)) {
            $response=array('result'=>'Error','data'=>'Empty data');
            echo Response::result(400,$response);
        } else {
            $provider = new Provider();
            $provider->setName(trim(strip_tags($params['name'])));
            $provider->setEmail(trim(strip_tags($params['email'])));
            $provider->setCif(trim(strip_tags($params['cif'])));

            $errors = $provider->validate();
            if(empty($errors)){
                //Validation successful
                $id = ProviderRepository::insert($provider);
                $provider->setId($id);
                $response = array(
                    'result'=>'OK',
                    'data'=>$provider->toArray(),
                );
                echo Response::result(201, $response);
            } else {
                //Validation errors
                $response = array(
                    'result'=>'Error',
                    'data'=>'Validation erros',
                    'errors'=>$errors,
                );
                echo Response::result(400, $response);
            }
        }
        break;
    case 'PUT':
        parse_str($_SERVER['QUERY_STRING'], $query);
        $params = json_decode(file_get_contents("php://input"),true);
        if(!isset($params) || empty($query['id'])) {
            $response=array('result'=>'Error','data'=>'Empty data');
            echo Response::result(400,$response);
        } else {
            $provider = new Provider();
            $provider->setId(trim(strip_tags($query['id'])));
            $provider->setName(trim(strip_tags($params['name'] ?? '')));
            $provider->setEmail(trim(strip_tags($params['email'] ?? '')));
            $provider->setCif(trim(strip_tags($params['cif'] ?? '')));

            //Validation
            $errors = $provider->validate();
            if(empty($errors)){
                if(ProviderRepository::update($provider)){
                    $response = array(
                        'result'=>'OK',
                        'data'=>$provider->toArray(),
                    );
                    echo Response::result(201, $response);
                } else {
                    $response = array(
                        'result'=>'Error',
                        'data'=>'Provider not found',
                    );
                    echo Response::result(400, $response);
                }
            } else {
                //Validation errors
                $response = array(
                    'result'=>'Error',
                    'data'=>'Validation erros',
                    'errors'=>$errors,
                );
                echo Response::result(400, $response);
            }
        }
        break;
    case 'DELETE':
        parse_str($_SERVER['QUERY_STRING'], $query);
        if(!isset($query['id'])) {
            $response=array('result'=>'Error','data'=>'Empty data');
            echo Response::result(400, $response);
        } else {
            $id = trim(strip_tags($query['id']));
            $provider = ProviderRepository::select($id);
            if(!is_null($provider) && ProviderRepository::delete($provider)){
                $response = array(
                    'result'=>'OK',
                    'data'=>$provider->toArray(),
                );
                echo Response::result(200, $response);
            } else {
                $response = array(
                    'result'=>'Error',
                    'data'=>'No providers deleted',
                );
                echo Response::result(200, $response);
            }
        }
        break;
    default:
        echo Response::result(405, ['message' => 'Method not allowed']);
        break;
}