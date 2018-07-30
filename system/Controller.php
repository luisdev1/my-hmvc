<?php 

/*
* Classe que fornece as funções básicas dos controladores do sitema
* @author Luis Eduardo <luisdesenvolvedor@gmail.com>
*/
class Controller {

	/**
	 * Faz o carregamento da visão (Parcial ou template)
	 * @param  string  $viewName Nome da view
	 * @param  array   $viewData Dados da view	 
	 * @param  boolean $layout   Usar ou não o template de layout (Geralmente header e footer)
	 */
	protected function loadView(string $viewName = null, array $viewData = array(), bool $layout = true) {		
		if($layout == true) {
			$viewData['viewData'] = $viewData;
			if(empty($viewName)) {
				$viewName = Routes::getInstance()->getMethod();
			}	
			$viewData['viewName'] = $viewName;		
			extract($viewData);
			if(!empty(Routes::getInstance()->getModule()) && file_exists('../app/modules/'.Routes::getInstance()->getModule().'/views/theme/index.php')) {
				require_once '../app/modules/'.Routes::getInstance()->getModule().'/views/theme/index.php';
			} else {
				require_once '../app/views/theme/index.php';				
			}
		} else {
			extract($viewData);
			if(!empty(Routes::getInstance()->getModule())) {
				$require = '../app/modules/'.Routes::getInstance()->getModule().'/views/';				
			} else {
				$require = '../app/views/';
			}
			if(!empty($viewName) && file_exists($require.$viewName.'.php')) {
				require_once $require.$viewName.'.php';
			} else {
				require_once $require.Routes::getInstance()->getController().'/'.Routes::getInstance()->getMethod().'.php';
			}			
		}
	}

	/**
	 * Retorna GET input
	 *
	 * @param String $key Nome do campo 
	 * @param mixed  $filter O(s) Filtro(s) para o campo
	 *
	 * @return mixed
	 */
	public function get($key = null, $filter = null) {
		if (!$key) {
			return $filter ? filter_input_array(INPUT_GET, $filter) : $_GET;
		}
		if (isset($_GET[$key])) {
			return $filter ? filter_input(INPUT_GET, $key, $filter) : $_GET[$key];
		}
		return null;
	}

	/**
	 * Retorna POST input
	 *
	 * @param String $key Nome do campo 
	 * @param mixed  $filter O(s) Filtro(s) para o campo
	 *
	 * @return mixed
	 */
	public function post($key = null, $filter = null) {
		if (!$key) {
			return $filter ? filter_input_array(INPUT_POST, $filter) : $_POST;
		}
		if (isset($_POST[$key])) {
			return $filter ? filter_input(INPUT_POST, $key, $filter) : $_POST[$key];
		}

		return null;
	}

	/**
	 * Retorna GET_POST input
	 *
	 * @param String $key Nome do campo 
	 * @param mixed  $filter O(s) Filtro(s) para o campo
	 *
	 * @return mixed
	 */
	public function get_post($key = null, $filter = null) {
		if (!isset($GLOBALS['_GET_POST'])) {
			$GLOBALS['_GET_POST'] = array_merge($_GET, $_POST);
		}
		if (!$key) {			
			return $filter ? filter_var_array($GLOBALS['_GET_POST'], $filter) : $GLOBALS['_GET_POST'];
		}
		if (isset($GLOBALS['_GET_POST'][$key])) {			
			return $filter ? filter_var($GLOBALS['_GET_POST'][$key], $filter) : $GLOBALS['_GET_POST'][$key];
		}
		return null;
	}

	/**
	 * Retorna COOKIE input
	 *
	 * @param String $key Nome do campo 
	 * @param mixed  $filter O(s) Filtro(s) para o campo
	 *
	 * @return mixed
	 */
	public function cookie($key = null, $filter = null) {
		if (!$key) {
			return $filter ? filter_input_array(INPUT_COOKIE, $filter) : $_COOKIE;
		}
		if (isset($_COOKIE[$key])) {
			return $filter ? filter_input(INPUT_COOKIE, $key, $filter) : $_COOKIE[$key];
		}
		return null;
	}

	/**
	 * Defini COOKIE input
	 *
	 * @param String $key Nome do campo
	 * @param mixed  $value Valor do campo
	 * @param int    $time Duração em segundos (Padrão 1 hora)
	 */
	public function set_cookie($key, $value, $time = 3600) {
		setcookie($key, $value, time() + $time, "/");
	}

	/**
	 * Deleta COOKIE input
	 *
	 * @param String $key Nome do campo
	 */
	public function delete_cookie($key) {
		setcookie($key, null, time() - 3600, "/");
		unset($_COOKIE[$key]);
	}

	/**
	 * Retorna uma varável de sessão
	 *
	 * @param String $key Nome do campo 
	 * @param mixed  $filter O(s) Filtro(s) para o campo
	 *
	 * @return mixed
	 */
	public function session($key = null, $filter = null) {
		if (!$key) {
			return $filter ? filter_var_array($_SESSION, $filter) : $_SESSION;
		}
		if (isset($_SESSION[$key])) {
			return $filter ? filter_var($_SESSION[$key], $filter) : $_SESSION[$key];
		}
		return null;
	}

	/**
	 * Defini uma variável de sessão
	 *
	 * @param String $key Nome do campo
	 * @param mixed  $value Valor do campo
	 */
	public function set_session($key, $value = '') {
		if (isset($key)) {
			$_SESSION[$key] = $value;
		}
	}	

}