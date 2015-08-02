<?php

// Move all "advanced" metaboxes above the default editor
add_action('edit_form_after_title', function() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
});

/* Film informations Metabox  */
function filmmeta_call_mb($post_type, $post)
{
    add_meta_box(
        'film-meta',
        __('Informations du film', 'filmmeta'),
        'filmmeta_display_mb',
        'post',
        'advanced',
        'high'
    );
}
add_action('add_meta_boxes', 'filmmeta_call_mb', 10, 2);

function filmmeta_display_mb($post, $arg)
{
    // wp-nonce
    wp-nonce-field('filmmeta-nonce','filmmeta-nonce')
        
    // Title
    // Screening date

    // Year
    // Director
    // Countries
    // Duration
    // Actors and actresses
    // Colors or Black and White
    
    // Poster
    // Trailer iframe

    // Synopsis
    // Introduction text
}


/* Ticket Fee  */
function filmfee_call_mb($post_type, $post)
{
    add_meta_box(
        'film-fee',
        __('Tarifs', 'filmmeta'),
        'filmfee_display_mb',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'filmmeta_call_mb', 10, 2);
    
function filmfee_display_mb($post, $args)
{
    // wp-nonce
    wp-nonce-field('filmfee-nonce','filmfee-nonce')
        
    // Fees
    // COF
    // Normal

    // long term COF
    // long term normal
    
}


?>