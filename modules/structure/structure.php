<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class structureClass extends yBeanClass {
	public $moduleName = 'structure';

	public function get() {
		switch ($_SERVER["REDIRECT_URL"]) {
			case '':
			case '/general':
				return
					yFactory::getBean('/template')
						->setContent(
							/*yFactory::getBean()
								->setModuleName('general')
								->setModelName('general')
								->setTemplateName('general')*/
							yFactory::getBean('/general')
						)
						->get();
			case '/objects':
				return
					yFactory::getBean('/template')
						->setContent(yFactory::getBean('/general/objects'))
						->get();
			case '/users':
				return
					yFactory::getBean('/template')
						->setContent(yFactory::getBean('/users'))
						->get();
		}
	}
}

?>