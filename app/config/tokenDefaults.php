<?php

/**
 * Token Defaults.
 *
 * Use for global fall-back when you don't want to customize a token per route,
 * but your tokens are utilized in header, footer, or other frequently used places.
 *
 * Use the token pattern: [bc:token],
 * this will ensure that any un-supplied token values are just blank.
 */

$navUtil = new Bc\App\Utils\NavUtil(); // Edit Nav Utility for Default Nav

return [
    '[bc:slogan]'   => '<div>Camouflaged for the Jungle;</div><div>Indifferent like a Cat.</div>',
    '[bc:nav]'      => $navUtil->getNav(true),
];