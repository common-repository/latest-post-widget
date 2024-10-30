<?php
// function to get the latest post

function getlatestpost($show_date, $show_category)
{
    $latest_posts = wp_get_recent_posts(
        array(
            'numberposts' => 1,
            'post_status' => 'publish'
        )
    );
    foreach ($latest_posts as $post) :
        $size = 'lpw-featured';
        $category_text = 'In';
        $date_text = 'On';
        $content = '<article class="latestPostWidget">';
        $content .= '<a href="' . get_permalink($post['ID']) . '" title="' . get_the_title($post['ID']) . '" id="featured-thumbnail" class="post-image post-image-left ' . $size . '">';
        $content .= '<div class="featured-thumbnail">';
        
        if (has_post_thumbnail($post['ID'])) {
            // the_post_thumbnail( $size, array( 'title' => '' ) );
            $content .= get_the_post_thumbnail($post['ID'], $size);
        } else {
            $content .= '<img src="' . plugin_dir_url( __FILE__ ) . 'img/nothumb-' . $size . '.png" alt="' . __('No Preview', 'fresh') . '"  class="wp-post-image" />';
        }
        $content .= '</div></a><div class="wrapper"><header><div class="post-info">';
        if ($show_date) {
            $content .= sprintf('<span class="thetime date updated">' . $date_text . ' <span>%1$s</span></span>', get_the_time(get_option('date_format')));
        }

        if ($show_category) {
            $content .= sprintf('<span class="thecategory">' . $category_text . ' %1$s </span>', lpw_get_the_category(', ', $post['ID']));
        }
        $content .= '</div><h2 class="title front-view-title">
                        <a href="' . get_permalink($post['ID']) . '" title="' . get_the_title($post['ID']) . '">' . get_the_title($post['ID']) . '</a>
                    </h2></header></div></article>';
        return $content;
    endforeach;
    wp_reset_query();
}