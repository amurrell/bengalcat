# BengalCat.io - Installation

This is PHP. So, it's a pain - and the learning curve can be annoying. So bare with me.

You should probably know how to setup a web server or be using a pretty generic pre-built one.

Requirements:

    - There aren't any if you want to write your own code / edit mine, which...
            - ... I really encourage that you hack my code up
            - please fork this project!
    - You should have a local web server, because without URL rewriting, this framework dies.
    - If using apache you shouldn't have to write the rewrites, there's an htaccess file.
    
Ok, tested with:

    - Apache2
    - PHP 7.0
    - Uses PDO for Db Class
        - which assumes mysql right now

## Web Server Setup

1. Be sure to point the document root to the `html` folder of the repo.
1. Adjust any configuration environmental variables.

### APACHE2

Should be able to use .htaccess file included in the repo. This file routes
everything through index.php, adds trailing slashes, and redirects index.php to /

1. In your configuration, be sure to `AllowOverride all`.
1. Enable the rewrite module: `a2enmod rewrite` and `service apache2 reload`

#### Mods / Packages

1. You will need PDO in your ini and other mysql related ini files

### NGINX

Should push all non-files and all *.php files through index.php

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

More help coming soon...


---

## Node Setup

You do not have to utilize build. Edit your `html/src/templates/head.php` to
alter the style sheet used to `/assets/css/style.css`.

### If you want to use gulp...

1. Edit your `html/src/templates/head.php` to alter the style sheet used to
 `/assets/build/main.css`.

Install NodeJS, npm etc - fastest most consistent way I've ever found:

1. Download **binaries** appropriate for your computer, ie mac etc from: https://nodejs.org/en/download/
1. `cd /usr/local`
1. `tar --strip-components 1 -xzf /path/to/downloaded/tar/your-zip.tar.gz`

Then go to `html/assets` and run `npm install`

Also had to run `npm install --global gulp-cli` to be able to use `gulp`

Additionally, on linux, was running into issues when trying to `gulp watch`
which was solved by:

    `echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf && sudo sysctl -p`

