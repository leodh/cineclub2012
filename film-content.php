<?php


function film_title($post_id) {

    // Title
    $title = 'TITLE'; // TODO
    // Director
    $infos = get_post_meta($post_id, 'filmmeta', true);
    $director = $infos['director'];
    // Date
    $screening = get_post_meta($post_id, 'filmscreeninginfos', true);
    $timestamp = $screening['timestamp'];
    
    echo $title . ' de ' . $director . '<br />';
    echo '(' . date_i18n("l j F Y, H:i", $timestamp) . ')';
}

function film_trailer($iframe) {

    echo '<div class="entry-trailer">' . $iframe . '</div>';
}

function film_presentation($presentation) {

    echo '<p>' . $presentation . '</p>';
}

function film_poster($poster_url) {

    echo '<div class="entry-separator">';
    echo '<img src="' . $poster_url
                      . '" alt="" class="entry-poster"/>';
    echo '</div>';
}

function film_infos($infos) {

    $year = $infos['year'];
    $director = $infos['director'];
    $actoresses = $infos['actoresses'];
    $countries = $infos['countries'];
    $duration = $infos['duration'];
    $colors = $infos['colors'];
    $colors_trad = array( 'colors' => 'Couleurs',
                          'blackwhite' => 'Noir et Blanc');
    $format = $infos['format'];
    $format = ($format == 'numeric') ? 'Fichier Numérique' : $format;
    
    echo '<div class="entry-text-info">';
    echo '<b>Durée </b>:' . $duration .'minutes.<br />';
    echo '<b>Réalisation </b>:' . $director . '<br />';
    echo '<b>Avec :</b>' . $actoresses . '<br />';
    echo '<b>Année :</b>' . $year . '<br />';
    echo '<b>Pays :</b>' . $countries . '<br />';
    echo ($colors != '') ? '<b>' . $color_trad[$colors] . '</b>.<br />' : '';
    echo '<b>Format de projection </b>:' . $format . '<br />';
}

function film_synopsis($synopsis) {

    echo '<p>Rapide synopsis : <span class="entry-synopsis">';
    echo $synopsis;
    echo '</span></p>';
}

function film_summary($post_id, $infos, $screening) {
}

function film_content($post_id, $summary) {

    // Get post metadatas
    // Film informations
    $infos = get_post_meta($post_id, 'filmmeta', true);
    // Film Synopsis
    $synopsis = get_post_meta($post_id, 'filmsyno', true);
    // Visuals
    $visuals = get_post_meta($post_id, 'filmvisuals', true);
    $iframe = $visuals['trailer'];
    $poster_url = $visual['poster'];
    // Screening Presentation
    $presentation = get_post_meta($post_id, 'filmscreeningpres', true);
    // Screening infos
    $screening = get_post_meta($post_id, 'filmscreeninginfos', true);
    
    // Print screening and movie informations
    film_trailer($iframe);
    film_presentation($presentation);
    
    echo '<div class="entry-mainblock">';
    film_poster($poster_url);
    film_infos($infos);
    film_synopsis($synopsis);
    echo '</div>';

    echo '<div class="entry-break"></div>';
    // Summary, if required
    $summary ? film_summary($post_id, $infos, $screening) : '';

    echo '<div class="entry-analyse">';
    // the_content = analysis

     echo '</div>'
}

/*


</div>
<p>Comme d'habitude, l'entrée coûte 4€, 3€ pour les membres du COF et vous avez la possibilité d'acheter des cartes de 10 places pour respectivement 30€ et 20€.</p>


*/

?>