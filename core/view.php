<?php defined ('_YEXEC')  or  die();

@require_once 'base.php';

class yViewClass extends yBaseClass{
	public
		$template,	// Объект шаблона
		$body;		// Контент
	
	static
		$head;		// Код для <head>
	
	public $view, $defaultview = 'default';

	// Устанавливает шаблон для обработки
	function setTemplate($template) {
		$this->template = $template;
		return $this;
	}

	// Обрабатывает шаблон и возвращает результат
	function get($template = NULL) {
		if ($template) $this->setTemplate($template);
		
		// 
		ob_start();
		$this->template->body();
		$this->body.= ob_get_contents();
		ob_end_clean();
	
		// Проверяет определен ли метод ->head() объекта шаблона
		// и дописывает его результат к $this->head
		if(method_exists($this->template, 'head')) {
			ob_start();
			$this->template->head();
			$this->head.= ob_get_contents();
			ob_end_clean();
		}
	
		return $this;
	}
	
	function getStyle() { //to del
		return $this->style;
	}

	function getScript() { //to del
		return $this->script;
	}

	function __construct() {
		$view->body = '';
		$view->head = '';
		$view->breadcrumbs = array();
	}
	
	function getPage($template) { //to del		
		$this->get($template);
	}
	
	/*function getView($template) { //to del
		if (isset($_->items))
			$this->quoteRecursive($_->items);
		if (isset($_->item))
			$this->quoteRecursive($_->item);

		return $this->getPage($template);
	}*/
	
	protected function quoteRecursive(&$data) {
		// Рекурсивное преобразование всех строк в html-безопасные в объекте/массиве
		if (is_array($data)||is_object($data)) {
			foreach ($data as $key => &$value)
				if (is_string($value))
				$value = htmlspecialchars($value, ENT_QUOTES);
			else
				$this->quoteRecursive($value);
		}
		elseif (is_string($data)) {
			$data = htmlspecialchars($data, ENT_QUOTES);
		}
	}
}

?>