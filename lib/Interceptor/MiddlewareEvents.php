<?php 
namespace RCCFicoScorePLD\Client\Interceptor;

use \GuzzleHttp\Middleware as middleware;
use \GuzzleHttp\Psr7\Stream;
use \Psr\Http\Message\RequestInterface as streamRequest;
use \Psr\Http\Message\ResponseInterface as streamResponse;

use \RCCFicoScorePLD\Client\Interceptor\KeyHandler;
use \RCCFicoScorePLD\Client\Interceptor\MyLogger;
use \RCCFicoScorePLD\Client\Model\Errors;
use \RCCFicoScorePLD\Client\Model\Error;

class MiddlewareEvents
{
    
    function __construct(\RCCFicoScorePLD\Client\Interceptor\KeyHandler $signer)
    {
        $this->signer = $signer;
        $this->logger = new MyLogger('MiddlewareEvents');
    }

    function add_signature_header($header){
        return middleware::mapRequest(function (streamRequest $request) use ($header){
            try{
                if ($request->getMethod() == "POST"){
                    $this->logger->info("Begining signature method POST ...");
                    $stream = $request->getBody();
                    $payload = $stream->getContents();
                    $stream->rewind();
                    $signature = $this->signer->getSignatureFromPrivateKey($payload);
                }
                else if ($request->getMethod() == "GET"){
                    $this->logger->info("Begining signature from folioConsulta ...");
                    $parts = explode("/", $request->getUri());
                    $folioConsulta = $parts[5];
                    $signature = $this->signer->getSignatureFromPrivateKey($folioConsulta);
                }
                
            }catch (Exception $e) {
                $this->logger->error('Exception when calling add_signature_header: '.$e->getMessage().PHP_EOL);
                exit(1);
            }
            return $request->withHeader($header, $signature);
        });
    }

    function verify_signature_header($header){
        return middleware::mapResponse(function (streamResponse $response) use ($header){
            $verified = false;
            $super_response = null;
            try{
                $this->logger->info("Begining verification...");
                $status_code = $response->getStatusCode();
                $this->logger->info("Status code: ".$status_code);
                $stream = $response->getBody();
                $payload = $stream->getContents();
                $stream->rewind();
                $this->logger->info("Payload to verify: ".$payload);
                if($status_code == 200){
                    if(isset($response->getHeaders()[$header][0])){
                        $signature = $response->getHeaders()[$header][0];                   
                        $verified = $this->signer->getVerificationFromPublicKey($payload, $signature);

                        if(!$verified){
                            $this->logger->error("Could not verify the signature");
                            $this->logger->warning("The response could be modified");
                        }
                        $super_response = $response;
                    }
                    else{
                        $this->logger->error("Could not retrieve the signature");
                        $new_stream = build_error("500", "No se recibió la firma");
                        $super_response = $response->withBody($new_stream)->withStatus(403);
                    }
                }
                else{
                    $super_response = $response;
                }
            }
            catch (Exception $e) {
                $this->logger->error('Exception when calling verify_signature_header: '.$e->getMessage().PHP_EOL);
                $super_response = build_error("500", "Error inesperado");
                $super_response = $response->withBody($new_stream)->withStatus(500);
            }

            return $super_response;
        });
    }
}

function build_error($code, $message){
    $error = new \RCCFicoScorePLD\Client\Model\Error([
        "code" => $code,
        "message" => $message
    ]);
    $errors = new \RCCFicoScorePLD\Client\Model\Errores(["errors" => [$error]]);

    $resource = fopen('data://text/plain,' . $errors,'r');
    $new_reponse = new \GuzzleHttp\Psr7\Stream($resource);
    
    return $new_reponse;
}