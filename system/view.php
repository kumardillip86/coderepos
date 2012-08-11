<?php
class View {
	private $pageVars = array();
	private $template;
	public $session;
	public function __construct($template)
	{
		$this->template = APP_DIR .'views/'. $template .'.php';
		$this->session = new Session_helper;
	}

	public function set($var, $val)
	{
		$this->pageVars[$var] = $val;
	}

	public function render()
	{
		extract($this->pageVars);

		ob_start();
		require($this->template);
		echo ob_get_clean();
	}
}
?>