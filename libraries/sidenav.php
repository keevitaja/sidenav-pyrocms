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

class Sidenav
{
    private $ci;
    private $top_page;
    private $links;

    public function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->model('sidenav_m');
    }

    // page tree for admin
    public function pages_tree($pages = false)
    {
        $pages = ($pages == false) ? $this->ci->sidenav_m->get_pages_tree() : $pages;
        $output = '';

        foreach ($pages as $page)
        {
            if (is_array($page))
            {
                $output .= '<li><ul>' . $this->pages_tree($page) . '</ul></li>';
            }
            else
            {
                $options = $this->ci->sidenav_m->get_options($page->id);
                $options_array = array();

                if ( ! empty($options['title']))
                {
                    $options_array[] = '( ' . $options['title'] . ' )';
                }

                if ($options['hide'] == 1)
                {
                    $options_array[] = 'H';
                }

                if ($options['hide_children'] == 1)
                {
                    $options_array[] = 'C';
                }

                if ($options['hide_menu'] == 1)
                {
                    $options_array[] = 'M';
                }

                if ( ! empty($options_array))
                {
                    $title = $page->title . ' : <span class="sidenav-options">[ ' . implode(' ', $options_array) . ' ]</span>';
                }
                else
                {
                    $title = $page->title;
                }

                $link = '<a href="' . site_url('admin/sidenav/page/' . $page->id) . '">' . $title . '</a>';

                $classes = array(
                    'sidenav-page-item'
                );

                if ($page->status == 'draft')
                {
                    $classes[] = 'sidenav-is-draft';
                }

                $classes = 'class="' . implode(" ", $classes) . '"';

                $output .= '<li ' . $classes . '>' . $link . '</li>';
            }
        }

        return $output;
    }

    // finds current pages id
    public function current_page()
    {
        $uri = $this->ci->uri->uri_string();
        $uri = (empty($uri)) ? 'home' : $uri;

        $page = $this->ci->sidenav_m->get_page(array('uri' => $uri));

        if (empty($page))
        {
            return false;
        }

        return $page->id;
    }

    // finds top page
    public function top_page()
    {
        if ($this->top_page === "0")
        {
            return 0;
        }

        if ($this->top_page != false)
        {
            $count_page = $this->ci->db->from('pages')->where('id', $this->top_page)->count_all_results();

            // if page does not exist, fall back to default behaviour
            if ($count_page != 0)
            {
                return $this->top_page;
            }
        }

        $uri = $this->ci->uri->uri_string();
        $uri = (empty($uri)) ? 'home' : $uri;

        $page = $this->ci->sidenav_m->get_page(array('uri' => $uri));

        if (empty($page))
        {
            return false;
        }

        $segments = explode('/', $page->uri);

        $page = $this->ci->sidenav_m->get_page(array('uri' => array_shift($segments)));

        return $page->id;
    }

    // find section top page
    public function section_top($page_id)
    {
        $page = $this->ci->sidenav_m->get_page($page_id);

        if (empty($page))
        {
            return false;
        }

        $segments = explode('/', $page->uri);

        // how many segments need to determine section top page
        // if start is 0, then 1, otherwise 2
        $needed_segments = ($this->top_page === "0") ? 1 : 2;

        if (count($segments) < $needed_segments)
        {
            return false;
        }

        $uri_segments = array_slice($segments, 0, $needed_segments);
        $uri = implode('/', $uri_segments);

        $page = $this->ci->sidenav_m->get_page(array('uri' => $uri));

        if (empty($page))
        {
            return false;
        }

        return $page->id;
    }

    // validate page for nav display
    public function valid_children($page_id)
    {
        $children = $this->ci->sidenav_m->get_children($page_id);
        $valid_children = array();

        foreach ($children as $page)
        {
            $segments = explode('/', $page->uri);

            // how many segments need to determine section top page
            // if start is 0, then 1, otherwise 2
            $needed_segments = ($this->top_page === "0") ? 1 : 2;

            if (count($segments) == $needed_segments)
            {
                $valid_children[] = $page;
            }
            elseif ($this->section_top($page->id) == $this->section_top($this->current_page()))
            {
                $valid_children[] = $page;
            }
        }

        return $valid_children;
    }

    // find all pages for navigation
    public function get_links_tree($page_id = false)
    {
        $page_id = ( ! $page_id) ? $this->top_page() : $page_id;

        if ($page_id === false)
        {
            return false;
        }

        $pages = array();

        $children = $this->valid_children($page_id);

        foreach ($children as $page)
        {
            $pages[] = $page;

            $children_check = $this->valid_children($page->id);

            if ( ! empty($children_check))
            {
                $pages[] = $this->get_links_tree($page->id);
            }
        }

        return (empty($pages)) ? false : $pages;
    }

    // build links for navigation
    public function build_links($pages = false)
    {
        $pages = ( ! $pages) ? $this->get_links_tree() : $pages;

        if ( ! $pages)
        {
            return false;
        }

        $output = '';

        foreach ($pages as $page)
        {
            if (is_array($page))
            {
                $output .= sprintf('<li class="sidenav-container"><ul>%s</ul></li>', $this->build_links($page));
            }
            else
            {
                $current_page = $this->current_page();
                $classes = array('sidenav-item');

                // check, if is current page
                if ($page->id == $current_page)
                {
                    $classes[] = 'sidenav-current';
                }

                $output .= sprintf('<li class="%s"><a href="%s" title="%s">%s</a></li>',
                    implode(' ', $classes),
                    site_url($page->uri),
                    $page->title_page,
                    $page->title
                );
            }
        }

        return $output;
    }

    // check, if current page has links
    public function plugin_has_links($top_page)
    {
        $options = $this->ci->sidenav_m->get_options($this->current_page());

        if ($options['hide_menu'] == 1)
        {
            return false;
        }

        $this->top_page = $top_page;

        $children = $this->ci->sidenav_m->get_children($this->top_page());

        return (empty($children)) ? false : true;
    }

    // return class to display in body element
    public function plugin_css_class($top_page)
    {
        return ($this->plugin_has_links($top_page)) ? 'sidebar' : 'no-sidebar';
    }

    // output links for plugin
    public function plugin_links($top_page)
    {
        return ($this->plugin_has_links($top_page)) ? $this->build_links() : false;
    }
}