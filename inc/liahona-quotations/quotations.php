<?php
/**
 * Add custom quotations and widget to liahona
 *
 */

define('MY_WORDPRESS_FOLDER', $_SERVER['DOCUMENT_ROOT']);
define('LIAHONA_FOLDER', str_replace("\\", '/', dirname(__FILE__)));
define('LIAHONA_PATH', '/' . substr(LIAHONA_FOLDER, stripos(LIAHONA_FOLDER, 'wp-content')));


// add quotations taxonomy
function add_liahona_quotation()
{
    $labels = array(
        'name' => __('Quotations', 'post type general name'),
        'singular_name' => __('Quotation', 'post type singular name'),
        'add_new' => _x('Add New', 'Listing'),
        'add_new_item' => __('Add New Quotation'),
        'edit_item' => __('Edit Quotation'),
        'new_item' => __('New Quotation'),
        'all_items' => __('All Quotations'),
        'view_item' => __('View Quotation'),
        'search_items' => __('Search Quotations'),
        'not_found' => __('No Quotations found'),
        'not_found_in_trash' => __('No Quotations found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Quotations'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'menu_position' => 4,
        'capability_type' => 'post',
        'hierarchical' => false,
        'has_archive' => true,
        'rewrite' => array('slug' => 'quotation', 'with_front' => false), // Important!
        'supports' => array('editor'),
        'taxonomies' => array(),
    );
    register_post_type('liahona_quotation', $args);
}

// Trying to remove the HTML editor...it's not working though!
add_filter('wp_default_editor', create_function('', 'return "tinymce";'));
add_action('admin_head', 'disable_html_editor_li_quotes');
function disable_html_editor_li_quotes()
{
    if (@$_GET['post_type'] == 'liahona_quotation') {
        echo '<style type="text/css">#editor-toolbar #edButtonHTML, #quicktags {display: none;}</style>';
    }
}


add_action('init', 'add_liahona_quotation');

// Change the columns for the edit liahona quotation screen
function change_columns($cols)
{
    $cols = array(
        'cb' => '<input type="checkbox" />',
        'li_quote' => __('Quote', 'liahona'),
        'li_author' => __('Author', 'liahona'),
        'li_url_title' => __('URL', 'liahona'),
    );
    return $cols;
}

add_filter("manage_liahona_quotation_posts_columns", "change_columns");

function custom_columns($column, $post_id)
{
    global $post;
    $meta = get_post_meta($post_id, '_li_quotations_meta', true);
    switch ($column) {
        case "li_quote":
            echo edit_post_link($post->post_content);
            break;
        case "li_author":
            echo isset($meta['author']) ? $meta['author'] : '';
            break;
        case "li_url_title":
            if(isset($meta['URL'])) {
                echo "<a href='{$meta['URL']}'>";
                echo isset($meta['url_title'])? $meta['url_title'] : str_replace('http://','',$meta['URL']);
                echo "</a>";
            }

            break;
    }
}

add_action("manage_posts_custom_column", "custom_columns", 10, 2);

// Make these columns sortable
function sortable_columns()
{
    return array(
        'li_quote' => 'li_quote',
        'li_author' => 'li_author',
        'li_url' => 'li_url_title'
    );
}

add_filter("manage_edit-liahona_quotation_sortable_columns", "sortable_columns");

add_action('admin_init', 'li_quotations_meta_init');

function li_quotations_meta_init()
{
    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_script
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_style

    //wp_enqueue_script('li_quotations_meta_js', MY_THEME_PATH . '/custom/meta.js', array('jquery'));
    wp_enqueue_style('li_quotations_meta_css', LIAHONA_PATH . '/quotations.css');

    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/add_meta_box

    add_meta_box('quotations_all_meta', 'Quotation details', 'li_quotations_meta_setup', 'liahona_quotation', 'normal', 'high');

    // add a callback function to save any data a user enters in
    add_action('save_post', 'li_quotations_meta_save');
}

function li_quotations_meta_setup()
{
    global $post;

    // using an underscore, prevents the meta variable
    // from showing up in the custom fields section
    $meta = get_post_meta($post->ID, '_li_quotations_meta', TRUE);

    ?>
    <div class="liahona-quotations-control">
    	<p>Enter the quotation, author&rsquo;s name and a URL (if applicable) for  the quote to link to. Also, in the quotation
        area above, don&rsquo;t format it. It's best to just enter text so the plugin functions correctly.</p>

    	<label>Author Name</label>

    	<p>
    		<input type="text" name="_li_quotations_meta[author]" value="<?php if(!empty($meta['author'])) echo $meta['author']; ?>"/>
    	</p>

    	<label>URL title and link<span>(optional)</span></label>

    	<p>
            <input type="text" name="_li_quotations_meta[url_title]"
                   value="<?php if(!empty($meta['url_title'])) echo $meta['url_title']; ?>"
                    placeholder="Link Title"
                />
            <input type="url" name="_li_quotations_meta[URL]" value="<?php if(!empty($meta['URL'])) echo $meta['URL']; ?>"
                placeholder="Link URL"
                />
    	</p>

    </div>
        <?php
    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="li_quotations_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function li_quotations_meta_save($post_id)
{
    // authentication checks

    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['li_quotations_meta_noncename'], __FILE__)) return $post_id;

    // check user permissions
    if ($_POST['post_type'] == 'liahona_quotation') {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    }

    // authentication passed, save data

    // var types
    // single: _li_quotations_meta[var]
    // array: _li_quotations_meta[var][]
    // grouped array: _li_quotations_meta[var_group][0][var_1], _li_quotations_meta[var_group][0][var_2]

    $current_data = get_post_meta($post_id, '_li_quotations_meta', TRUE);

    $new_data = $_POST['_li_quotations_meta'];

    li_quotations_meta_clean($new_data);
    if (isset($new_data['URL'])) {
        //        var_dump(strpos($new_data['URL'],'http://'));
        //        die;
        if (strpos($new_data['URL'], 'http://') !== 0) {
            $new_data['URL'] = 'http://' . $new_data['URL'];
        }
    }

    if ($current_data) {
        if (is_null($new_data)) delete_post_meta($post_id, '_li_quotations_meta');
        else update_post_meta($post_id, '_li_quotations_meta', $new_data);
    }
    elseif (!is_null($new_data))
    {
        add_post_meta($post_id, '_li_quotations_meta', $new_data, TRUE);
    }

    return $post_id;
}

function li_quotations_meta_clean(&$arr)
{
    if (is_array($arr)) {
        foreach ($arr as $i => $v)
        {
            if (is_array($arr[$i])) {
                li_quotations_meta_clean($arr[$i]);

                if (!count($arr[$i])) {
                    unset($arr[$i]);
                }
            }
            else
            {
                if (trim($arr[$i]) == '') {
                    unset($arr[$i]);
                }
            }
        }

        if (!count($arr)) {
            $arr = NULL;
        }
    }
}

// =======================================================
//    Quotations Widget
// =======================================================

class LiahonaQuotesWidget extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array('classname' => 'LiahonaQuotesWidget', 'description' => 'Displays a random quotation');
        parent::__construct('LiahonaQuotesWidget', 'Random Quotations', $widget_ops);
    }

    function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = $instance['title'];
        ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:
        <input class="widefat"
               id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>"
               type="text"
               value="<?php echo esc_attr($title); ?>"/></label>
    </p>
    <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if (!empty($title))
            echo $before_title . $title . $after_title;

        $args = array(
            'numberposts' => 1,
            'orderby' => 'rand',
            'post_type' => 'liahona_quotation',
            'post_status' => 'publish'
        );
        $the_quotes = get_posts($args);

        if (isset($the_quotes[0])) {
            $meta = get_post_meta($the_quotes[0]->ID, '_li_quotations_meta', TRUE);
            setup_postdata($the_quotes[0]);
            echo "<div class='quote_quotation'>";
            the_content();
            echo "</div>";
            echo "<div class='quote_author'>" . $meta['author'] . "</div>";
            if(isset($meta['URL'])) {
                echo "<div class='quote_url'><a href='{$meta['URL']}'>";
                echo isset($meta['url_title'])? $meta['url_title'] : str_replace('http://','',$meta['URL']);
                echo "</a></div>";
            }

        }

        echo $after_widget;
    }

}

add_action('widgets_init', create_function('', 'return register_widget("LiahonaQuotesWidget");'));