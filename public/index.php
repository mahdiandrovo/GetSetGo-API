<?php

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../includes/DbOperations.php';

    $app = new \Slim\App([
        'settings' => [
            'displayErrorDetails'=>true
        ]
    ]);

    /*
        endpoint: createuser
        parameters: name, email, password
        method: POST
    */

    $app->post('/createuser', function(Request $request, Response $response){
        if(!haveEmptyParameters(array('name','email','password'), $response)){
            $request_data = $request->getParsedBody();

            $name = $request_data['name'];
            $email = $request_data['email'];
            $password = $request_data['password'];
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            $db = new DbOperations;
            $result = $db->createUser($name, $email, $hash_password);

            if($result == USER_CREATED){

                $message = array();
                $message['error'] = false;
                $message['message'] = 'User created successfully';

                $response->write(json_encode($message));

                return $response
                            ->withHeader('Content-type', 'application/json')
                            ->withStatus(201);

            }else if($result == USER_FAILURE){

                $message = array();
                $message['error'] = true;
                $message['message'] = 'Some error occured';

                $response->write(json_encode($message));

                return $response
                            ->withHeader('Content-type', 'application/json')
                            ->withStatus(422);

            }else if($result == USER_EXISTS){
                $message = array();
                $message['error'] = true;
                $message['message'] = 'User already exists';

                $response->write(json_encode($message));

                return $response
                            ->withHeader('Content-type', 'application/json')
                            ->withStatus(409);
            }
        }

        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(422);

    });


    $app->post('/userlogin', function(Request $request, Response $response){
        if(!haveEmptyParameters(array('email', 'password'), $response)){
            $request_data = $request->getParsedBody();

            $email = $request_data['email'];
            $password = $request_data['password'];

            $db = new DbOperations;

            $result = $db->userLogin($email, $password);

            if($result == USER_AUTHENTICATED){

                $user = $db->getUserByEmail($email);

                $response_data = array();
                
                $response_data['error'] = false;
                $response_data['message'] = 'Login successful';
                $response_data['user'] = $user;

                $response->write(json_encode($response_data));

                return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(200);

            }else if($result == USER_NOT_FOUND){

                $response_data = array();

                $response_data['error'] = true;
                $response_data['message'] = 'User do not exist';


                $response->write(json_encode($response_data));

                return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(404);

            }else if($result == USER_PASSWORD_DO_NOT_MATCH){

                $response_data = array();

                $response_data['error'] = true;
                $response_data['message'] = 'Invalid credential';


                $response->write(json_encode($response_data));

                return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(401);
            }

        }
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(422);

    });

    $app->get('/places', function(Request $request, Response $response){

        $db = new DbOperations;
        $places = $db->getAllPlaces();

        $response_data = array();

        $response_data['error'] = false;
        $response_data['message'] = 'Places found';
        $response_data['places'] = $places;

        $response->write(json_encode($response_data));
        return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    });


    function haveEmptyParameters($required_params, $response){
        $error = false;
        $error_params = '';
        $request_params = $_REQUEST;

        foreach($required_params as $param){
            if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
                $error = true;
                $error_params .= $param . ', ';
            }
        }

        if($error){
            $error_detail = array();
            $error_detail['error'] = true;
            $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
            $response->write(json_encode($error_detail));
        }
        return $error;
    }


    $app->run();

?>