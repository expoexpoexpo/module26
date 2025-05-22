<?php

$html = file_get_contents('index.html'); 

preg_match_all('/<meta\b[^>]*>/i', $html, $matches);

$metaTags = $matches[0];

$namesToRemove = ['title', 'description', 'keywords'];
$removedTags = [];

foreach ($metaTags as $metaTag) {

    foreach ($namesToRemove as $name) {
        if (preg_match('/name=["\']?' . preg_quote($name, '/') . '["\']?/i', $metaTag)) {

            $removedTags[] = $metaTag;

            $html = str_replace($metaTag, '', $html);
            break; 
        }
    }
}


