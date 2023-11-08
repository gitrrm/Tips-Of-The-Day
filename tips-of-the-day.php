<?php 
/**
    Plugin Name: Tips of the Day
    Description: A simple plugin to store and display daily tips.
    Version: 1.0
    Author: Rashmi Ranjan Muduli for Nandan Global
 */ 
    
// Enqueue CSS for tips (optional)
function tips_of_the_day_enqueue_styles() {
    wp_enqueue_style('tips-of-the-day-styles', plugin_dir_url(__FILE__) . 'styles.css');
}
add_action('wp_enqueue_scripts', 'tips_of_the_day_enqueue_styles');

// Register 'Tips' Custom Post Type
function tips_of_the_day_register_post_type() {
    $labels = array(
        'name' => 'Tips',
        'singular_name' => 'Tip',
        'menu_name' => 'Tips of the Day',
        'add_new' => 'Add New Tip',
        'add_new_item' => 'Add New Tip',
        'edit_item' => 'Edit Tip',
        'new_item' => 'New Tip',
        'view_item' => 'View Tip',
        'view_items' => 'View Tips',
        'search_items' => 'Search Tips',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'tip'),
        'supports' => array('title', 'editor'),
        'menu_icon' => 'dashicons-lightbulb',
    );

    register_post_type('tip', $args);
}
add_action('init', 'tips_of_the_day_register_post_type');

// Create a Shortcode to Display Tips
function tips_of_the_day_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 1, // Number of tips to display
    ), $atts);

    $tips_query = new WP_Query(array(
        'post_type' => 'tip',
        'posts_per_page' => $atts['count'],
        'orderby' => 'rand', // Display tips randomly
    ));

    if ($tips_query->have_posts()) {
        ob_start();
        while ($tips_query->have_posts()) {
            $tips_query->the_post();
            echo '<div class="tip">';
            the_title('<strong>', '</strong>');
            echo '<b> : </b>';
            the_content();
            echo '</div>';
        }
        wp_reset_postdata();
        return ob_get_clean();
    }

    return 'No tips found.';
}
add_shortcode('tips_of_the_day', 'tips_of_the_day_shortcode');
