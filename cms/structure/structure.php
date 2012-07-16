<?php defined ('_YEXEC')  or  die();

yFactory::includeBean();

class structureClass extends yBeanClass {
	public $moduleName = 'structure';

	public function get() {
		switch ($_SERVER["REDIRECT_URL"]) {
			case '':
				$content =
					yFactory::getBean('catalog')->cat();
				break;
			case '/page':
				$content =
					yFactory::getBean('catalog')->page();
				break;
			case '/admin/':
				$content =
					yFactory::getBean('catalog')->catEdit();
				break;
			case '/admin/page':
				$content =
					yFactory::getBean('catalog')->pageEdit();
				break;
			case '/admin/add':
				$content =
					yFactory::getBean('catalog')->pageAdd();
				break;
			case '/export':
				$content =
					yFactory::getBean('catalog')->export();
				break;
			case '/install':
				$content =
					yFactory::getBean('catalog')->install();
				break;
		}
				
		return yFactory::getBean('template')
				->get($content);
	}
}

?>