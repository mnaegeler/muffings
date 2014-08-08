<?php

namespace Blog\Controller;

use View;
use BaseController;

class Artigos extends BaseController {

	public function getIndex() {
		return View::make('artigos');
	}

	public function getNew() {
		return 'Novo Artigo!';
	}

	public function getList() {
		return 'Listando Artigos!';
	}

}