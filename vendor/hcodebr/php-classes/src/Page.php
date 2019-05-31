<?php 

namespace Hcode;

use Rain\Tpl;
use \Hcode\Model\User;
use \Hcode\DB\Sql;

class Page{

	private $tpl;
	private $options = [];
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];

	public function __construct($opts = array(), $tpl_dir = "/views/"){

		$this->options = array_merge($this->defaults, $opts);

		$config = array(
			"tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir,
			"cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",
			"debug"         => false
		);

		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);

		if ($this->options["header"] === true) {
			$this->setTpl("header", array(
				"usuario"=>$this->loadById("desperson"),
				"dtregister"=>$this->loadById("dtregister")
			));
			
		}

	}

	public function loadById($field){

		$sql = new Sql();

		$currentId = $_SESSION[User::SESSION]['idperson'];

		$results = $sql->select("SELECT * FROM tb_persons WHERE idperson = :ID", array(
			":ID"=>$currentId			
		));

		$data = $results[0];

		return $data[$field];
	}

	public function setData($data = array()){

		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}

	}

	public function setTpl($name, $data = array(), $returnHtml = false){

		$this->setData($data);

		return $this->tpl->draw($name, $returnHtml);

	}

	public function __destruct(){

		if ($this->options["footer"] === true) {
			$this->tpl->draw("footer");
		}

	}

}

 ?>