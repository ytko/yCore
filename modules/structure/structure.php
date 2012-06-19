<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class structureClass extends yBeanClass {
	public $moduleName = 'structure';

	public function get() {
		/*$query = yFactory::getDb()
				->select()
				->from('`j7_yt_obj`')
				->where('`id` = 76');
				
		echo '<pre>';
		print_r(
			$query->get()
		);*/
		
		switch ($_SERVER["REDIRECT_URL"]) {
			case '':
			case '/general':
				return
					yFactory::getBean('template')
						->setContent(
							/*yFactory::getBean()
								->setModuleName('general')
								->setModelName('general')
								->setTemplateName('general')*/
							yFactory::getBean('general')
						)
						->get();
			case '/objects':
				return
					yFactory::getBean('template')
						->setContent(yFactory::getBean('general/objects'))
						->get();
			case '/users':
				return
					yFactory::getBean('template')
						->setContent(yFactory::getBean('users'))
						->get();
			case '/news':
				return
					yFactory::getBean('template')
						->setContent(yFactory::getBean('news'))
						->get();
		}
	}
}

?>