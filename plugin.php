<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sidenav module
 * 2013
 *
 * https://github.com/keevitaja/sidenav-pyrocms
 *
 * @package     sidenav
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @version     master
 *
 */

class Plugin_Sidenav extends Plugin
{
    public $version = '1';

    public $name = array(
        'en' => 'SideNav',
    );

    public $description = array(
        'en' => 'Display navigation links in sidebar',
    );

    function __construct()
    {
        $this->load->library('sidenav');
    }

    public function has_links()
    {
        $top_page = $this->attribute('start_from');

        return $this->sidenav->plugin_has_links($top_page);
    }

    public function css_class()
    {
        $top_page = $this->attribute('start_from');

        return $this->sidenav->plugin_css_class($top_page);
    }

    public function links()
    {
        $top_page = $this->attribute('start_from');

        return $this->sidenav->plugin_links($top_page);
    }
}