<?php get_header(); ?>
  <?php roots_content_before(); ?>
    <div id="content" class="<?php echo $roots_options['container_class']; ?>">
    <?php roots_main_before(); ?>
        <?php $main_query= clone $wp_query; ?>
      <div id="main" class="<?php echo $roots_options['main_class']; ?>" role="main">
          <div class="container" id="featured_section">
              <?php roots_loop_before(); ?>
              <?php get_template_part('featured'); ?>
              <?php roots_loop_after(); ?>
          </div>
          <div class="container" id="announcements">
              <?php $hide_pagination=true; wp_reset_query(); query_posts('category_name=announcements&posts_per_page=1'); ?>
              <?php roots_loop_before(); ?>
              <?php get_template_part('loop','full'); ?>
              <?php roots_loop_after(); ?>
          </div>
          <hr />
        <div class="container" id="articles">
            <?php wp_reset_query(); $hide_pagination=true; query_posts(array(
                'category__in'=>array(15),  // Exclude Announcements (5) & News (7)
                'posts_per_page'=>3,
                'paged' => get_query_var('paged') // Make pagination work http://scribu.net/wordpress/wp-pagenavi/right-way-to-use-query_posts.html
        ));  ?>
          <?php roots_loop_before(); ?>
          <?php get_template_part('loop'); ?>
          <?php roots_loop_after();  ?>
        </div>
        <div class="container" id="essays">
	        <h2 class="highlight">Personal Essays</h2>
            <?php wp_reset_query(); $hide_pagination=true; query_posts(array(
                'category__in'=>array(6),  // Exclude Announcements (5) & News (7)
                'posts_per_page'=>10,
                'paged' => get_query_var('paged') // Make pagination work http://scribu.net/wordpress/wp-pagenavi/right-way-to-use-query_posts.html
        ));  ?>
          <?php roots_loop_before(); ?>
          <?php get_template_part('loop'); ?>
          <?php roots_loop_after();  ?>
        </div>
<!--          <div class="container" id="news">-->
<!--              <h2 class="highlight">In the News</h2>-->
<!--              --><?php //wp_reset_query(); $hide_pagination=true; query_posts('category_name=news&posts_per_page=3'); ?>
<!--              --><?php //roots_loop_before(); ?>
<!--              --><?php //get_template_part('loop'); ?>
<!--              --><?php //roots_loop_after(); ?>
<!--          </div>-->
<!--          <div class="container" id="regional_news">-->
<!--              --><?php //wp_reset_query(); $hide_pagination=true; query_posts('p=870&posts_per_page=1'); ?>
<!--              --><?php //roots_loop_before(); ?>
<!--              --><?php //get_template_part('loop','full'); ?>
<!--              --><?php //roots_loop_after(); ?>
<!--          </div>-->
          <?php wp_reset_query(); // leave this in to reset the query and make sure is_home and is_front_page work ?>
      </div><!-- /#main -->
    <?php roots_main_after(); ?>
    <?php roots_sidebar_before(); ?>
      <aside id="sidebar" class="<?php echo $roots_options['sidebar_class']; ?>" role="complementary">
      <?php roots_sidebar_inside_before(); ?>
        <div class="container">
          <?php get_sidebar(); ?>
        </div>
      <?php roots_sidebar_inside_after(); ?>
      </aside><!-- /#sidebar -->
    <?php roots_sidebar_after(); ?>
    </div><!-- /#content -->
  <?php roots_content_after(); ?>
<?php get_footer(); ?>
