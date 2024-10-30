<?php
// function to get the category of the posts
function lpw_get_the_category($separator = ', ', $post_ID)
{
    $categories = get_the_category($post_ID);

    if (empty($categories)) {
        return;
    }

    global $wp_rewrite;

    $links = array();
    $rel   = (is_object($wp_rewrite) && $wp_rewrite->using_permalinks()) ? 'rel="category tag"' : 'rel="category"';

    foreach ($categories as $category) {

        $links[] = sprintf(
            '<a href="%1$s" title="%2$s" %3$s>%4$s</a>',
            esc_url(get_category_link($category->term_id)),
            // translators: category name.
            sprintf(esc_html__('View all posts in %s', 'fresh'), esc_attr($category->name)),
            $rel,
            esc_html($category->name)
        );
    }

    return join($separator, $links);
}