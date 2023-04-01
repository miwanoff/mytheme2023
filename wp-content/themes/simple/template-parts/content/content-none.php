<article class="not-found">
    <?php if (current_user_can('edit_posts')): ?>
    <header class="entry-header">
        <h1 class="entry-title"><?php _e('Немає записів для відображення.', '');?></h1>
    </header>
    <div class="entry-content">
        <p><?php printf(__('Готові опублікувати свій перший запис? <a href="%s">Тоді перейдіть за цим посиланням.</a>.'), admin_url('post-new.php'));?>
        </p>
    </div><!-- .entry-content -->
    <?php else: ?>
    <header class="entry-header">
        <h1 class="entry-title"><?php _e('Нічого не знайдено');?></h1>
    </header>
    <div class="entry-content">
        <p><?php _e('Нічого не знайдено, скористайтеся пошуком.');?></p>
        <?php get_search_form();?>
    </div><!-- .entry-content -->
    <?php endif;?>
</article>