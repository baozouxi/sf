<?php
namespace Master\Controller;

use Think\Controller;


class MenuController extends Controller
{

	static public function getMenu($partId)
	{
		$menu = load_config(MODULE_PATH.'Conf/menu.php');
		switch ($partId) {
			case '0':
				unset($menu['统计管理']);
				unset($menu['医院管理']);
				unset($menu['管理员']);
				return $menu;
				break;

			case '1':
				unset($menu['管理员']);
				unset($menu['医院管理']['医院列表']);
				return $menu;
				break;

			case '2':
				return $menu;
				break;

			default:
				return array();
				break;

			return $menu;
		}
	}
}