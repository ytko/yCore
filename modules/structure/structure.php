<?php defined ('_YEXEC')  or  die();

yCore::load('yClass');

class structureClass extends yClass {
	public function show() {
		switch ($this->getUrl()) {
			case '':
				$content = yCore::catalogClass()->catalog();
				break;
			case '/page':
				$content = yCore::catalogClass()->page();
				break;
			case '/admin':
				$content = yCore::catalogClass()->catalogEdit();
				break;
			case '/admin/page':
				$content = yCore::catalogClass()->pageEdit();
				break;
			case '/admin/add':
				$content = yCore::catalogClass()->pageAdd();
				break;
			case '/export':
				$content = yCore::catalogClass()->export();
				break;
			case '/install':
				$content = yCore::catalogClass()->install();
				break;
			case '/uninstall':
				$content = yCore::catalogClass()->uninstall();
				break;
		}
				
		return yCore::templateClass()->show($content);
	}
}

?>