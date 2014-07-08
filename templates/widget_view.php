<?php ob_start(); ?>

<h2><?php _e('Frequently Asked Question on', LANG_DOMAIN) ?> <?php echo $cat->name; ?></h2>
<br>
<?php
    $args = array(
        'post_type' => 'faq',
        'faq_categories' => $cat->slug,
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
?>
<div class="smart_widget_accordion accod_parent" id="faq_<?php echo str_replace(' ', '_', $cat->slug); ?>">
    <?php foreach($posts as $post) { ?>
    <div class="smartItems">
        <h3 class="accordion_title"><?php echo $post->post_title; ?></h3>
        <div class="smartItemsDetails">
            <?php echo $post->post_content; ?>
        </div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
    jQuery(function() {
        jQuery('#faq_<?php echo str_replace(' ', '_', $cat->slug); ?>').faqAccordion({
            theme:  '<?php echo $data['theme']; ?>',
            expandAll:  <?php echo $data['expand']; ?>,
            animationSpeed: <?php echo $data['faq_speed']; ?>
        });
    })
</script>
<style type="text/css">
    <?php echo $data['faq_css']; ?>
</style>
<?php $html .= ob_get_clean();