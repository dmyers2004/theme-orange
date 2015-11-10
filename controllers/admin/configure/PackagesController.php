<?php

/**
* Orange Framework Extension
*
* This content is released under the MIT License (MIT)
*
* @package	CodeIgniter / Orange
* @author	Don Myers
* @license	http://opensource.org/licenses/MIT	MIT License
* @link	https://github.com/dmyers2004
*/

class packagesController extends APP_AdminController {
	public $controller = 'packages';
	public $controller_path = '/admin/configure/packages';
	public $controller_title = 'Package';
	public $controller_titles = 'Packages';
	public $libraries = 'package_manager';
	public $has_access = 'Orange::Manage Packages';
	public $type_map = [''=>'default','?'=>'danger','core_required'=>'warning','core'=>'warning','library'=>'success','libraries'=>'success','theme'=>'danger','package'=>'primary','plugin'=>'info','assets'=>'danger'];

	public function indexAction() {
		$this->load->library('plugin_search_sort');

		$this->page
			->data([
				'type_map'=>$this->type_map,
				'records'=>$this->package_manager->records(),
				'errors'=>$this->package_manager->messages,
			])
			->build($this->controller_path.'/index');
	}

	public function installAction($package=null) {
		$this->_process($package,'install');

		redirect($this->controller_path);
	}

	public function upgradeAction($package=null) {
		$this->_process($package,'upgrade');

		redirect($this->controller_path);
	}

	public function uninstallAction($package=null) {
		$this->_process($package,'uninstall');

		redirect($this->controller_path);
	}

	public function detailsAction($package) {
		$package = hex2bin($package);

		$this->page
			->data([
				'type_map'=>$this->type_map,
				'record'=>$this->package_manager->record($package),
			])
			->build();
	}

	protected function _process($name,$method) {
		$map = ['install'=>'installed','uninstall'=>'uninstalled','delete'=>'deleted','upgrade'=>'upgraded'];

		$package = hex2bin($name);

		/* dump all caches */
		$this->cache->clean();

		/* also refresh the user data */
		$this->auth->refresh_userdata();

		if ($this->package_manager->$method($package) !== true) {
			$this->wallet->failed(ucfirst($method).' Error');

			return false;
		}

		$this->wallet->success('Package "'.$package.'" '.$map[$method].'.');

		return true;
	}

} /* end class */