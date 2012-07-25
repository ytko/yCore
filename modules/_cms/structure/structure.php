<?php defined ('_YEXEC')  or  die();

yCore::includeBean();

class structureClass extends yBean {
	public $moduleName = 'structure';

	public function get() {
		switch ($_SERVER["REDIRECT_URL"]) {
			case '':
				$content =
					yCore::get('catalog')->cat();
				break;
			case '/page':
				$content =
					yCore::get('catalog')->page();
				break;
			case '/admin/':
				$content =
					yCore::get('catalog')->catEdit();
				break;
			case '/admin/page':
				$content =
					yCore::get('catalog')->pageEdit();
				break;
			case '/admin/add':
				$content =
					yCore::get('catalog')->pageAdd();
				break;
			case '/export':
				$content =
					yCore::get('catalog')->export();
				break;
			case '/install':
				$content =
					yCore::get('catalog')->install();
				break;
		}
				
		return yCore::get('template')
				->get($content);
	}
}

?>