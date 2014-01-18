<?php 

namespace PAPE\SOCL;

use Silex\ServiceProviderInterface;
use Silex\Application;
use PAPE\SOCL\ArrayNodeStore;
use PAPE\SOCL\MatrixGraphLinkStrategy;
use PAPE\SOCL\FriendshipGraph;


/**
*	@author GUEYE MAMADOU <papepapes@gmail.com>
*	This class is a service provider to work with the social graph inside of Silex
*/
class FriendshipGraphServiceProvider implements ServiceProviderInterface{

    public function register(Application $app){
        $app['socl'] = $app->share(function(){
            $store = new ArrayNodeStore(); // Any class that extends AbstractNodeStore can be placed here
            $lnStr = new MatrixGraphLinkStrategy(); //// Any class that extends AbstractGraphLinkStrategy can be placed here
            return new FriendshipGraph($store, $lnStr);
        });
    }

    public function boot(Application $app){

    }

}