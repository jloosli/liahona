<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
  <?php roots_post_before(); ?>
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
    <?php roots_post_inside_before(); ?>
      <header>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php roots_entry_meta(); ?>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
          <span class='st_facebook_large'></span>
          <span class='st_twitter_large'></span>
          <span class='st_email_large'></span>
          <span class='st_plusone_large'></span>
          <span class='st_sharethis_large'></span>
          <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
          <?php if(in_category(5)) get_template_part('authorbio'); ?>
      </div>
      <footer>
        <?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>' )); ?>
        <p><?php the_tags(); ?></p>
      </footer>
      <?php comments_template(); ?>
      <?php roots_post_inside_after(); ?>
    </article>
  <?php roots_post_after(); ?>
<?php endwhile; // End the loop ?>
