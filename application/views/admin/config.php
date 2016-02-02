<?php
/**
 * config.php
 *
 * Author: pixelcave
 *
 * Global configuration file
 *
 */

// Include Template class
require '_classes/Template.php';

//定义资源根路径
$assets_folder = base_url() . 'assets';
// Create a new Template Object
$one                               = new Template('OneUI', '1.3', $assets_folder); // Name, version and assets folder's name

// Global Meta Data
$one->author                       = 'pixelcave';
$one->robots                       = 'noindex, nofollow';
$one->title                        = 'OneUI - Admin Dashboard Template & UI Framework';
$one->description                  = 'OneUI - Admin Dashboard Template & UI Framework created by pixelcave and published on Themeforest';

// Global Included Files (eg useful for adding different sidebars or headers per page)
$one->inc_side_overlay             = 'base_side_overlay.php';
$one->inc_sidebar                  = 'base_sidebar.php';
$one->inc_header                   = 'base_header.php';

// Global Color Theme
$one->theme                        = '';       // '' for default theme or 'amethyst', 'city', 'flat', 'modern', 'smooth'

// Global Cookies
$one->cookies                      = false;    // True: Remembers active color theme between pages (when set through color theme list), False: Disables cookies

// Global Body Background Image
$one->body_bg                      = '';       // eg 'assets/img/photos/photo10@2x.jpg' Useful for login/lockscreen pages

// Global Header Options
$one->l_header_fixed               = true;     // True: Fixed Header, False: Static Header

// Global Sidebar Options
$one->l_sidebar_position           = 'left';   // 'left': Left Sidebar and right Side Overlay, 'right;: Flipped position
$one->l_sidebar_mini               = false;    // True: Mini Sidebar Mode (> 991px), False: Disable mini mode
$one->l_sidebar_visible_desktop    = true;     // True: Visible Sidebar (> 991px), False: Hidden Sidebar (> 991px)
$one->l_sidebar_visible_mobile     = false;    // True: Visible Sidebar (< 992px), False: Hidden Sidebar (< 992px)

// Global Side Overlay Options
$one->l_side_overlay_hoverable     = false;    // True: Side Overlay hover mode (> 991px), False: Disable hover mode
$one->l_side_overlay_visible       = false;    // True: Visible Side Overlay, False: Hidden Side Overlay

// Global Sidebar and Side Overlay Custom Scrolling
$one->l_side_scroll                = true;     // True: Enable custom scrolling (> 991px), False: Disable it (native scrolling)

// Global Active Page (it will get compared with the url of each menu link to make the link active and set up main menu accordingly)
$one->main_nav_active              = basename($_SERVER['PHP_SELF']);

// Global Main Menu
$one->main_nav                     = array(
    array(
        'name'  => '<span class="sidebar-mini-hide">Dashboard</span>',
        'icon'  => 'si si-speedometer',
        'url'   => admin_url()
    )
);


if(check_user_permission('article')) {
    $nav = array(
        'name'  => '<span class="sidebar-mini-hide">文章管理</span>',
        'icon'  => 'si si-grid',
        'sub'   => array(
            array(
                'name'  => '文章列表',
                'url'   => admin_url()
            ),
            array(
                'name'  => '新建文章',
                'url'   => admin_url()
            )
        )
    );
    array_push($one->main_nav, $nav);
}

if(check_user_permission('analysis')) {
    $nav = array(
        'name'  => '<span class="sidebar-mini-hide">统计分析</span>',
        'icon'  => 'si si-grid',
        'sub'   => array(
            array(
                'name'  => '日活',
                'url'   => admin_url()
            ),
            array(
                'name'  => '留存率',
                'url'   => admin_url()
            )
        )
    );
    array_push($one->main_nav, $nav);
}

if(check_user_permission('user')) {
    $nav = array(
        'name'  => '<span class="sidebar-mini-hide">系统管理员</span>',
        'type'  => 'heading'
    );
    $nav1 = array(
        'name'  => '<span class="sidebar-mini-hide">用户角色和权限管理</span>',
        'icon'  => 'si si-grid',
        'sub'   => array(
            array(
                'name'  => '用户管理',
                'url'   => admin_url()
            ),
            array(
                'name'  => '角色管理',
                'url'   => admin_url()
            ),
            array(
                'name'  => '权限管理',
                'url'   => admin_url()
            )
        )
    );
    array_push($one->main_nav, $nav, $nav1);
}


/**
 * 检查用户权限，是否显示菜单
 *
 * @param $p
 * @return bool
 */
function check_user_permission($p)
{
    if(check_user_role('super_admin')) {
        //超级管理员拥有所有权限
        return true;
    }
    $perms = $_SESSION[Const_string::SessionPermsKey];
    if(empty($perms) || ! in_array($p,$perms)) {
        return false;
    }
    return true;
}

function check_user_role($r)
{
    $roles = $_SESSION[Const_string::SessionRolesKey];
    if(empty($roles) || ! in_array($r,$roles)) {
        return false;
    }
    return true;
}

/**
 * 将one对象作为全局变量输出
 * update by liaosy 2016-01-19
 */
$this->load->vars(array('one' => $one));