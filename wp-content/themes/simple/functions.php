<?php
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
}

// begin widget setting
if (function_exists('register_sidebars')) {
    register_sidebar(array(
        'name' => 'Sidebar Area',
        'before_widget' => '<div id="%1$s" class="backgroundlist %2$s"><div class="listtitle">',
        'after_widget' => '</div></div>',
        'before_title' => '<h2>',
        'after_title' => '</h2></div><div class="contentbox">',
    ));
}

// begin widget setting
if (function_exists('register_sidebars')) {
    register_sidebar(array(
        'name' => 'Sidebar Area 1',
        'before_widget' => '<div id="%1$s" class="backgroundlist %2$s"><div class="listtitle">',
        'after_widget' => '</div></div>',
        'before_title' => '<h2>',
        'after_title' => '</h2></div><div class="contentbox">',
    ));
}
