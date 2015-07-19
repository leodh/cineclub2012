<?php
/* Template Name: Contact Us */
get_header(); ?>

<div id="primary" class="site-content">
<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php if ( ! is_page_template( 'page-templates/front-page.php' ) ) : ?>
			<?php the_post_thumbnail(); ?>
			<?php endif; ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">

<?php
if(isset($_POST['submit_subscribe']))
{
$flag=1;

if($_POST['email_subscribe']=='')
{
$flag=0;
echo "<font color='#FF0000'>Veuillez fournir une adresse mail.</font><br>";
}
else if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $_POST['email_subscribe']))
{
$flag=0;
echo "<font color='#FF0000'>Veuillez fournir une adresse mail valide.</font><br>";
}
if($flag==1)
{
$ccclipper="cineclub@clipper.ens.fr";
$mail=trim($_POST[email_subscribe]);
$subject="add cineclub-informations@ens.fr ".$mail;

wp_mail($ccclipper, $subject," ") or die ("L'e-mail n'a pas pu être envoyé. Veuillez essayer.");
echo "<font color='#0000A0'>La demande d'inscription a bien été prise en compte.</font>";
}
}

?>

<?php
if(isset($_POST['submit_unsubscribe']))
{
$flag=1;

if($_POST['email_unsubscribe']=='')
{
$flag=0;
echo "<font color='#FF0000'>Veuillez fournir une adresse mail.</font><br>";
}
else if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $_POST['email_unsubscribe']))
{
$flag=0;
echo "<font color='#FF0000'>Veuillez fournir une adresse mail valide.</font><br>";
}
if($flag==1)
{
$ccclipper="cineclub@clipper.ens.fr";
$mail=trim($_POST[email_unsubscribe]);
$subject="del cineclub-informations@ens.fr ".$mail;

wp_mail($ccclipper, $subject," ") or die ("<font color='#FF0000'>L'e-mail n'a pas pu être envoyé. Veuillez essayer.</font>");
echo "<font color='#0000A0'>La demande de désinscription a bien été prise en compte.</font>";
}
}

?>

<?php the_content(); ?>

<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
</div><!-- .entry-content -->
<footer class="entry-meta">
<?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- .entry-meta -->
</article><!-- #post -->


<?php endwhile; // end of the loop. ?>

</div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

