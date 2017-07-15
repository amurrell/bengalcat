<?php

/**
 * Token Defaults.
 * 
 * Version-Controlled default tokens go here.
 * Create tokens.php (is in .gitignore) to add to or override default tokens.
 *
 * ---
 * Use for global fall-back when you don't want to customize a token per route,
 * but your tokens are utilized in header, footer, or other frequently used places.
 *
 * Use the token pattern: [:token],
 * this will ensure that any un-supplied token values are just blank.
 */

return [
    '[:nav]'           => $this->nav->getNav(true),
    '[:jquery script]' => $this->getThemePartContents(
                            '/src/tokenHTML/scripts/jquery.php'
                          ),
    '[:main script]'   => $this->getThemePartContents(
                            '/src/tokenHTML/scripts/main.php'
                          ),
    '[:admin logo name]' => 'Console',
    '[:admin site title]'=> 'Console'
];