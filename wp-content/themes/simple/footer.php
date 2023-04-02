<div class="clear"></div>
<!-- Підвал сайту -->
<footer id="colophon" class="site-footer">
    <div class="site-info">
        <a href="<?php echo esc_url(__('http://wordpress.org/')); ?>">
            <?php echo 'KIT 2020'; ?>
        </a>
    </div><!-- .site-info -->
    <div class="my-new-sidebar">
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar Area 1")): ?>
        <?php endif;?>
    </div>
    <?php wp_footer();?>
</footer><!-- #colophon -->
</div><!-- #page -->
</body>

</html>