<?php

namespace App\Http;

class ApiResponse
{
   // NON CODE
   public const HTTP_NO_STATUS = 0;
   // SUCCESS CODES
   public const HTTP_STATUS_OK = 200;
   public const HTTP_STATUS_CREATED = 201;
   public const HTTP_STATUS_NO_CONTENT = 204;
   // NOT OFFICIAL SUCCESS CODES
   public const HTTP_STATUS_UPDATED = 210;
   public const HTTP_STATUS_DELETED = 211;

   // ERROR CODES
   public const HTTP_STATUS_BAD_REQUEST = 400;
   public const HTTP_STATUS_UNAUTHORIZED = 401;
   public const HTTP_STATUS_FORBIDDEN = 403;
   public const HTTP_STATUS_NOT_FOUND = 404;
   public const HTTP_STATUS_METHOD_NOT_ALLOWED = 405;

   // SERVER ERROR CODES
   public const HTTP_STATUS_SERVER_ERROR = 500;
   public const HTTP_STATUS_NOT_IMPLEMENTED = 501;
   public const HTTP_STATUS_SERVICE_NOT_AVAIL = 503;

   public static function sendCORSheaders()
   {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
      header('Access-Control-Allow-Headers: *');
      header('Access-Control-Max-Age: 1728000');
      header('Content-Length: 0');
      header('Content-Type: text/plain');
      http_response_code(200);
      exit();
   }

   public static function sendDefaultHeaders(): void
   {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
      header('Access-Control-Allow-Headers: *'); // token, Content-Type, Accept,
      header('Content-Type: application/json');
   }

   public static function sendStatusCode(
      int $code = self::HTTP_STATUS_OK,
      string $msg = 'Ok'
      ): void
   {
      header("HTTP/1.1 $code $msg");
   }

   public static function prepareResponse(
      array $data,
      int $code = self::HTTP_NO_STATUS,
      string $msg = ''
      ): void
   {
      $response = [
         'api_version' => '1.0',
         'api_name' => 'web api',
      ];

      if($code !== self::HTTP_NO_STATUS)
         $response['status'] = $code;

      if( ! empty($msg) )
         $response['status_message'] = $msg;

         $response['data'] = $data;

      echo json_encode($response);
   }

   public static function sendResponse(
      array $data,
      int $code = self::HTTP_STATUS_OK,
      string $msg = 'Ok'
      ): void
   {
      self::sendStatusCode($code, $msg);
      self::sendDefaultHeaders();
      http_response_code($code);
      echo self::prepareResponse($data, $code, $msg);
   }

   public static function sendError(int $code, string $msg, string $error_details = ''): void
   {
      self::sendStatusCode($code, $msg);
      self::sendDefaultHeaders();
      http_response_code($code);
      if( ! empty($error_details) )
         echo self::prepareResponse([
            'error_details' => $error_details
         ], $code, $msg);
   }
}