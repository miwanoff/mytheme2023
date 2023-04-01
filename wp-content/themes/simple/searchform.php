<form name="search" action="<?php echo home_url('/') ?>" method="get" class="search-form">
    <input type="text" value="<?php echo get_search_query() ?>" name="s" placeholder="<?php echo 'Пошук'; ?>"
        class="input">
    <button type="submit" class="button"><?php echo 'Знайти'; ?></button>
</form>