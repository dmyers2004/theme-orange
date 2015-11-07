<?php

class bootstrap_menu {
	static protected $all_menus;
	static protected $menus;
	
	/*
	Hidden On: /dashboard/*,/foo/bar/*,/cookies/monster
	*/
	static public function nav($left_menu=-1,$right_menu=-1) {
		$hidden_on = setting('menubar','Hidden On','*SHOWONALL*');
		$left_root_menu = setting('menubar','Left Root Menu',$left_menu);
		$right_root_menu = setting('menubar','Right Root Menu',$right_menu);

		/* first we get all menus this user has access to */
		self::$all_menus = ci()->o_menubar_model->get_menus(array_keys(ci()->user->access));

		/* then we build the menus array */
		self::$menus = ci()->o_menubar_model->get_menus_ordered_by_parent_ids(self::$all_menus);
		
		/* now we build the left and right menus */
		$nav = '';

		if (!empty($hidden_on)) {
			if (!preg_match('@,'.str_replace('*','[^,]*',$hidden_on).'@',',/'.ci()->uri->uri_string(),$matches)) {
				$nav .= '<nav class="navbar navbar-'.setting('menubar','Inverse Menubar','inverse').' navbar-fixed-top">';
				$nav .= '<div class="container">';
				$nav .= '<div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">';
				$nav .= '<span class="sr-only">Toggle</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>';
				$nav .= '</div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav">';
				$nav .= self::build($left_root_menu);
				$nav .= '</ul><ul class="nav navbar-nav navbar-right">';
				$nav .= self::build($right_root_menu);
				$nav .= '</ul></div></div></nav>';
			}
		}

		return $nav;
	}
	
	static protected function build($start_at,$filter_empty=true) {
		$new_menus = [];

		if (is_array(self::$menus)) {
			foreach (self::$menus[$start_at] as $key => $item) {
				$new_menus[$key]['class'] = $item->class;
				$new_menus[$key]['href']  = rtrim($item->url, '/#');
				$new_menus[$key]['text']  = $item->text;
				$new_menus[$key]['color'] = $item->color;
				$new_menus[$key]['icon'] = $item->icon;
				$new_menus[$key]['target'] = $item->target;

				if (isset(self::$menus[$key])) {
					/* has children */
					foreach (self::$menus[$key] as $key2 => $item2) {
						$href = (self::$menus[$key][$key2]->url == '/') ? '/' : rtrim(self::$menus[$key][$key2]->url, '/');
					
						$new_menus[$key]['childern'][$key2]['class'] = self::$menus[$key][$key2]->class;
						$new_menus[$key]['childern'][$key2]['href']  = $href;
						$new_menus[$key]['childern'][$key2]['text']  = self::$menus[$key][$key2]->text;
						$new_menus[$key]['childern'][$key2]['icon']  = self::$menus[$key][$key2]->icon;
						$new_menus[$key]['childern'][$key2]['color']  = self::$menus[$key][$key2]->color;
						$new_menus[$key]['childern'][$key2]['target']  = self::$menus[$key][$key2]->target;
					}
				}
			}
		}

		/* filter out empty or menu items without urls */
		if ($filter_empty) {
			foreach ($new_menus as $idx=>$menu) {
				if (count($menu['childern']) == 0 && $menu['href'] == '') {
					unset($new_menus[$idx]);
				}
			}
		}

		$navigation_menu = self::build_twitter_bootstrap_menu($new_menus);

		ci()->event->trigger('menubar.right_navigation_menu',$navigation_menu,$start_at,$access,$filter_empty);

		return $navigation_menu;
	}

	static protected function build_twitter_bootstrap_menu($menu) {
		$html = '';

		if (is_array($menu)) {
			foreach ($menu as $item) {
				if (isset($item['childern'])) {

					/* has children */
					$html .= '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">';
					$html .= $item['text'].' <b class="caret"></b></a><ul class="dropdown-menu">';
					
					foreach ($item['childern'] as $row) {
						if ($row['href'] == '/#') {
							$html .= '<li class="dropdown-header '.$row['class'].'">'.$row['text'].'</li>';
						} else {
							$target = ($row['target']) ? ' target="'.$row['target'].'"' : '';
							$html .= '<li><a'.$target.' data-color="'.$row['color'].'" data-icon="'.$row['icon'].'" class="'.$row['class'].'" href="'.$row['href'].'">'.$row['text'].'</a></li>';
						}
					}
					
					$html .= '</ul></li>';

				} else {
					/* no children */
					$html .= '<li><a class="'.$item['class'].'" href="'.$item['href'].'">'.$item['text'].'</a></li>';
				}
			}
		}
		
		/* do the swap */
		$user = ci()->user;

		return str_replace(['{name}','{role}','{email}','{hr}'],[$user->username,$user->role_name,$user->email,'<li class="divider"></li>'],$html);
	}

} /* end class */