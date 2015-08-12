<?php

// Move all "advanced" metaboxes above the default editor
add_action('edit_form_after_title', function() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
});

function film_mbs() {
    // Screening informations
    add_meta_box( 'film_screening', 'Informations Seance', 'film_screening_display_mb', 'post', 'advanced', 'high' );
    // Movie informations
    add_meta_box('film_meta', 'Informations du film', 'film_meta_display_mb', 'post', 'advanced', 'high' );
}
add_action( 'admin_init', 'film_mbs' );


/*************************************************/
/* Time and Place metabox, code by Kevin Chard, adapted for usage   */
/* http://wpsnipp.com/index.php/functions-php/start-date-end-date-metabox-for-events-custom-post-types/# */
/*************************************************/

function film_screening_display_mb($post, $args) {

    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'filmscreening_nonce' );

    $metabox_id = 'filmscreening';
    $screening_values = get_post_meta($post->ID, $metabox_id, true);

    film_screening_date_display($post, array('id'=> $metabox_id, 'screening_values' => $screening_values));
    echo '<br />';
    film_screening_place_display($post, array('id'=> $metabox_id));
    echo '<br />';

    $value_fee = (isset($screening_values['fee'])) ? $screening_values['fee'] : "";
    
    echo '<label for="'. $metabox_id . '[fee]' . '">Tarifs : </label>';    
    echo '<input type="text' . '" name="' . $metabox_id . '[fee]'
                             . '" value="' . $value_fee
                             . '" size="' . '30'
                             . '"/>';    
}

// Metabox HTML

function film_screening_date_display($post, $args) {
    $metabox_id = $args['id'];
    global $post, $wp_locale;

    $time_adj = current_time( 'timestamp' );
    $date = $args['screening_values'];

    $day = (isset($date['day'])) ? $date['day'] : "";

    if ( empty( $day ) ) {
        $day = gmdate( 'd', $time_adj );
    }

    $month = (isset($date['month'])) ? $date['month'] : "";

    if ( empty( $month ) ) {
        $month = gmdate( 'm', $time_adj );
    }

    $year = (isset($date['year'])) ? $date['year'] : "";

    if ( empty( $year ) ) {
        $year = gmdate( 'Y', $time_adj );
    }

    $hour = (isset($date['hour'])) ? $date['hour'] : "";

    if ( empty($hour) ) {
        $hour = gmdate( 'H', $time_adj );
    }

    $min = (isset($date['minute'])) ? $date['minute'] : "";

    if ( empty($min) ) {
        $min = '00';
    }

    $month_s = '<select name="' . $metabox_id . '[month]">';
    for ( $i = 1; $i < 13; $i = $i +1 ) {
        $month_s .= "\t\t\t" . '<option value="' . zeroise( $i, 2 ) . '"';
        if ( $i == $month )
            $month_s .= ' selected="selected"';
        $month_s .= '>' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
    }
    $month_s .= '</select>';

    echo '<label for="'. $metabox_id .'">Date et heure : </label>';
    echo '<input type="text" name="' . $metabox_id . '[day]" value="' . $day  . '" size="2" maxlength="2" />';
    echo $month_s;
    echo '<input type="text" name="' . $metabox_id . '[year]" value="' . $year . '" size="4" maxlength="4" /> @ ';
    echo '<input type="text" name="' . $metabox_id . '[hour]" value="' . $hour . '" size="2" maxlength="2"/>:';
    echo '<input type="text" name="' . $metabox_id . '[minute]" value="' . $min . '" size="2" maxlength="2" />';

}

function film_screening_place_display($post, $args) {
    $metabox_id = $args['id'];
    global $post;

    $filmscreen = get_post_meta( $post->ID, $metabox_id, true );
    $event_location = isset($filmscreen['place']) ? $filmscreen['place'] : "";

    echo '<label for="' . $metabox_id . '[place]'  . '">Location : </label>';
    echo '<input type="text" name="' . $metabox_id . '[place]' . '" value="' . $event_location  . '" />';
}

// Save the Screening Metabox Data

function film_screening_save_mb( $post_id, $post ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( !isset( $_POST['filmscreening_nonce'] ) )
        return;

    if ( !wp_verify_nonce( $_POST['filmscreening_nonce'], plugin_basename( __FILE__ ) ) )
        return;

    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ) )
        return;

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though

    $metabox_id = 'filmscreening';
    $filmscreen = $_POST[$metabox_id];

    if ($filmscreen['hour']<10){
        $filmscreen['hour'] = '0' . $filmscreen['hour'];
    }
                
    $timestamp = $filmscreen['year']
               . $filmscreen['month']
               . $filmscreen['day']
               . $filmscreen['hour']
               . $filmscreen['day'];

    $filmscreen['timestamp'] = $timestamp;

    // Add values of $events_meta as custom fields

    update_post_meta( $post->ID, $metabox_id, $filmscreen );
}
add_action( 'save_post', 'film_screening_save_mb', 10, 2 );

/**
 * Helpers to display the date on the front end
 */

// Get the Month Abbreviation

function eventposttype_get_the_month_abbr($month) {
    global $wp_locale;
    for ( $i = 1; $i < 13; $i = $i +1 ) {
        if ( $i == $month )
            $monthabbr = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
    }
    return $monthabbr;
}

// Display the date

// function eventposttype_get_the_event_date() {
//     global $post;
//     $eventdate = '';
//     $month = get_post_meta($post->ID, '_month', true);
//     $eventdate = eventposttype_get_the_month_abbr($month);
//     $eventdate .= ' ' . get_post_meta($post->ID, '_day', true) . ',';
//     $eventdate .= ' ' . get_post_meta($post->ID, '_year', true);
//     $eventdate .= ' at ' . get_post_meta($post->ID, '_hour', true);
//     $eventdate .= ':' . get_post_meta($post->ID, '_minute', true);
//     echo $eventdate;
// }

// Echo an input
function build_input_text($post, $name, $placeholder, $size="")
{
    return '<input type="text" name="'.$name.'" value="'
        . get_post_meta($post->ID, $name, true)
        .'" placeholder="'.$placeholder.'" '
        .'size="'.$size.'"'                              
        . '/>';
}

function film_meta_display_mb($post, $arg)
{
    // wp-nonce
    // TODO: better wp_nonce
    wp_nonce_field('filmmeta-nonce','filmmeta-nonce');

    $id_base = 'film_meta';
    
    // Identifiers
    $identifiers = array(
        'year' => $id_base . '[year]',
        'director' => $id_base . '[director]',
        'actoresses' => $id_base . '[actoresses]',
        'countries' => $id_base . '[countries]',
        'duration' => $id_base . '[duration]',
        'colors' => $id_base . '[colors]'
    );
    
    // Values
    $all_values = get_post_meta($post->ID, $id_base, true);
    $values = array(
        'year' => (isset($all_values['year'])) ? $all_values['year'] : '',
        'director' => (isset($all_values['director'])) ? $all_values['director'] : '',
        'actoresses' => (isset($all_values['actoresses'])) ? $all_values['actoresses'] : '',
        'countries' => (isset($all_values['countries'])) ? $all_values['countries'] : '',
        'duration' => (isset($all_values['duration'])) ? $all_values['duration'] : '',
        'colors' => (isset($all_values['colors'])) ? $all_values['duration'] : ''
    );
    
    // Placeholders
    $labels = array(
        'year' => "Annee",
        'director' => "Realisateur/trice(s)",
        'actoresses' => 'Acteurs et Actrices',
        'countries' => 'Pays',
        'duration' => 'Duree',
        'colors' => 'Couleurs ou Noir et Blanc'
    );

    // Sizes
    $sizes = array(
        'year' => "4",
        'director' => "50",
        'actoresses' => '75',
        'countries' => '50',
        'duration' => '4',
        'colors' => '50'
    );

    // Duration
    echo '<label for="' . $identifiers['duration']  . '">' . $labels['duration']  . ' : </label>';
    echo '<input type="text' . '" name="' . $identifiers['duration']
                             . '" value="' . $values['duration']
                             . '" size="' . $sizes['duration']
                             . '" maxsize="' . $sizes['duration']
                             . '"/>min';
    echo '<br />';

    // Director
    
    echo '<label for="' . $identifiers['director']  . '">' . $labels['director']  . ' : </label>';
    echo '<input type="text' . '" name="' . $identifiers['director']
                             . '" value="' . $values['director']
                             . '" size="' . $sizes['director']
                             . '"/>';
    echo '<br />';
    
    // Actors and actresses
    
    echo '<label for="' . $identifiers['actoresses']  . '">' . $labels['actoresses'] . ' : </label>';
    echo '<input type="text' . '" name="' . $identifiers['actoresses']
                             . '" value="' . $values['actoresses']
                             . '" size="' . $sizes['actoresses']
                             . '"/>';
    echo '<br />';

    // Year
    echo '<label for="' . $identifiers['year']  . '">' . $labels['year']  . ' : </label>';
    echo '<input type="text' . '" name="' . $identifiers['year']
                             . '" value="' . $values['year']
                             . '" size="' . $sizes['year']
                             . '" maxsize="' . $sizes['year']
                             . '"/>';
    echo '<br />';
    
    // Countries
    
    echo '<label for="' . $identifiers['countries']  . '">' . $labels['countries']  . ' : </label>';
    echo '<input type="text' . '" name="' . $identifiers['countries']
                             . '" value="' . $values['countries']
                             . '" size="' . $sizes['countries']
                             . '"/>';
    echo '<br />';
    
    // Colors or Black and White
    echo '<label for="' . $identifiers['colors']  . '">' . $labels['colors']  . ' : </label>';
    echo '<input type="text' . '" name="' . $identifiers['colors']
                             . '" value="' . $values['colors']
                             . '" size="' . $sizes['colors']
                             . '"/>';
    echo '<br />';
}

    
    // Poster
    // Trailer iframe

    // Synopsis
    // Introduction text

?>