<?php 

namespace PAPE\SOCL;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/**
*   @author GUEYE MAMADOU <papepapes@gmail.com>
*   This class is a used to serialize the API response in JSON or XML to the request client
*/
class GraphDataSerializerServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['gserializer'] = $app->share(function(){
        	$encoders = array(new XmlEncoder(), new JsonEncoder());
    		$normalizers = array(new GetSetMethodNormalizer());
    		$serializer = new Serializer($normalizers, $encoders);
            return $serializer;
        });
    }

    public function boot(Application $app){

    }

}