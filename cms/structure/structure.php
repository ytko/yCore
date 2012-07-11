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
				$content =
					yFactory::getBean('catalog')->cat();
				break;
			case '/page':
				$content =
					yFactory::getBean('catalog')->page();
				break;
			case '/edit':
				$content =
					yFactory::getBean('catalog')->edit();
				break;
			case '/export':
				$content =
					yFactory::getBean('catalog')->export();
				break;
		}
				
		return yFactory::getBean('template')
				->get($content);
	}
}

?>