<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

$app->options('/api/', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get All Customers
$app->get('/api/customers', function (Request $request, Response $response) {
    $sql = "SELECT * FROM customers";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    }
    catch(PDOException $e){
       echo '{"error": {"text": '. $e->getMessage().'}';
    }
});

// Get Single Customer
$app->get('/api/customer/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM customers WHERE id = $id";

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);
    }
    catch(PDOException $e){
       echo '{"error": {"text": '. $e->getMessage().'}';
    }
});

// Add Customer
$app->post('/api/customer/add', function (Request $request, Response $response) {
    $first_name = $request->getParam("first_name");
    $last_name = $request->getParam("last_name");
    $email = $request->getParam("email");
    $address = $request->getParam("address");
    $phone = $request->getParam("phone");
    $city = $request->getParam("city");
    $state = $request->getParam("state");

    $sql = "INSERT INTO customers (first_name,last_name,email,address,phone,city,state) VALUES (?,?,?,?,?,?,?)";
    
    $responseText = '{"notice": "Customer Added."}';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute([$first_name,$last_name,$email,$address,$phone,$city,$state]);
        // header({'Content-Type': 'application/json', 'Content-Type': 'text/plain'});        
        // echo '{"notice": {"text": "Customer Added."}';
        return $response->withHeader('Content-Type', 'application/json')->write($responseText);
    }
    catch(PDOException $e){
       echo '{"error": {"text": '. $e->getMessage().'}';
    }
});

// Update Customer
$app->put('/api/customer/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $first_name = $request->getParam("first_name");
    $last_name = $request->getParam("last_name");
    $email = $request->getParam("email");
    $address = $request->getParam("address");
    $phone = $request->getParam("phone");
    $city = $request->getParam("city");
    $state = $request->getParam("state");

    $sql = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, address = ?, phone = ?, city = ?, state = ? WHERE id = $id";
    

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute([$first_name,$last_name,$email,$address,$phone,$city,$state]);     
        header('Content-Type: application/json');    
        echo '{"notice": {"text": "Customer Updated."}';
    }
    catch(PDOException $e){
       echo '{"error": {"text": '. $e->getMessage().'}';
    }
});

// Delete Customer
$app->delete('/api/customer/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM customers WHERE id = ?";
    $responseText = '{"notice": {"text": "Customer '.$id.' Deleted Now."}';

    try{
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        // echo '{"notice": {"text": "Customer Deleted"}';
        return $response->withHeader('Content-Type', 'application/json')->write($responseText);
    }
    catch(PDOException $e){
       echo '{"error": {"text": '. $e->getMessage().'}';
    }
});