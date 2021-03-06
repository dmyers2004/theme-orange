<?php

class v100_theme_orange extends package_migration {
	public function up() {
		$this->cli_output('Add Symlink');

		$this->add_symlink('themes/orange');

		$group = 'Orange Theme';
		$internal = 'projectorangebox/theme-orange';
		$color = 'E36B2A';
		$logged_in = $logged_in;

		$this->cli_output('Clear access of any old internal orange_theme');
		ci()->o_access_model->delete_by(['internal'=>$internal]);

		$ids['manage menubar'] = $this->add_access(['name'=>'Manage Menubar','description'=>'Allow user to edit the menubar','group'=>$group]);
		$ids['manage settings'] = $this->add_access(['name'=>'Manage Settings','description'=>'Allow user to edit the settings','group'=>$group]);
		$ids['manage packages'] = $this->add_access(['name'=>'Manage Packages','description'=>'Allow user to edit packages','group'=>$group]);
		$ids['manage users'] = $this->add_access(['name'=>'Manage Users','description'=>'Allow user to edit users','group'=>$group]);
		$ids['manage roles'] = $this->add_access(['name'=>'Manage Roles','description'=>'Allow user to edit roles','group'=>$group]);
		$ids['manage access'] = $this->add_access(['name'=>'Manage Access','description'=>'Allow user to edit access','group'=>$group]);

		$this->add_access(['name'=>'Manage Settings Advanced','description'=>'Allow advanced gui editing of settings','group'=>$group]);
		$this->add_access(['name'=>'Manage Menubar Advanced','description'=>'Allow advanced gui editing of menubars','group'=>$group]);

		$this->cli_output('Clear menubar table of any old internal orange_theme');
		ci()->o_menubar_model->delete_by(['internal'=>$internal]);

		$this->cli_output('Insert New');

		/* insert orange theme menus */
		$left_menu_id = $this->add_menu(['url'=>'/#','text'=>'Backend Left Menu','parent_id'=>0,'access_id'=>$logged_in]);
		$right_menu_id = $this->add_menu(['url'=>'/#','text'=>'Backend Right Menu','parent_id'=>0,'access_id'=>$logged_in]);

		$this->add_menu(['color'=>'E36B2A','icon'=>'bars','url'=>'/admin/dashboard','text'=>'Dashboard','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in,'class'=>'menu-logo']);
		$this->add_menu(['url'=>'/#','text'=>'Content','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);
		$this->add_menu(['url'=>'/#','text'=>'Utilities','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);
		$configure_id = $this->add_menu(['url'=>'/#','text'=>'Configure','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);
		$user_id = $this->add_menu(['url'=>'/#','text'=>'Users','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);

		$this->add_menu(['url'=>'/#','text'=>'Packages','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);
		$this->add_menu(['url'=>'/#','text'=>'Reports','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);
		$this->add_menu(['url'=>'/#','text'=>'Help','parent_id'=>(int)$left_menu_id,'access_id'=>$logged_in]);

		$this->add_menu(['color'=>$color,'icon'=>'toggle-on','url'=>'/admin/configure/setting','text'=>'Settings','parent_id'=>(int)$configure_id,'access_id'=>(int)$ids['manage settings']]);
		$this->add_menu(['color'=>$color,'icon'=>'compass','url'=>'/admin/configure/menubar','text'=>'Menubar','parent_id'=>(int)$configure_id,'access_id'=>(int)$ids['manage menubar']]);
		$this->add_menu(['color'=>$color,'icon'=>'archive','url'=>'/admin/configure/packages','text'=>'Packages','parent_id'=>(int)$configure_id,'access_id'=>(int)$ids['manage packages']]);

		$this->add_menu(['color'=>$color,'icon'=>'user','url'=>'/admin/users/user','text'=>'Users','parent_id'=>(int)$user_id,'access_id'=>(int)$ids['manage users']]);
		$this->add_menu(['color'=>$color,'icon'=>'users','url'=>'/admin/users/role','text'=>'Roles','parent_id'=>(int)$user_id,'access_id'=>(int)$ids['manage roles']]);
		$this->add_menu(['color'=>$color,'icon'=>'unlock-alt','url'=>'/admin/users/access','text'=>'Access','parent_id'=>(int)$user_id,'access_id'=>(int)$ids['manage access']]);

		$user_menu_id = $this->add_menu(['url'=>'/#','text'=>'{user.username}','parent_id'=>(int)$right_menu_id,'access_id'=>$logged_in]);

		$this->add_menu(['url'=>'/#','text'=>'{user.role_name}','parent_id'=>(int)$user_menu_id,'access_id'=>$logged_in]);
		$this->add_menu(['url'=>'/#','text'=>'{user.email}','parent_id'=>(int)$user_menu_id,'access_id'=>$logged_in]);
		$this->add_menu(['url'=>'/#','text'=>'{hr}','parent_id'=>(int)$user_menu_id,'access_id'=>$logged_in]);

		$this->add_menu(['color'=>$color,'icon'=>'globe','url'=>'/','text'=>'View Site','target'=>'_site','parent_id'=>(int)$user_menu_id,'access_id'=>$logged_in]);
		$this->add_menu(['color'=>$color,'icon'=>'sign-out','url'=>'/orange/logout','text'=>'Logout','parent_id'=>(int)$user_menu_id,'access_id'=>$logged_in]);

		$this->add_menu(['color'=>$color,'icon'=>'sign-in','url'=>'/orange','text'=>'Login','parent_id'=>0,'access_id'=>'orange::everyone']);

		$this->add_setting(['name'=>'Backend Left Menu','value'=>$left_menu_id,'group'=>$group,'show_as'=>3,'options'=>1]);
		$this->add_setting(['name'=>'Backend Right Menu','value'=>$right_menu_id,'group'=>$group,'show_as'=>3,'options'=>1]);

		$this->add_setting(['name'=>'Show Menubar Icon','value'=>'true','group'=>$group,'show_as'=>1]);
		$this->add_setting(['name'=>'Show Menubar Color','value'=>'true','group'=>$group,'show_as'=>1]);
		$this->add_setting(['name'=>'Hidden Menubar On','value'=>'','group'=>$group,'help'=>'ie. dashboard/*,/foo/bar/*,/cookies/monster']);

		$this->add_setting(['name'=>'admin theme default template','value'=>'_templates/orange_default','group'=>'page','internal'=>'projectorangebox/orange']);
		$this->add_setting(['name'=>'admin theme folder','value'=>'projectorangebox/theme-orange','group'=>'page','internal'=>'projectorangebox/orange']);

		return true;
	}

	public function down() {
		$this->cli_output('Remove Symlink');
		$this->remove_symlink('themes/orange');

		$internal = 'projectorangebox/theme-orange';

		$this->cli_output('Remove menubar orange_theme');
		ci()->o_menubar_model->delete_by(['internal'=>$internal]);

		$this->cli_output('Remove access orange_theme');
		ci()->o_access_model->delete_by(['internal'=>$internal]);

		$this->cli_output('Remove settings orange_theme');
		ci()->o_setting_model->delete_by(['internal'=>$internal]);

		return true;
	}

} /* end example_person_v100 */