<?php defined('BASEPATH') OR exit('No direct script access allowed');

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

class Module_Sidenav extends Module {

    public $version = '1';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'SideNav'
            ),
            'description' => array(
                'en' => 'Display navigation links in sidebar'
            ),
            'frontend' => false,
            'backend' => true,
            'menu' => 'structure',
            'sections' => array(
                'options' => array(
                    'name' => 'Pages',
                    'uri' => 'admin/sidenav'
                )
            )
        );
    }

    public function install()
    {
        $this->dbforge->drop_table('sidenav_pages');

        $tables = array(
            'sidenav_pages' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
                'page_id' => array('type' => 'INT', 'constraint' => 11),
                'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true),
                'hide' => array('type' => 'INT', 'constraint' => 11),
                'hide_children' => array('type' => 'INT', 'constraint' => 11),
                'hide_menu' => array('type' => 'INT', 'constraint' => 11),
            ),
        );

        if ( ! $this->install_tables($tables))
        {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('sidenav_pages');

        return true;
    }

    public function upgrade($old_version)
    {
        return false;
    }
}