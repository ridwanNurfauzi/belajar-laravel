<?php

\Html::macro('smartNav', function ($url, $title) {
    $class = $url == request()->url() ? 'active' : '';
    return "<li class=\"nav-item\"><a class=\"nav-link $class\" href=\"$url\">$title</a></li>";
});
