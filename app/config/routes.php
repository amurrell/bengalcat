<?php

return array(
  '/' => '\Bc\App\Classes\Installed',
  '/about/' => '\Bc\App\Classes\About',
  /** @note Great for an archive page, and can make the variant page extend this class */ 
  # '/articles/' => '\Bc\App\Classes\Articles',
    
  /** @note Example of a varying sub page that will probably draw from db */  
  /** @note Use the () to group the variant so you can get the variant. */  
  '/articles/([^/]*)/' => '\Bc\App\Classes\ArticlesVar', 
);