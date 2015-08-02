<?php

// Move all "advanced" metaboxes above the default editor
add_action('edit_form_after_title', function() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
});

// 
function cinemeta_call_mb($post_type, $post)
{
    add_meta_box(
        'Cine Meta',
        __('Cineclub', 'cinemeta'),
        'cinemeta_display_mb',
        'post',
        'advanced',
        'high'
    );
}
add_action('add_meta_boxes', 'cinemeta_call_mb', 10, 2);

function cinemeta_display_mb($post, $arg)
{
    echo '<strong>LALALALA</strong>';
}

?>