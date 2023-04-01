<?php get_header();
get_sidebar();?>
<div class="site-content">
    <?php if (have_posts()): ?>
    <!-- Початок циклу WordPress -->
    <?php while (have_posts()): the_post();?>
	    <?php get_template_part('template-parts/content/content');?>
	    <?php endwhile;?>
    <!-- Кінець циклу WordPress -->
    <?php if ($wp_query->max_num_pages > 1): ?>
    <nav id="nav-below">
        <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav"><</span> Попередній запис'));?>
        </div>
        <div class="nav-next"><?php previous_posts_link(__('наступний запис <span class="meta-nav">></span>'));?>
        </div>
    </nav><!-- #nav-below .navigation -->
    <?php endif;?>
    <!-- Записів для відображення немає, тоді виводимо повідомлення про це -->
    <?php else: ?>
    <?php get_template_part('template-parts/content/content', 'none');?>
    <!--.not-found -->
    <?php endif; // кінець have_posts() перевірки?>
</div><!-- .site-content -->
<?php get_footer();?>