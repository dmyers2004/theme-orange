<?php

class bootstrap_menu {
	static protected $menus;
	static protected $cache_key;

	/*
	Hidden On: /dashboard/*,/foo/bar/*,/cookies/monster
	*/
	static public function nav($left_menu=-1,$right_menu=-1,$filter_empty=true) {
		$hidden_on = setting('menubar','Hidden On','*SHOWONALL*');
		$left_root_menu = setting('menubar','Left Root Menu',$left_menu);
		$right_root_menu = setting('menubar','Right Root Menu',$right_menu);

		/*
		get all the menus this user has access to in parent / child order
		this is cached by the model based on the user access record ids
		*/
		$menus = ci()->o_menubar_model->get_menus_ordered_by_parent_ids(array_keys(ci()->user->access));

		/* now we build the bootstrap menubar */
		$nav = '';

		if (!empty($hidden_on)) {
			if (!preg_match('@,'.str_replace('*','[^,]*',$hidden_on).'@',',/'.ci()->uri->uri_string(),$matches)) {
				$nav .= '<nav class="navbar navbar-'.setting('menubar','Inverse Menubar','inverse').' navbar-fixed-top">';
				$nav .= '<div class="container">';
				$nav .= '<div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">';
				$nav .= '<span class="sr-only">Toggle</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>';
				$nav .= '</div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav">';
				$nav .= self::build('left',$left_root_menu,$menus,$filter_empty);
				$nav .= '</ul><ul class="nav navbar-nav navbar-right">';
				$nav .= self::build('right',$right_root_menu,$menus,$filter_empty);
				$nav .= '</ul></div></div></nav>';
			}
		}

		/* here you go! */
		return $nav;
	}

	static protected function build($side,$start_at,$menus,$filter_empty=true) {
		/*
		we are going to cache this based on the parameters
		this users access (role)
		where we need to start for this "build"
		weither to filter out empty parents (with no childern) 9 out of 10 time true

		NOTE: we are "cheating" a little by using the same cache prefix as the o_menubar_model
		this way when the model flushes it's caches on CUD our cache also get's flushed
		of course since we are tied pretty closely to the model anyway...
		*/
		$cache_key = 'tbl_orange_nav_'.md5(serialize($menus).$start_at.'/'.(int)$filter_empty);

		$navigation_menu = [];

		if (!$navigation_menu = ci()->cache->get($cache_key)) {
			if (is_array($menus)) {
				foreach ($menus[$start_at] as $key => $item) {
					$navigation_menu[$key]['class'] = $item->class;
					$navigation_menu[$key]['href']  = rtrim($item->url, '/#');
					$navigation_menu[$key]['text']  = $item->text;
					$navigation_menu[$key]['color'] = $item->color;
					$navigation_menu[$key]['icon'] = $item->icon;
					$navigation_menu[$key]['target'] = $item->target;

					if (isset($menus[$key])) {
						/* has children */
						foreach ($menus[$key] as $key2 => $item2) {
							$href = ($menus[$key][$key2]->url == '/') ? '/' : rtrim($menus[$key][$key2]->url, '/');

							$navigation_menu[$key]['childern'][$key2]['class'] = $menus[$key][$key2]->class;
							$navigation_menu[$key]['childern'][$key2]['href']  = $href;
							$navigation_menu[$key]['childern'][$key2]['text']  = $menus[$key][$key2]->text;
							$navigation_menu[$key]['childern'][$key2]['icon']  = $menus[$key][$key2]->icon;
							$navigation_menu[$key]['childern'][$key2]['color']  = $menus[$key][$key2]->color;
							$navigation_menu[$key]['childern'][$key2]['target']  = $menus[$key][$key2]->target;
						}
					}
				}
			}

			/* filter out empty or menu items without urls */
			if ($filter_empty) {
				foreach ($navigation_menu as $idx=>$menu) {
					if (count($menu['childern']) == 0 && $menu['href'] == '') {
						unset($navigation_menu[$idx]);
					}
				}
			}

			/* convert the array in to bootstrap format */
			$navigation_menu = self::build_twitter_bootstrap_menu($navigation_menu);

			/* cache it */
			ci()->cache->save($cache_key,$navigation_menu);
		}


		/* Ok now put in the dynamic users specific values */
		$user = ci()->user;

		$navigation_menu = str_replace(['{user.username}','{user.role_name}','{user.email}','{hr}'],[$user->username,$user->role_name,$user->email,'<li class="divider"></li>'],$navigation_menu);

		/* call any listeners incase they want a wack at it */
		ci()->event->trigger('menubar.build',$side,$navigation_menu,$start_at,$menus,$filter_empty);

		/* new menu! */
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

		return $html;
	}

} /* end class */