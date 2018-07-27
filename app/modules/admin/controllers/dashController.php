<?php 

class dashController extends Controller {

	public function index() {
		$teste = new Teste();
		$this->loadView(null, array('var' => $teste->get()));
	}

}