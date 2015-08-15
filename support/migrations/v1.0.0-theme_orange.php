<?php

class v100_theme_orange extends package_migration {

	public function up() {
		$this->add_symlink('themes/orange');
	}

	public function down() {
		$this->remove_symlink('themes/orange');
	}

} /* end example_person_v100 */
