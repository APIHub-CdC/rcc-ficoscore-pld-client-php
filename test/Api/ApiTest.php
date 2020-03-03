<?php
namespace RCCFicoScorePLD\Client;

use \GuzzleHttp\Client;
use \GuzzleHttp\Event\Emitter;
use \GuzzleHttp\Middleware;
use \GuzzleHttp\HandlerStack as handlerStack;

use \RCCFicoScorePLD\Client\ApiException;
use \RCCFicoScorePLD\Client\Configuration;
use \RCCFicoScorePLD\Client\Model\Error;
use \RCCFicoScorePLD\Client\Interceptor\KeyHandler;
use \RCCFicoScorePLD\Client\Interceptor\MiddlewareEvents;

class RCCFicoScorePLDApiTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $password = getenv('KEY_PASSWORD');
        $this->signer = new \RCCFicoScorePLD\Client\Interceptor\KeyHandler(null, null, $password);

        $events = new \RCCFicoScorePLD\Client\Interceptor\MiddlewareEvents($this->signer);
        $handler = handlerStack::create();
        $handler->push($events->add_signature_header('x-signature'));   
        $handler->push($events->verify_signature_header('x-signature'));
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $config = new \RCCFicoScorePLD\Client\Configuration();
        $config->setHost('the_url');
        $this->apiInstance = new \RCCFicoScorePLD\Client\Api\RCCFicoScorePLDApi($client, $config);
        $this->x_api_key = "your_api_key";
        $this->username = "your_username";
        $this->password = "your_password";
    }

    public function testGetReporte()
    {
        $estado = new \RCCFicoScorePLD\Client\Model\CatalogoEstados();
        $request = new \RCCFicoScorePLD\Client\Model\PersonaPeticion();

        $request->setApellidoPaterno("PATERNO");
        $request->setApellidoMaterno("MATERNO");
        $request->setApellidoAdicional(null);
        $request->setPrimerNombre("PRIMERNOMBRE");
        $request->setSegundoNombre(null);
        $request->setFechaNacimiento("1952-05-13");
        $request->setRfc("PAMP010101");
        $request->setCurp(null);
        $request->setNacionalidad(null);
        $request->setResidencia(null);
        $request->setEstadoCivil(null);
        $request->setSexo(null);
        $request->setClaveElectorIfe(null);
        $request->setNumeroDependientes(null);
        $request->setFechaDefuncion(null);

        $domicilio->setDireccion("HIDALGO 32");
        $domicilio->setColoniaPoblacion(null);
        $domicilio->setDelegacionMunicipio("LA BARCA");
        $domicilio->setCiudad("BENITO JUAREZ");
        $domicilio->setEstado($estado::JAL);
        $domicilio->setCp("44190");
        $domicilio->setFechaResidencia(null);
        $domicilio->setNumeroTelefono(null);
        $domicilio->setTipoDomicilio(null);
        $domicilio->setTipoAsentamiento(null);
        $request->setDomicilio($domicilio);


        try {
            $result = $this->apiInstance->getReporte($this->x_api_key, $this->username, $this->password, $request, $this->x_full_report);
            $this->signer->close();
            print_r($result);
            $this->assertTrue($result->getFolioConsulta()!==null);
            return $result->getFolioConsulta();
        } catch (Exception $e) {
            echo 'Exception when calling RCCFicoScorePLDApi->getReporte: ', $e->getMessage(), PHP_EOL;
        }
    } 

   /**
     * @depends testGetReporte
     */    
    public function testGetConsultas($folioConsulta)
    {
        if($this->x_full_report == "false") {
            try {
                $result = $this->apiInstance->getConsultas($folioConsulta, $this->x_api_key, $this->username, $this->password);
                $this->signer->close();
                print_r($result);
                $this->assertTrue($result->getConsultas()!==null);
            } catch (Exception $e) {
                echo 'Exception when calling RCCFicoScorePLDApi->testGetConsultas: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }
    }


    /**
     * @depends testGetReporte
     */
    public function testGetCreditos($folioConsulta)
    {
        if($this->x_full_report == "false") {
            try {
                $result = $this->apiInstance->getCreditos($folioConsulta, $this->x_api_key, $this->username, $this->password);
                $this->signer->close();
                print_r($result);
                $this->assertTrue($result->getCreditos()!==null);
            } catch (Exception $e) {
                echo 'Exception when calling RCCFicoScorePLDApi->testGetCreditos: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }        
    }

    /**
     * @depends testGetReporte
     */
    public function testGetDomicilios($folioConsulta)
    {
        if($this->x_full_report == "false") {
            try {
                $result = $this->apiInstance->getDomicilios($folioConsulta, $this->x_api_key, $this->username, $this->password);
                $this->signer->close();
                print_r($result);
                $this->assertTrue($result->getDomicilios()!==null);
            } catch (Exception $e) {
                echo 'Exception when calling RCCFicoScorePLDApi->testGetDomicilios: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }          
    }

    /**
     * @depends testGetReporte
     */
    public function testGetEmpleos($folioConsulta)
    {
        if($this->x_full_report == "false") {
            try {
                $result = $this->apiInstance->getEmpleos($folioConsulta, $this->x_api_key, $this->username, $this->password);
                $this->signer->close();
                print_r($result);
                $this->assertTrue($result->getEmpleos()!==null);
            } catch (Exception $e) {
                echo 'Exception when calling RCCFicoScorePLDApi->testGetEmpleos: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }          
    }

    /**
     * @depends testGetReporte
     */
    public function testGetScores($folioConsulta)
    {
        if($this->x_full_report == "false") {
            try {
                $result = $this->apiInstance->getScores($folioConsulta, $this->x_api_key, $this->username, $this->password);
                $this->signer->close();
                print_r($result);
                $this->assertTrue($result->getScores()!==null);
            } catch (Exception $e) {
                echo 'Exception when calling RCCFicoScorePLDApi->testGetScores: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }         
    }
 
    /**
     * @depends testGetReporte
     */
    public function testGetMensajes($folioConsulta)
    {
        if($this->x_full_report == "false") {
            try {
                $result = $this->apiInstance->getMensajes($folioConsulta, $this->x_api_key, $this->username, $this->password);
                $this->signer->close();
                print_r($result);
                $this->assertTrue($result->getMensajes()!==null);
            } catch (Exception $e) {
                echo 'Exception when calling RCCFicoScorePLDApi->testGetMensajes: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }         
    }    
}

