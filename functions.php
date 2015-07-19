<?php

include "count-post-category.php";
add_action('pre_get_posts', 'diff_post_count_per_cat');

function diff_post_count_per_cat() {
    if (is_admin()) return;
    
    include 'count-post-category.php';

    $cat = get_query_var('category_name');
    switch ($cat) {
        case 'partenariats':
            set_query_var('posts_per_page',$partenariats);
            break;
        case 'cannes-2014':
            set_query_var('posts_per_page',$cannes2014);
            break;
        case 'critiques':
            set_query_var('posts_per_page',$critiques);
            break;
        case 'analyses':
            set_query_var('posts_per_page',$analyses);
            break;
        case 'seances':
            set_query_var('posts_per_page',$seances);
            break;
    }
}

function get_categories_dropdown() {
  $return_text="<div class=\"nav-dropdown-categories\"><center><h3>Archives des catégories de l'article</h3></center><br />";
 
  $post_categories = wp_get_post_categories( get_the_ID() );
      
  foreach($post_categories as $cat){
			 
 $my_query = new WP_Query( 'cat=' . $cat . '&posts_per_page=-1' );
 $return_text.="<h4><a href=\"".get_category_link($cat)."\">".get_cat_name($cat)."</a></h4><br />";
 $return_text.="<div class=\"archive-dropdown-container\"><select name=\"archive-dropdown\" onchange='document.location.href=value;'>";

	while ( $my_query->have_posts() ) : $my_query->the_post();
     	  $the_permalink=get_the_permalink();
	  $the_title=get_the_title();
	  $return_text .="<option value=\"$the_permalink\">$the_title</option>";
	endwhile;
      $return_text.="</select></div><!-- .archive-dropdown-container --><br />";
      wp_reset_postdata();

  }  

  $return_text.="</div><!-- .nav-dropdown-categories -->";
  echo $return_text;
}
/*
function get_nav_single() {
				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title', TRUE); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>', TRUE ); ?></span>


				</nav><!-- .nav-single -->
}
*/
?>