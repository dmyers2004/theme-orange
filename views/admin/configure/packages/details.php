<?php
theme::header_start('Package &ldquo;'.$record['composer']['name'].'&rdquo;');
theme::header_button_back();
theme::header_end();
?>
<table class="table">
	<tr>
		<td>Authors<?=(count($record['composer']['authors']) > 1) ? 's' : '' ?></td>
		<td>
		<?php
		foreach ($record['composer']['authors'] as $a) {
			foreach ($a as $key=>$val) {
				echo '<strong>'.$key.':</strong> '.$val.' ';
			}
			echo '<br>';
		}
		?>
		</td>
	</tr>

	<tr>
		<td>Keyword</small></td>
		<td><?=implode(', ',$record['composer']['keywords']) ?></td>
	</tr>

	<tr>
		<td>Homepage</small></td>
		<td><a target="homepage" href="<?=$record['composer']['homepage'] ?>"><?=$record['composer']['homepage'] ?></a></td>
	</tr>

	<tr>
		<td>License</small></td>
		<td><?=$record['composer']['license'] ?></td>
	</tr>

	<tr>
		<td>Location</small></td>
		<td><?=$record['folder'] ?></td>
	</tr>

	<tr>
		<td>Description</td>
		<td><?=$record['composer']['description'] ?></td>
	</tr>

<?php if (isset($record['composer']['orange'])) { ?>
	<tr>
		<td>Active</td>
		<td><?=($record['database']['is_active']) ? '<span class="label label-success">TRUE</span>' : '<span class="label label-danger">FALSE</span>' ?></td>
	</tr>

	<tr>
		<td colspan="2">
			<h3>Framework Details</h3>
		</td>
	</tr>

	<tr>
		<td>Type</td>
		<td><span class="label label-<?=$type_map[$record['type']]?>"><?=$record['composer']['orange']['type'] ?></span></td>
	</tr>

	<tr>
		<td>Command Line</td>
		<td>
			<?php
			if ($record['orange']['cli']) {
				foreach ($record['orange']['cli'] as $a=>$b) {
					echo '<code>'.$a.'</code> '.$b.'<br>';
				}
			}
			?>
		</td>
	</tr>

	<tr>
		<td>Managed as</td>
		<?php if ($record['folder'] == 'framework') { ?>
			<td><span class="label label-primary">Framework Package</span></td>
		<?php } else { ?>
			<td><span class="label label-info">Composer Package</span></td>
		<?php } ?>
	</tr>

	<tr>
		<td>Package Priority</td>
		<td><span class="badge"><?=$record['composer']['orange']['priority'] ?></span> <?=$record['human_priority'] ?> <small> - Common priorities: themes 10, default 50, libraries 70, plugins 80, Orange 90+</small></td>
	</tr>

	<tr>
		<td>Help</td>
		<td><a target="help" href="<?=$record['composer']['orange']['help'] ?>"><?=$record['composer']['orange']['homepage'] ?></a></td>
	</tr>

	<tr>
		<td>Note(s)</td>
		<td><?=$record['orange']['notes'] ?></td>
	</tr>

	<tr>
		<td colspan="2">
			<h3>Migrations / Version</h3>
		</td>
	</tr>

	<tr>
		<td>Installed Version</td>
		<td><?=$record['database']['migration_version'] ?></td>
	</tr>

	<tr>
		<td>Current Package Version</td>
		<td><?=$record['composer']['orange']['version'] ?></td>
	</tr>

	<tr>
		<td>Migration Status</td>
		<?php $map = [1=>'less than',2=>'equal to',3=>'greater than'] ?>
		<td>Installed version is <?=$map[$record['version_check']] ?> current package version.</td>
	</tr>

	<tr>
		<td>Has Migrations</td>
		<td><span class="badge"><?=(($record['migrations']['has_migrations']) ? 'true' : 'false') ?></span> <span class="badge"><?=count($record['migrations']['files']) ?></span></td>
	</tr>
	
	<tr>
		<td>Migration that need to be run</td>
		<td><?php
		foreach ($record['migrations']['files'] as $f)  {
			echo basename($f).'<br>';
		}
		?></td>
	</tr>

	<tr>
		<td colspan="2">
			<h3>Adds</h3>
		</td>
	</tr>
	
	<tr>
		<td>Table(s)</td>
		<td><?=str_replace(',','<br>',$record['orange']['tables']) ?></td>
	</tr>

	<tr>
		<td>Access</td>
		<td><?=str_replace(',','<br>',$record['orange']['access']) ?></td>
	</tr>

	<tr>
		<td>Menubar</td>
		<td><?=str_replace(',','<br>',$record['orange']['menubar']) ?></td>
	</tr>

	<tr>
		<td>Settings</td>
		<td><?=str_replace(',','<br>',$record['orange']['settings']) ?></td>
	</tr>
	
<?php } ?>
	<tr>
		<td colspan="2">
			<h3>Requirements</h3>
		</td>
	</tr>

	<tr>
		<td>Require<?=(count($record['require'],',') ? '' : 's') ?></td>
		<td>
			<?
			foreach ($record['composer']['require'] as $folder=>$version) {
				$r[] = $folder.' '.$version;
			}
			echo implode('<br>',$r);
			?>
		</td>
	</tr>

	<tr>
		<td>Missing Packages</td>
		<td class="text-danger">
			<strong>
			<?=implode('<br>',$record['package_not_available']) ?>
			</strong>
		</td>
	</tr>

	<tr>
		<td>Available but not active packages</td>
		<td class="text-danger">
			<strong>
			<?=implode('<br>',$record['package_not_active']) ?>
			</strong>
		</td>
	</tr>

	<tr>
		<td>Required By</td>
		<td class="text-danger">
			<strong>
			<?=implode('<br>',$record['is_required_by']) ?>
			</strong>
		</td>
	</tr>

</table>