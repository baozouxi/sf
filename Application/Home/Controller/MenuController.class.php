<?php
namespace Home\controller;

use Think\Controller;

class MenuController extends Controller
{

	static public function getMenu($partId)
	{
		$menu = load_config(CONF_PATH.'menu.php');
		
		switch ($partId) {

			case '0':
				unset($menu['统计管理']);
				unset($menu['医院信息']);
				return $menu;
				break;

			case '1':
				unset($menu['统计管理']);
				unset($menu['医院信息']['管理员列表']);
				unset($menu['医院信息']['添加管理员']);
				return $menu;
				break;

			case '2':
				return $menu;
				break;
			default:
				return array();
				break;
		}
	}
}