<?php // https://github.com/retlehs/roots/wiki

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));

require_once locate_template('/inc/roots-activation.php'); // activation
require_once locate_template('/inc/roots-options.php'); // theme options
require_once locate_template('/inc/roots-cleanup.php'); // cleanup
//require_once locate_template('/inc/roots-htaccess.php'); // rewrites for assets, h5bp htaccess
require_once locate_template('/inc/roots-hooks.php'); // hooks
require_once locate_template('/inc/roots-actions.php'); // actions
require_once locate_template('/inc/roots-widgets.php'); // widgets
require_once locate_template('/inc/roots-custom.php'); // custom functions
require_once locate_template('/inc/liahona-quotations/quotations.php'); // liahona quotations
//require_once locate_template('/inc/mailchimp/liahona_mailchimp.php'); // liahona quotations
//require_once locate_template('/inc/ilchimp/liahona_mailchimp.php'); // liahona quotations

$roots_options = roots_get_theme_options();

// set the maximum 'Large' image width to the maximum grid width
// http://wordpress.stackexchange.com/q/11766
if (!isset($content_width)) {
    global $roots_options;
    $roots_css_framework = $roots_options['css_framework'];
    switch ($roots_css_framework) {
        case 'blueprint':
            $content_width = 950;
            break;
        case '960gs_12':
            $content_width = 940;
            break;
        case '960gs_16':
            $content_width = 940;
            break;
        case '960gs_24':
            $content_width = 940;
            break;
        case '1140':
            $content_width = 1140;
            break;
        case 'adapt':
            $content_width = 940;
            break;
        case 'bootstrap':
            $content_width = 940;
            break;
        case 'foundation':
            $content_width = 980;
            break;
        default:
            $content_width = 950;
            break;
    }
}

function roots_setup()
{
    load_theme_textdomain('roots', get_template_directory() . '/lang');

    // tell the TinyMCE editor to use editor-style.css
    // if you have issues with getting the editor to show your changes then
    // use this instead: add_editor_style('editor-style.css?' . time());
    add_editor_style('editor-style.css');

    // http://codex.wordpress.org/Post_Thumbnails
    add_theme_support('post-thumbnails');
    //set_post_thumbnail_size(250, 250, true);
    update_option('thumbnail_size_w', 250);
    update_option('thumbnail_size_h', 250);

    add_image_size('featured', 300, 200, false); // For featured post/page images

    // http://codex.wordpress.org/Post_Formats
    // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

    // http://codex.wordpress.org/Function_Reference/add_custom_image_header
    if (!defined('HEADER_TEXTCOLOR')) {
        define('HEADER_TEXTCOLOR', '');
    }
    if (!defined('NO_HEADER_TEXT')) {
        define('NO_HEADER_TEXT', true);
    }
    if (!defined('HEADER_IMAGE')) {
        define('HEADER_IMAGE', get_template_directory_uri() . '/img/logo.png');
    }
    if (!defined('HEADER_IMAGE_WIDTH')) {
        define('HEADER_IMAGE_WIDTH', 960);
    }
    if (!defined('HEADER_IMAGE_HEIGHT')) {
        define('HEADER_IMAGE_HEIGHT', 88);
    }

    function roots_custom_image_header_site()
    {
    }

    function roots_custom_image_header_admin()
    {
        ?>
    <style type="text/css">
        .appearance_page_custom-header #heading {
            min-height: 0;
        }
    </style>
    <?php
    }

    add_custom_image_header('roots_custom_image_header_site', 'roots_custom_image_header_admin');

    // http://codex.wordpress.org/Function_Reference/register_nav_menus
    register_nav_menus(array(
        'primary_navigation' => __('Primary Navigation', 'roots'),
        'utility_navigation' => __('Utility Navigation', 'roots')
    ));

    // Add "Featured" meta box

}

add_action('after_setup_theme', 'roots_setup');


// http://codex.wordpress.org/Function_Reference/register_sidebar
// hook into 'widgets_init' function with a lower priority in your
// child theme to remove these sidebars
function roots_register_sidebars()
{
    $sidebars = array('Sidebar', 'Footer');

    foreach ($sidebars as $sidebar) {
        register_sidebar(
            array(
                'id' => 'roots-' . strtolower($sidebar),
                'name' => __($sidebar, 'roots'),
                'description' => __($sidebar, 'roots'),
                'before_widget' => '<article id="%1$s" class="widget %2$s"><div class="container">',
                'after_widget' => '</div></article>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            )
        );
    }
}

add_action('widgets_init', 'roots_register_sidebars');

// return post entry meta information
function roots_entry_meta()
{
    global $post;

    //echo '<time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('Posted on %s.', 'roots'), get_the_date()).'</time>';
    if (in_category('essays'))
        echo '<p class="byline author vcard">' . __('AN ESSAY by', 'roots') . ' <a href="' . get_author_posts_url(get_the_author_meta('id')) . '" rel="author" class="fn">' . get_the_author() . '</a> ';
    //echo edit_post_link(__('Edit entry'), '&bull; ') . '</p>';
}

// add Callout shortcode

function liahona_callout($args = array(), $content = NULL)
{
    extract(shortcode_atts(array(
        'align' => 'left'
    ), $args));
    $style = "text-align: $align;";
    return "<div class='callout' style='$style'>" . do_shortcode($content) . "</div>";
}

add_shortcode('callout', 'liahona_callout');

// current date shortcode

function date_func($atts)
{
    extract(shortcode_atts(array(
        'dateFormat' => 'F j, Y'
    ), $atts));
    $thetime = time();
    $date = date($dateFormat, $thetime);
    $script = <<<HTML
    <noscript>$date</noscript>
<script type="text/javascript">
<!--
var currentTime = new Date();
var month = currentTime.getMonth();
var day = currentTime.getDate();
var year = currentTime.getFullYear();
var months = "January,February,March,April,May,June,July,August,September,October,November,December".split(',');
document.write(months[month] + " " + day + ", " + year);
//-->
</script>
HTML;

    return $script;
}

add_shortcode('date', 'date_func');

// Add shortcodes in excerpts
add_filter('the_excerpt', 'shortcode_unautop');
add_filter('the_excerpt', 'do_shortcode');

// add thumbnails with_link
function get_thumbnail_link($type = 'thumbnail', $to_echo = false)
{
    global $post;
    $img = '';
    if (has_post_thumbnail()) {
        $img .= '<a href="' . get_permalink($post->ID) . '">' . get_the_post_thumbnail($post->ID, $type) . '</a>';
    }
    if ($to_echo)
        echo $img;
    return $img;
}


// add "featured" meta checkbox
add_action('admin_init', 'liahona_featured_init');

function liahona_featured_init()
{
    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_script
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_style

    //wp_enqueue_script('liahona_featured_js', MY_THEME_PATH . '/custom/meta.js', array('jquery'));
    //    wp_enqueue_style('liahona_featured_css', MY_THEME_PATH . '/custom/meta.css');

    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/add_meta_box

    // add a meta box for each of the wordpress page types: posts and pages
    foreach (array('post', 'page') as $type)
    {
        add_meta_box('my_all_meta', "Featured $type", 'liahona_featured_setup', $type, 'side', 'default');
    }

    // add a callback function to save any data a user enters in
    add_action('save_post', 'liahona_featured_save');
}

function liahona_featured_setup()
{
    global $post;

    // using an underscore, prevents the meta variable
    // from showing up in the custom fields section
    $meta = get_post_meta($post->ID, '_liahona_featured', TRUE);
    ?>
<div class="liahona_featured">
    <input type="checkbox" name="_liahona_featured" id="_liahona_featured" value="featured"
        <?php echo $meta == 'featured' ? 'checked="checked"' : ''; ?>
        />
    <label for="_liahona_featured">Set as featured <?php echo $post->post_type; ?> (top of front page)</label>
</div>
<?php
    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="liahona_featured_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function liahona_featured_save($post_id)
{
    // authentication checks

    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['liahona_featured_noncename'], __FILE__)) return $post_id;

    // check user permissions
    if ($_POST['post_type'] == 'page') {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    }
    else
    {
        if (!current_user_can('edit_post', $post_id)) return $post_id;
    }

    // authentication passed, save data

    $current_data = get_post_meta($post_id, '_liahona_featured', TRUE);

    $new_data = $_POST['_liahona_featured'];

    if ($current_data) {
        if (is_null($new_data)) delete_post_meta($post_id, '_liahona_featured');
        else update_post_meta($post_id, '_liahona_featured', $new_data);
    }
    elseif (!is_null($new_data))
    {
        add_post_meta($post_id, '_liahona_featured', $new_data, TRUE);
    }

    return $post_id;
}

// Make Liahona project the logo
function login_styles() {
echo '<style type="text/css">
.login h1 a { background: url(http://theliahonaproject.net/assets/facebook1.jpg) no-repeat center top;
 height: 175px;
 }
 /*
body.login { background: #0C3C53 !important; }
.login #nav a, .login #backtoblog a .login #nav a:hover, .login #backtoblog a:hover { color: white !important;}
*/
</style>';
}
add_action('login_head', 'login_styles');

add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

function wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$profile_url = get_edit_profile_url( $user_id );

if ( 0 != $user_id ) {
/* Add the "My Account" menu */
$avatar = get_avatar( $user_id, 28 );
$howdy = sprintf( __('Welcome, %1$s'), $current_user->display_name );
$class = empty( $avatar ) ? '' : 'with-avatar';

$wp_admin_bar->add_menu( array(
'id' => 'my-account',
'parent' => 'top-secondary',
'title' => $howdy . $avatar,
'href' => $profile_url,
'meta' => array(
'class' => $class,
),
) );

}
}

