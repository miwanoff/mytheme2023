<?php
/**
 * Template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Simple
 * @since Simple 1.0
 */
get_header();
get_sidebar();?>
<div class="site-content">
    <header class="entry-header">
        <h1 class="entry-title">
            <?php echo "Помилка 404"; ?>
        </h1>
    </header>
    <div class="entry-content">
        <p><?php echo 'Вибачте, такої сторінки не існує.'; ?></p>
    </div>
</div>
</div> <?php get_footer();?>