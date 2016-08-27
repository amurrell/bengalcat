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

return array(
  '[bc:slogan]' => '<div>Camouflaged for the Jungle;</div><div>Indifferent like a Cat.</div>',
);