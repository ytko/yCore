<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class structureClass extends yBeanClass {
	public $moduleName = 'index';

	public function get() {
		//echo $_SERVER["REDIRECT_URL"];
		switch ($_SERVER["REDIRECT_URL"]) {
			case '':
			case '/general':
				return
					yFactory::getModule('template')
						->setBean(
							/*yFactory::getBean()
								->setModuleName('general')
								->setModelName('general')
								->setTemplateName('general')*/
							yFactory::getModule('general')
						)
						->get();
			case '/objects':
				return
					yFactory::getModule('template')
						->setBean(yFactory::getModule('general/objects'))
						->get();
			case '/users':
				return
					yFactory::getModule('template')
						->setBean(yFactory::getModule('users'))
						->get();
		}
	}
}

?>