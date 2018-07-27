<?php 

/**
 * Classe que gerencia as rotas do sistema
 * @author Luis Eduardo <luisdesenvolvedor@gmail.com>
 */

class Routes {

	private $method = 'index';
	private $controller = 'home';
	private $module = '';

	/**
	 * Retorna a instância da classe
	 * @return object
	 */
	public static function getInstance() {
		static $instance = null;
		if($instance === null) {
			$instance = new Routes();
		}
		return $instance;
	}

	private function __construct() {
		
	}
	
	public function setMethod($method) {
		$this->method = strtolower($method);
	}

	public function getMethod() {
		return $this->method;
	}

	public function setController($controller) {
		$this->controller = strtolower(str_replace('Controller','',$controller));
	}

	public function getController() {
		return $this->controller;
	}

	public function setModule($module) {
		$this->module = $module;
	}

	public function getModule() {
		return $this->module;
	}

	/**
	 * Verifica a existência da url acessada entre as rotas
	 * @param  string $url URL acessada
	 * @return string Nova URL
	 */
	public function checkRoutes($url) {
		global $routes;

		foreach($routes as $pt => $newurl) {
			
			$pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $pt);
			
			if(preg_match('#^('.$pattern.')*$#i', $url, $matches) === 1) {
				array_shift($matches);
				array_shift($matches);

				$itens = array();
				if(preg_match_all('(\{[a-z0-9]{1,}\})', $pt, $m)) {
					$itens = preg_replace('(\{|\})', '', $m[0]);
				}

				$arg = array();
				foreach($matches as $key => $match) {
					$arg[$itens[$key]] = $match;
				}

				foreach($arg as $argkey => $argvalue) {
					$newurl = str_replace(':'.$argkey, $argvalue, $newurl);
				}

				$url = $newurl;
				
				break;

			}

		}

		return $url;

	}


}