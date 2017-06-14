<?php
namespace Master\Controller;

use Think\Controller;
use Master\Controller\MenuController;

class CommonController extends Controller
{

    protected static $menu = array();
    protected static $prefix;

    public function __construct()
    {
        self::$prefix = C('SESSION_PREFIX');
        parent::__construct();
        if (!isset($_SESSION[self::$prefix]['status'])||
            !isset($_SESSION[self::$prefix]['id'])||
            !isset($_SESSION[self::$prefix]['name'])||
            !isset($_SESSION[self::$prefix]['admin_part'])
            ){
                header('location:/Master/Login/index');
            exit;
        }
        // $file = COMMON_PATH.'/Common/menu.php';
        self::$menu = MenuController::getMenu($_SESSION[self::$prefix]['admin_part']);
    }

    //表单唯一标识
    protected function _unique($name)
    {
        $code = mt_rand(0,9);
        for ($i=1;$i<5;$i++) {
            $code .= mt_rand(0,9);
        }
        return $_SESSION[$name] = sha1($code);
    }
}