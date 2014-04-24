<?php /* Start loop */
$featured_query = new WP_Query('meta_key=_liahona_featured&post_type=any&posts_per_page=1');
while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
  <?php roots_post_before(); ?>
    <article <?php post_class('featured-post') ?> id="post-<?php the_ID(); ?>">
    <?php roots_post_inside_before(); ?>
        <a href="http://r-word.org">
            <img width="288" height="200" src="/assets/respect2-288x200.jpg"
                 class="attachment-featured wp-post-image" alt="Click here to take the R-word Pledge"
                 title="Click here to take the R-word Pledge">
        </a>
        <?php /* get_thumbnail_link('featured',true); hardcode the image and link instead */ ?>
      <header>
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php roots_entry_meta(); ?>
      </header>
      <div class="entry-content">
        <?php the_excerpt(); ?>
      </div>
      <footer>
          <a href="http://theliahonaproject.net/join-the-conversation/">
              <img src="http://theliahonaproject.net/assets/FirstVisit.png" width="100%" />
          </a>
        <?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>' )); ?>
      </footer>
      <?php comments_template(); ?>
      <?php roots_post_inside_after(); ?>
    </article>
  <?php roots_post_after(); ?>
<?php endwhile; // End the loop
?>
