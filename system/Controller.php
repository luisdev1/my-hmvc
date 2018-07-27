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

}