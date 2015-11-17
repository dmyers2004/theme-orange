<?php
theme::header_start('Packages');
Plugin_search_sort::field();
o::view_event($controller_path,'header.buttons');
theme::header_end();

echo '<small style="padding: 0 0 5px;display: block;">Actual version requirements are now managed by composer.</small>';

/* display errors */
if ($errors) {
	echo '<div class="alert alert-danger" role="alert">';
	echo '<b>We have a problem!</b><br>';
	echo $errors.'<br>';
	echo 'This needs to be fixed in order for packages to be dynamically loaded.';
	echo '</div>';
}

theme::table_start(['Name','Type'=>'text-center','Location'=>'text-center','Description','Migration'=>'text-center','Actions'=>'text-center'],['tbody_class'=>'searchable','class'=>'sortable'],$records);

//kd($records);

foreach ($records as $name=>$record) {
	/* Name */
	theme::table_start_tr();
	o::html($record['www_name']);

	/* type */
	theme::table_row('text-center');
	echo '<span class="label label-'.$type_map[$record['composer']['orange']['type']].'">'.$record['composer']['orange']['type'].'</span>';

	/* type */
	theme::table_row('text-center');
	if ($record['folder'] == 'framework') {
		echo '<span class="label label-info">packages</span>';
	} else {
		echo '<span class="label label-primary">vendor</span>';
	}

	/* Description */
	theme::table_row();
	o::e($record['composer']['description']);
	echo ' <a href="'.$controller_path.'/details/'.$record['url_name'].'"><i class="fa fa-info-circle"></i></a> ';

	/* Version */
	theme::table_row('text-center');
	/* show upgrade version and up arrow? */
	if ($record['composer']['orange']['version'] == $record['database']['migration_version']) {
		echo '<span class="label label-primary">'.$record['database']['migration_version'].'</span>';
	} else {
		echo '<span class="label label-info">'.$record['composer']['orange']['version'].'</span>&nbsp;';
		echo '<span class="label label-primary">'.$record['database']['migration_version'].'</span>';
	}

	/* Actions */
	theme::table_row('text-center');
	echo '<nobr>';

	/* show install */
	if ($record['buttons']['error']) {
		echo '<a href="'.$controller_path.'/details/'.$record['url_name'].'" class="btn btn-xs btn-primary"><i class="fa fa-info-circle"></i></a> ';
	}

	/* show install */
	if ($record['buttons']['deactivate']) {
		echo '<a href="'.$this->controller_path.'/deactivate/'.$record['url_name'].'" class="btn btn-xs btn-danger">Deactivate</a> ';
	}
	
	if ($record['buttons']['activate']) {
		echo '<a href="'.$this->controller_path.'/activate/'.$record['url_name'].'" class="btn btn-xs btn-default">Activate</a> ';
	}

	/* show upgrade */
	if ($record['buttons']['upgrade']) {
		echo '<a href="'.$this->controller_path.'/migrate/'.$record['url_name'].'" class="btn btn-xs btn-info">Migrate</a> ';
	}

	/* show uninstall */
	if ($record['buttons']['uninstall']) {
		echo '<a href="'.$this->controller_path.'/uninstall/'.$record['url_name'].'" data-name="'.$record['name'].'" class="btn btn-xs btn-warning js-uninstallable">Uninstall</a> ';
	}
	
	echo '</nobr>';
	theme::table_end_tr();
}

theme::table_end();

theme::return_to_top();