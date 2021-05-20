<?php
namespace RCCFSPLD\MX\Client;

use \GuzzleHttp\Client;
use \GuzzleHttp\HandlerStack as handlerStack;

use Signer\Manager\Interceptor\MiddlewareEvents;
use Signer\Manager\Interceptor\KeyHandler;

use \RCCFSPLD\MX\Client\Api\RCCFSPLDApi as Instance;
use \RCCFSPLD\MX\Client\Configuration;

use \RCCFSPLD\MX\Client\Model\CatalogoEstados;
use \RCCFSPLD\MX\Client\Model\PersonaPeticion;
use \RCCFSPLD\MX\Client\Model\DomicilioPeticion;

class ApiTest extends \PHPUnit_Framework_TestCase
{

    public function setUp() {

        $this->x_api_key = "XXXXXXXXX";
        $this->username = "XXXXXXXX";
        $this->password = "XXXXXXX";
        $this->x_full_report = "false";
        $host = "the_url";
        $password = getenv('KEY_PASSWORD');

        $this->signer = new KeyHandler("/folder/keypair.p12", "/folder/cdc_certifcate.pem", $password);
        $events = new MiddlewareEvents($this->signer);
        $handler = HandlerStack::create();
        $handler->push($events->add_signature_header('x-signature'));
        $handler->push($events->verify_signature_header('x-signature'));
        
        $config = new Configuration();
        $config->setHost($host);
        $client = new Client(['handler' => $handler]);
        $this->apiInstance = new Instance($client, $config);
        
    }

    public function testGetReporte()
    {
        $estado = new CatalogoEstados();
        $request = new PersonaPeticion();
        $domicilio = new DomicilioPeticion();        

        $request->setPrimerNombre("XXXXXXXXX");
        $request->setApellidoPaterno("XXXXXXXXX");
        $request->setApellidoMaterno("XXXXXXXXX");
        $request->setFechaNacimiento("XXXX-XX-XX");
        $request->setRfc("XXXXXXXXX");
        $request->setCurp(null);
        $request->setNacionalidad("MX");

        $domicilio->setDireccion("XXXXXXXXX");
        $domicilio->setColoniaPoblacion("XXXXXXXXX");
        $domicilio->setDelegacionMunicipio("XXXXXXXXX");
        $domicilio->setCiudad("XXXXXXXXX");
        $domicilio->setEstado($estado::DF);
        $domicilio->setCp("XXXXX");
        $request->setDomicilio($domicilio);


        try {
            $result = $this->apiInstance->getReporte($this->x_api_key, $this->username, $this->password, $request, $this->x_full_report);
            $this->signer->close();
            print_r($result);
            $this->assertTrue($result->getFolioConsulta()!==null);
            return $result->getFolioConsulta();
        } catch (Exception $e) {
            echo 'Exception when calling RCC-FS-PLDApi->getReporte: ', $e->getMessage(), PHP_EOL;
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
                echo 'Exception when calling RCC-FS-PLDApi->testGetConsultas: ', $e->getMessage(), PHP_EOL;
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
                echo 'Exception when calling RCC-FS-PLDApi->testGetCreditos: ', $e->getMessage(), PHP_EOL;
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
                echo 'Exception when calling RCC-FS-PLDApi->testGetDomicilios: ', $e->getMessage(), PHP_EOL;
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
                echo 'Exception when calling RCC-FS-PLDApi->testGetEmpleos: ', $e->getMessage(), PHP_EOL;
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
                echo 'Exception when calling RCC-FS-PLDApi->testGetScores: ', $e->getMessage(), PHP_EOL;
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
                echo 'Exception when calling RCC-FS-PLDApi->testGetMensajes: ', $e->getMessage(), PHP_EOL;
            }
        } else {
            print_r("x_full_report inicializado en true");
        }         
    }    
}
