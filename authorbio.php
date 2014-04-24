<?php
$curauth = get_the_author()?get_userdata(get_the_author_meta("ID")):((get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author')));
?>
<div class="author-bio <?php if(is_single()) echo "minimized";?> ">
        <?php echo get_avatar( $curauth->user_email, $size = '96'); ?>
        <h3>About the Author: <a rel="author" href="<?php echo get_author_posts_url( $curauth->ID ); ?>" title="Posts by <?php echo $curauth->display_name; ?>"><?php echo $curauth->display_name; ?></a>
        </h3>
        <div class="full-bio">
        <?php echo $curauth->user_description==""?"This author's bio coming soon...":$curauth->user_description; ?>
        </div>
        <div class="rolltext">
            Roll over to see full bio.
        </div>
</div>
<script >
(function($) {
    var bio = $('.author-bio.minimized');
    bio.hover(function() {
        bio.attr('style', ''); // fixes issues caused by .stop()
        bio.stop().removeClass('minimized', 'slow');
    }, function() {
        bio.attr('style', ''); // fixes issues caused by .stop()
        bio.stop().addClass('minimized', 'slow');
    });

})(jQuery);
</script>
