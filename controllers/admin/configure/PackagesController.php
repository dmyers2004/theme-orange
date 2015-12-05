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
		$this->page
			->data([
				'type_map'=>$this->type_map,
				'records'=>$this->package_manager->records(),
				'errors'=>$this->package_manager->messages,
			])
			->build($this->controller_path.'/index');
	}

	public function activateAction($package=null) {
		$this->_process($package,'activate','activated');
	}

	public function deactivateAction($package=null) {
		$this->_process($package,'deactivate','deactivated');
	}

	public function migrateAction($package=null) {
		$this->_process($package,'migrate','updated');
	}

	public function uninstallAction($package=null) {
		$this->_process($package,'uninstall','uninstalled');
	}

	public function detailsAction($package=null) {
		$package = hex2bin($package);

		$this->page
			->data([
				'type_map'=>$this->type_map,
				'record'=>$this->package_manager->record($package),
			])
			->build();
	}

	public function flushAction() {
		$this->package_manager->flush(true);

		$this->wallet->success('Updated',$this->controller_path);
	}
	
	protected function _process($name,$method,$action) {
		$key = hex2bin($name);
		$packagename = 'Package "'.$this->package_manager->packages[$key]['composer']['name'].'" ';
		
		/* the package mgr flushes all nessesary data */
		
		if ($this->package_manager->$method($key)) {
			$this->wallet->success($packagename.$action.'.',$this->controller_path);
		}

		$this->wallet->failed(ucfirst(str_replace('_',' ',$method)).' Error',$this->controller_path);
	}

} /* end class */