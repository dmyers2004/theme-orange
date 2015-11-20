<?php

class v100_theme_orange extends package_migration {
	public function up() {
		$this->cli_output('Add Symlink');

		$this->add_symlink('themes/orange');

		$this->cli_output('Clear access of any old internal orange_theme');
		ci()->o_access_model->delete_by(['internal'=>'projectorangebox/theme-orange']);

		$ids['manage menubar'] = $this->add_access(['name'=>'Manage Menubar','description'=>'Allow user to  edit the menubar','group'=>'Orange Theme']);
		$ids['manage settings'] = $this->add_access(['name'=>'Manage Settings','description'=>'Allow user to edit the settings','group'=>'Orange Theme']);
		$ids['manage packages'] = $this->add_access(['name'=>'Manage Packages','description'=>'Allow user to edit packages','group'=>'Orange Theme']);
		$ids['manage users'] = $this->add_access(['name'=>'Manage Users','description'=>'Allow user to edit users','group'=>'Orange Theme']);
		$ids['manage roles'] = $this->add_access(['name'=>'Manage Roles','description'=>'Allow user to edit roles','group'=>'Orange Theme']);
		$ids['manage access'] = $this->add_access(['name'=>'Manage Access','description'=>'Allow user to edit access','group'=>'Orange Theme']);

		$this->add_access(['name'=>'Manage Settings Advanced','description'=>'Allow advanced gui editing of settings','group'=>'Orange Theme']);
		$this->add_access(['name'=>'Manage Menubar Advanced','description'=>'Allow advanced gui editing of menubars','group'=>'Orange Theme']);

		$this->cli_output('Clear menubar table of any old internal orange_theme');
		ci()->o_menubar_model->delete_by(['internal'=>'projectorangebox/theme-orange']);

		$this->cli_output('Insert New');
		/* insert orange theme menus */
		$left_menu_id = $this->add_menu(['url'=>'/#','text'=>'Backend Left Menu','parent_id'=>0,'access_id'=>'orange::logged in users']);
		$right_menu_id = $this->add_menu(['url'=>'/#','text'=>'Backend Right Menu','parent_id'=>0,'access_id'=>'orange::logged in users']);

		$this->add_menu(['url'=>'/admin/dashboard','text'=>'Dashboard','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users','class'=>'menu-logo']);
		$this->add_menu(['url'=>'/#','text'=>'Content','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);
		$this->add_menu(['url'=>'/#','text'=>'Utilities','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);
		$configure_id = $this->add_menu(['url'=>'/#','text'=>'Configure','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);
		$user_id = $this->add_menu(['url'=>'/#','text'=>'Users','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);

		$this->add_menu(['url'=>'/#','text'=>'Packages','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);
		$this->add_menu(['url'=>'/#','text'=>'Reports','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);
		$this->add_menu(['url'=>'/#','text'=>'Help','parent_id'=>(int)$left_menu_id,'access_id'=>'orange::logged in users']);

		$this->add_menu(['url'=>'/admin/configure/setting','text'=>'Settings','parent_id'=>(int)$configure_id,'access_id'=>(int)$ids['manage settings']]);
		$this->add_menu(['url'=>'/admin/configure/menubar','text'=>'Menubar','parent_id'=>(int)$configure_id,'access_id'=>(int)$ids['manage menubar']]);
		$this->add_menu(['url'=>'/admin/configure/packages','text'=>'Packages','parent_id'=>(int)$configure_id,'access_id'=>(int)$ids['manage packages']]);

		$this->add_menu(['url'=>'/admin/users/user','text'=>'Users','parent_id'=>(int)$user_id,'access_id'=>(int)$ids['manage users']]);
		$this->add_menu(['url'=>'/admin/users/role','text'=>'Roles','parent_id'=>(int)$user_id,'access_id'=>(int)$ids['manage roles']]);
		$this->add_menu(['url'=>'/admin/users/access','text'=>'Access','parent_id'=>(int)$user_id,'access_id'=>(int)$ids['manage access']]);

		$user_menu_id = $this->add_menu(['url'=>'/#','text'=>'{user.username}','parent_id'=>(int)$right_menu_id,'access_id'=>'orange::logged in users']);

		$this->add_menu(['url'=>'/#','text'=>'{user.role_name}','parent_id'=>(int)$user_menu_id,'access_id'=>'orange::logged in users']);
		$this->add_menu(['url'=>'/#','text'=>'{user.email}','parent_id'=>(int)$user_menu_id,'access_id'=>'orange::logged in users']);
		$this->add_menu(['url'=>'/#','text'=>'{hr}','parent_id'=>(int)$user_menu_id,'access_id'=>'orange::logged in users']);

		$this->add_menu(['url'=>'/','text'=>'View Site','target'=>'_site','parent_id'=>(int)$user_menu_id,'access_id'=>'orange::logged in users']);
		$this->add_menu(['url'=>'/orange/logout','text'=>'Logout','parent_id'=>(int)$user_menu_id,'access_id'=>'orange::logged in users']);

		$this->add_menu(['url'=>'/orange','text'=>'Login','parent_id'=>0,'access_id'=>'orange::everyone']);

		$this->add_setting(['name'=>'Backend Left Menu','value'=>$left_menu_id,'group'=>'Orange Theme','show_as'=>3,'options'=>1]);
		$this->add_setting(['name'=>'Backend Right Menu','value'=>$right_menu_id,'group'=>'Orange Theme','show_as'=>3,'options'=>1]);

		return true;
	}

	public function down() {
		$this->cli_output('Remove Symlink');
		$this->remove_symlink('themes/orange');

		$this->cli_output('Remove menubar orange_theme');
		ci()->o_menubar_model->delete_by(['internal'=>'projectorangebox/theme-orange']);

		$this->cli_output('Remove access orange_theme');
		ci()->o_access_model->delete_by(['internal'=>'projectorangebox/theme-orange']);

		$this->cli_output('Remove settings orange_theme');
		ci()->o_setting_model->delete_by(['internal'=>'projectorangebox/theme-orange']);

		return true;
	}

} /* end example_person_v100 */