<?php

/**
 * Classe responsável sobre o funciomaneto em HMVC do sistema
 * @author Luis Eduardo <luisdesenvolvedor@gmail.com>
 */
class Core {

	/**
	 * Faz a inicialização geral do sistema
	 */
	public function run() {

		$url = '/';
		if(!empty($_GET['url'])){
			$url .= $_GET['url'];
		}		

		$_routes = Routes::getInstance();

		$url = $_routes->checkRoutes($url);
		$params = array();

		if(!empty($url) && $url != '/') {
			$url = explode('/', $url);
			array_shift($url);

			$currentModule = '';

			if(!empty($url[0])) {
				if(file_exists('../app/modules/'.$url[0])) {
					$currentModule = $url[0];
					$currentController = 'homeController';
				} else {
					$currentController = $url[0].'Controller';
				}				
				array_shift($url);
			} else {
				$currentController = 'homeController';
			}

			if(!empty($url[0])) {
				if(!empty($currentModule)) {
					$currentController = $url[0].'Controller';
					$currentAction = 'index';
				} else {
					$currentAction = $url[0];
				}				
				array_shift($url);
			} else {
				$currentAction = 'index';
			}

			if(!empty($currentModule) && !empty($url[0])) {
				$currentAction = $url[0];
				array_shift($url);
			}

			if(count($url) > 0){
				$params = array_filter($url);
			}				

		} else {
			$currentModule = '';
			$currentController = 'homeController';
			$currentAction = 'index';
		}


		if(!empty($currentModule)) {
			$fetch_controller = '../app/modules/'.$currentModule.'/controllers/'.$currentController.'.php';
			if(file_exists($fetch_controller)) {
				require_once $fetch_controller;
			}
		} else {
			$fetch_controller = '../app/controllers/'.$currentController.'.php';
		}

		if(file_exists($fetch_controller) && method_exists($currentController, $currentAction)){			
			$reflection = new ReflectionMethod($currentController, $currentAction);
			if($reflection->getNumberOfParameters() < count($params)){
				$currentController = 'notfoundController';
				$currentAction = 'index';	
			}	
		} else {
			$currentController = 'notfoundController';
			$currentAction = 'index';				
		}		

		$_routes->setController($currentController);
		$_routes->setMethod($currentAction);
		$_routes->setModule($currentModule);

		$c = new $currentController();			
		call_user_func_array(array($c, $currentAction), $params);

	}

}