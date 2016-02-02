<?php
/**
 * Created by PhpStorm.
 * User: liaosy
 * Date: 16/2/1
 * Time: 下午2:35
 */


/**
 * admin专用 url 方法
 *
 * @param string $uri
 * @return string
 */
function admin_url($uri = '')
{
    return base_url(Const_string::Admin . '/' . $uri);
}

/**
 * admin专用 redirect 方法
 *
 * @param string $uri
 * @param string $method
 * @param null $code
 */
function admin_redirect($uri = '', $method = 'auto', $code = NULL)
{
    redirect(Const_string::Admin . '/' . $uri, $method, $code);
}