<?php get_template_part( 'qa', 'head' ); ?>

<div id="qa-page-wrapper">

<?php the_qa_menu(); ?>

<div id="edit-question">
<?php the_question_form(); ?>
</div>

</div><!--#qa-page-wrapper-->

<?php get_template_part( 'qa', 'foot' ); ?>