</div></div>  <?php roots_footer_before(); ?>
    <footer id="content-info" class="<?php global $roots_options; echo $roots_options['container_class']; ?>" role="contentinfo">
      <?php roots_footer_inside(); ?>
      <div class="container">
        <?php dynamic_sidebar('roots-footer'); ?>
        <p class="copy"><small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
        <p class="reference"><small>This is not an official site of <a href="http://lds.org">The Church of Jesus Christ of Latter Day Saints</a></small></p>
      </div>
    </footer>
    <?php roots_footer_after(); ?>
  </div><!-- /#wrap -->

<?php wp_footer(); ?>
<?php roots_footer(); ?>

  <!--[if lt IE 7]>
    <script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->

</body>
</html>
