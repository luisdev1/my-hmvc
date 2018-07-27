<?php 

class homeController extends Controller {

	public function index($parametro = null) {
		echo "Home geral! - ";
		echo ($parametro) ? $parametro : null;
	}

}
