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

class Sidenav_m extends MY_Model
{
    // get all children for navigation links
    public function get_children($page_id = 0)
    {
        $pages = array();

        if ($page_id === false)
        {
            return $pages;
        }

        $options = $this->get_options($page_id);

        // check if children are excluded from page options
        if ($options['hide_children'] == 1)
        {
            return $pages;
        }

        $pages_all = $this->db
            ->select('id, title, uri')
            ->where(array(
                'parent_id' => $page_id,
                'status' => 'live'
            ))
            ->order_by('order')
            ->get('pages')->result();

        if (empty($pages_all))
        {
            return $pages;
        }

        // check if page is hidden from options, find title
        foreach ($pages_all as $page)
        {
            $options = $this->get_options($page->id);

            if ($options['hide'] != 1)
            {
                $page->title_page = $page->title;
                $page->title = (empty($options['title'])) ? $page->title : $options['title'];

                $pages[] = $page;
            }
        }

        return $pages;
    }

    // get all pages for admin recursive
    public function get_pages_tree($page_id = 0)
    {
        $pages = array();

        $children = $this->db
            ->select('id, title, status')
            ->where('parent_id', $page_id)
            ->order_by('order')
            ->get('pages')->result();

        foreach ($children as $page)
        {
            $pages[] = $page;

            $count_children = $this->db
                ->from('pages')
                ->where('parent_id', $page->id)
                ->count_all_results();

            if ($count_children != 0)
            {
                $pages[] = $this->get_pages_tree($page->id);
            }
        }

        return $pages;
    }

    // get page
    public function get_page($search)
    {
        $where = (is_array($search)) ? $search : array('id' => $search);

        return $this->db
            ->select('id, title, uri')
            ->where($where)
            ->get('pages')->row();
    }

    // get page options
    public function get_options($page_id)
    {
        // if page does not have any options defined
        $options_empty = array(
            'page_id' => $page_id,
            'title' => '',
            'hide' => '',
            'hide_children' => '',
            'hide_menu' => ''
        );

        $options = $this->db->where('page_id', $page_id)->get('sidenav_pages')->row_array();

        if (empty($options))
        {
            $options = $options_empty;
        }

        return $options;
    }

    // create or update page options
    public function save_options($page_id, $options)
    {
        // delete options, if all values are empty
        $delete = true;

        foreach ($options as $option)
        {
            if ($option)
            {
                $delete = false;
            }
        }

        if ($delete)
        {
            return $this->db->where('page_id', $page_id)->delete('sidenav_pages');
        }

        $count_options = $this->db->from('sidenav_pages')->where('page_id', $page_id)->count_all_results();

        // check if page has options saved
        if ($count_options != 0)
        {
            return $this->db->set($options)->where('page_id', $page_id)->update('sidenav_pages');
        }
        else
        {
            return $this->db->set($options)->set('page_id', $page_id)->insert('sidenav_pages');
        }
    }
}