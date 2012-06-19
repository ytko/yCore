<?php defined ('_YEXEC')  or  die();

yFactory::linkBean();

class badgeClass extends yBeanClass {
	public function get() {
		$template = yFactory::getTemplate('badge');
		return $template->body();
	}
}

?>