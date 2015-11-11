<?php

class v100_theme_orange extends package_migration {
	public $package = 'projectorangebox/theme-orange';

	public function up() {
		$this->add_symlink('themes/orange');
	
		return true;
	}

	public function down() {
		$this->remove_symlink('themes/orange');
	
		return true;
	}

} /* end example_person_v100 */
