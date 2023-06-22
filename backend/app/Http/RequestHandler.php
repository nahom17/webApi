<?php

namespace App\Http;

class RequestHandler
{
   public const GET = 'GET';
   public const POST = 'POST';
   public const PUT = 'PUT';
   public const PATCH = 'PATCH';
   public const DELETE = 'DELETE';
   public const OPTIONS = 'OPTIONS';

   private array $routes;
   private string $request_type;
   private string $resource;
   private int $id = 0;

   public function __construct(array $routes)
   {
      $this->routes = $routes;

      $this->request_type = strtoupper($_SERVER['REQUEST_METHOD']);

      $this->parseURI();
   }

   private function parseURI(): void
   {
      if(isset($_GET['resource'])) {
         $this->resource = strtolower($_GET['resource']);

         if(isset($_GET['id'])) {
            $this->id = intval($_GET['id']);
         }
      } else {
         $this->resource = '/';
      }
   }

   private function getContent(): array
   {
      $content = [];

      if( ! empty($_POST) )
         $content = $_POST;
      else {
         $data = file_get_contents('php://input');
         if( ! empty($data) )
            $content = $data;
      }

      return $content;
   }

   private function checkRequest(): bool
   {
      if(isset($this->routes[$this->request_type])) {
         if(isset($this->routes[$this->request_type][$this->resource])) {
            if(
               isset($this->routes[$this->request_type][$this->resource][0]) &&
               isset($this->routes[$this->request_type][$this->resource][1])
            ) {
               $callable = new \ReflectionMethod(
                  $this->routes[$this->request_type][$this->resource][0],
                  $this->routes[$this->request_type][$this->resource][1]
               );
               if($callable->getNumberOfRequiredParameters() >= 1) {
                  foreach($callable->getParameters() as $parameter) {
                     if($parameter->getType() == 'int' && $this->id == 0)
                        return false;

                     if($parameter->getType() == 'array' && empty($this->getContent()))
                        return false;
                  }
               }
            }

            return true;
         }
      }

      return false;
   }

   public function handleRequest(): void
   {
      // Controle uitvoeren
      if( $this->request_type !== RequestHandler::OPTIONS )
         if( ! $this->checkRequest() ) {
            // Response terug sturen met fout code
            ApiResponse::sendError(ApiResponse::HTTP_STATUS_BAD_REQUEST, 'Bad Request', 'Some parameters are missing or route not available');
            die();
         }

      switch($this->request_type) {
         case RequestHandler::GET:
            $this->handleGetRequest();
            break;

         case RequestHandler::POST:
            $this->handlePostRequest();
            break;

         case RequestHandler::PUT:
            $this->handlePutRequest();
            break;

         case RequestHandler::PATCH:
            $this->handlePatchRequest();
            break;

         case RequestHandler::DELETE:
            $this->handleDeleteRequest();
            break;

         case RequestHandler::OPTIONS:
            $this->handleOptionsRequest();
            break;
      }
   }

   private function executeMethod(
      array $request_data = []
      ): array
   {
      $return_value = [];

      $classname = $this->routes[$this->request_type][$this->resource][0];
      $method_name =
      $this->routes[$this->request_type][$this->resource][1];

      if(class_exists($classname)) {
         $controller = new $classname;

         if(method_exists($controller, $method_name)) {
            if($this->id !== 0)
               if( ! empty($request_data) ) // PUT en PATCH
                  $return_value = $controller->$method_name($this->id, $request_data);
               else
                  $return_value = $controller->$method_name($this->id);
            else
               if( ! empty($request_data) ) // POST
                  $return_value = $controller->$method_name($request_data);
               else
                  $return_value = $controller->$method_name();
         }
      }
      return $return_value ?: [];
   }

   private function handleGetRequest(): void
   {
      ApiResponse::sendResponse($this->executeMethod());
   }

   private function handlePostRequest(): void
   {
      $request_data = $_POST;

      ApiResponse::sendResponse(
         $this->executeMethod($request_data),
         ApiResponse::HTTP_STATUS_CREATED,
         'Created successful'
      );
   }

   private function handlePutRequest(): void
   {

   }

   private function handlePatchRequest(): void
   {

   }

   private function handleDeleteRequest(): void
   {

   }

   private function handleOptionsRequest(): void
   {
      ApiResponse::sendCORSheaders();
   }
}