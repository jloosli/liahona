<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
  <div class="notice">
    <p class="bottom"><?php _e('Sorry, no results were found.', 'roots'); ?></p>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
  <?php roots_post_before(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php roots_post_inside_before(); ?>
        <?php get_thumbnail_link('thumbnail',true); ?>
      <header>
        <h2><?php the_title(); ?></h2>
        <?php roots_entry_meta(); ?>
      </header>
      <div class="entry-content">

      <?php the_content(); ?>
      </div>
      <footer>
        <?php $tag = get_the_tags(); if (!$tag) { } else { ?><p><?php the_tags(); ?></p><?php } ?>
      </footer>
    <?php roots_post_inside_after(); ?>
    </article>
  <?php roots_post_after(); ?>
<?php endwhile; // End the loop ?>

<?php /* Display navigation to next/previous pages when applicable */ global $hide_pagination  ?>
<?php if ( !$hide_pagination && ($wp_query->max_num_pages > 1)) : ?>
  <nav id="post-nav">
    <div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'roots' ) ); ?></div>
    <div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'roots' ) ); ?></div>
  </nav>
<?php endif; ?>
