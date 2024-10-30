<?php
/**
 * Plugin Name:       Latest Post Widget
 * Plugin URI:        https://staged.fun/plugins/latest-post-widget
 * Description:       Shows the latest post with the featured image. Adds a widget to do it.
 * Version:           1.1
 * Requires at least: 2.9.0
 * Requires PHP:      4.3 or higher
 * Author:            Asif Chowdhury
 * Author URI:        https://staged.fun/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       latest-post-widget
 * Domain Path:       /languages
 */

// get the file for category data
include_once  __DIR__ . '/inc/category_data.php';

// get the file for latest post query and retrn
include_once __DIR__ . '/inc/get_latest_post.php';



class Latest_Post_Widget extends WP_Widget
{
    //  Setting up the Widget
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'latest_post_widget',
            'description' => 'Adds a widget to display latest post including the featured image.'
        );
        parent::__construct(
            'latest_post_widget',
            esc_html__('Latest Post Widget', 'Latest_Post_Widget'),
            $widget_ops
        );
    }

    // output content of the widget
    // @param array $args
    // @param array $instance
    public function widget($args, $instance)
    {
        // this part is the output
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] .
                apply_filters('widget_title', $instance['title']) .
                $args['after_title'];
        }
        // getting the latest post


        echo getlatestpost($show_category = $instance['show_category'] ? 'true' : 'false', $show_date = $instance['show_date'] ? 'true' : 'false');
        echo $args['after_widget'];
    }

    // output the options form on admin
    // @param array $instance The widget options
    public function form($instance)
    {
        // this part is the output for admin
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'latest-post-widget');
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'latest-post-widget'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_category')); ?>"><?php esc_attr_e('Show Category:', 'latest-post-widget'); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo esc_attr($this->get_field_id('show_category')); ?>" name="<?php echo esc_attr($this->get_field_name('show_category')); ?>" <?php checked($instance['show_category'], 'on'); ?>>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_attr_e('Show Date:', 'latest-post-widget'); ?></label>
            <input type="checkbox" class="widefat" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" <?php checked($instance['show_date'], 'on'); ?>>
        </p>
<?php
    }

    // processing widget options on save
    // @return array
    public function update($new_instance, $old_instance)
    {
        // this part processes the widget options to be saved
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_category'] = (!empty($new_instance['show_category'])) ? sanitize_text_field($new_instance['show_category']) : '';
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? sanitize_text_field($new_instance['show_date']) : '';

        return $instance;
    }
}

// registering the widget
function register_latest_post_widget()
{
    register_widget('Latest_Post_Widget');
}
add_action('widgets_init', 'register_latest_post_widget');

//  registering and Enqueue css stylesheet
function latest_post_widget_scripts()
{
    wp_enqueue_style('latest-post-widget-style', plugin_dir_url( __FILE__ ) . '/inc/css/latest-post-widget.css');
}
add_action('wp_enqueue_scripts', 'latest_post_widget_scripts');

// register thumbnail size

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
    function wpdocs_theme_setup() {
        add_image_size( 'lpw-featured', 348, 352, true ); // default.

    }
}