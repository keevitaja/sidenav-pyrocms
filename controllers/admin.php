<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class Admin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('sidenav');
    }

    public function index()
    {
        $this->template
            ->set('pages', $this->sidenav->pages_tree())
            ->append_css('module::sidenav.css')
            ->build('admin/index');
    }

    public function page($page_id)
    {
        $page_id = (int)$page_id;
        $page = $this->sidenav_m->get_page($page_id);

        $this->template
            ->set('title', $page->title)
            ->set('options', $this->sidenav_m->get_options($page_id))
            ->build('admin/page');
    }

    public function page_update($page_id)
    {
        $this->sidenav_m->save_options($page_id, array(
            'title' => trim((string)$this->input->post('title')),
            'hide' => (int)$this->input->post('hide'),
            'hide_children' => (int)$this->input->post('hide_children'),
            'hide_menu' => (int)$this->input->post('hide_menu')
        ));

        $this->session->set_flashdata('success', 'Update successful');

        $url = ($this->input->post('submit') == 'esave') ? 'admin/sidenav' : 'admin/sidenav/page/' . $page_id;

        redirect($url, 'location');
    }
}