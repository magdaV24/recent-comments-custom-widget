<?php

/**
 * Plugin Name: Recent Comments 
 * Description: A custom plugin that shows the recent comments;
 * Author: Magda Vasilache
 * Author URI: -
 * Version: 1.0.0
 * Text Domain: recent-comments-custom-plugin
 */

if (!defined('ABSPATH')) {
    exit;
}
function recent_comments_scripts()
{
    wp_enqueue_script('recent-comments-script', plugin_dir_url(__FILE__) . '/js/recent-comments.js', array('jquery'), '1.0', true);
    wp_enqueue_style('recent-comments-style', plugin_dir_url(__FILE__) . '/css/recent-comments.css');
}
add_action('wp_enqueue_scripts', 'recent_comments_scripts');

function register_recent_comments_widget()
{
    register_widget('Custom_Recent_Comments_Widget');
}
add_action('widgets_init', 'register_recent_comments_widget');

class Custom_Recent_Comments_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'recent_comments',
            'Custom Recent Comments Widget',
            array('description' => 'Displays recent comments with avatars.')
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
?>
        <div class="widget-title">
            <span class="widget-title-before">
                <?php echo $args['before_title']; ?>
            </span>
            <span class="widget-title-text">
                Recent comments
            </span>
            <span class="widget-title-after">
                <?php echo $args['after_title']; ?>
            </span>
        </div>

        <?php
        $comments_args = array(
            'number' => $instance['count'],
            'status' => 'approve',
        );
        $comments = get_comments($comments_args);

        if ($comments) :
            foreach ($comments as $comment) :
        ?>
                <div class="comment-widget-wrapper">
                    <div class="comment-widget-header">
                        <div class="comment-widget-avatar-wrapper"><?php echo get_avatar($comment, 35); ?></div>
                        <p><?php echo get_comment_author($comment); ?> commented:</p>
                    </div>

                    <div class="sidebar-comment-text"><?php echo get_comment_text($comment); ?></div>
                    <p><a href="<?php echo esc_url(get_comment_link($comment)); ?>">See the comment &rarr;</a></p>
                </div>
        <?php
            endforeach;
        else :
            echo 'No comments found.';
        endif;

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $count = !empty($instance['count']) ? $instance['count'] : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Number of comments to display:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" />
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['count'] = (!empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '';
        return $instance;
    }
}
