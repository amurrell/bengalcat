# BengalCat

Very little abstraction. No community. Hardly any docs.

_On purpose_...

Camouflaged for the Jungle; Indifferent like a Cat.

## Who is this for?

This minimal framework is basically Controller|View - no models.

- For people who are not always working with a database.
- Or people who like to work with a database without an ORM.
- Or people who want a framework, but a really simple one - that they can hack.
- Or people who need to prototype a proof of concept quickly.

The idea is the framework (outside of server side setup) should only take a few minutes to get the developer coding.

And furthermore, **that the learning curve should be about 10 minutes**.

More ideas of what you're doing with BengalCat:

- Maybe you are using it to write some worker scripts you'll never touch after your task is complete, like a personal sandbox.
- Or you are writing a quick API and need _something_ without a lot of overhead. (You need to write your own api auth!)
- Or working with multiple databases and despise ORMs for that.

---

### Install

- Read the `INSTALL.md` and ensure you have a working webserver with PHP
    - Install also goes over NodeJS, npm if you want to use it.

### Setup Routes

- Copy `app\config\routes-sample.php` to `app\config\routes.php`;
        - You can view the home page now and you'll see the install template if successful.

### Make a Page! Ex. Home Page

1. In `app\config\routes.php`, Change `Installed` to `Home` in the line with the root route

- `'/' => '\Bc\App\Classes\Installed',` becomes `'/' => '\Bc\App\Classes\Home',`

1. Make `app/classes/Home.php` with following:

    ```
    <?php

    namespace Bc\App\Classes;

    class Home extends \Bc\App\RouteClass {

        protected function init()
        {
            $this->render(SRC_DIR . 'home.php');
        }
    }
    ```


1. Edit the `home.php` in `html/src/main/home.php`


### Head, Header, Footer templates

You can modify your head, header, footer templates in the `html/src/templates/` directory.

Cool notes:

- You can make alternative heads, headers, footers per class in the init() method

### Front-end Fun - Gulp or not to Gulp

The project defaults to using compiled css from sass and compiled JavaScript. 

- The complied output folder is in version control: `html/assets/build/`
- You can edit your `head.php` in the templates folder to use whatever css/js sources you want though.
- Or, you can teach yourself gulp, or to get started...
    - read the `INSTALL.md` about NodeJS, npm etc
    - in the terminal, `cd html/assets/`
        - `gulp watch` This will watch your changes to sass files
            - ex. edit `html/assets/sass/base/_base.scss` with something obvious and watch your terminal watching changes. Refresh your browser.
        - `gulp` will run all the tasks defined in the `gulpfile.js`
    - You need to be a little bit of a self-starter and learn this stuff on your own if you want to know more about gulp! Google is your friend.

---


### Explanations for those who like them

The site is based on routes (many PHP frameworks do this ie Laravel, Symfony ...).

- You edit routes in `app/config/routes.php`
- You associate a pattern in the associative array **key** with the full namespace + class name to handle the route
    - This namespace + class is essentially, your controller.
    - The patterns are either **exact match** or they can have a regex pattern that must have a capturing group using `()`
        - `/articles/([^/]*)/` - This pattern will match any number of characters between two /s but cannot match a /
            - This would be useful for something like /articles/an-amazing-article/ where `an-amazing-article` will be the variant you can use in your controller.
        


#### Route Classes (found in `app/classes/`) extend abstract RouteClass

An abstract class `RouteClass` implements some functions useful for your route classes to inherit, like render.

But,

- You can "override" the render method by writing your own in your route class.


#### Abstract function init() - The most important method!

An abstract class can also require that classes extending it **must** 
implement any abstract method described in it's class declaration.

So that means classes extending RouteClass must have a method `init()`.

- The `init()` method can most easily simply `$this->render($PATH_TO_TEMPLATE);`
- But! You can do a few other things...
    1. You can setHead, setHeader, setFooter to different templates other than the defaults in the `abstract RouteClass`
    2. Or you can call any other custom methods you write to determine logic, grab data from the database and choose to return that without calling render.
    3. Or you can do the above, but also write your own render method to return perhaps a json encoded array.

    ```
    <?php

        namespace Bc\App\Classes;

        class Articles extends \Bc\App\RouteClass {

            protected function init()
            {
                $this->setHead(SRC_DIR . 'templates/head-articles.php');
                $this->setHeader(SRC_DIR . 'templates/header-minimal.php');
                $this->setFooter(SRC_DIR . 'templates/footer-with-cta.php');
                $this->render(SRC_DIR . 'articles.php');
            }
        }
    ```

#### More Route Class Methods

A few other useful methods on the Route Class are worth mentioning:

- **getVariant()** - Returns the variant from the request in a route like `'/articles/([^/]*)/' => '\Bc\App\Classes\ArticlesVar'`
    - ex. /articles/**an-amazing-article**/
- **getMethod()** - Returns the Request Method ie GET, POST, PATCH, DELETE, PUT
- **getQueryVars()** - Returns an array of all the query string, var => value


---

### More Explanations

For the people who REALLY like reading. 

** Note ** ALL directory path's (referred to in the dir constants below) include your `your/projectroot/` in them!

#### Index.php hardly doesn't much, but so important.

index.php autoloads all your classes, but only classes in `app` and `app/classes`. This is really the only place classes should go.

- **APP_DIR** constant is defined in index.php, too. Points to the path of the `app/` directory.

#### The core is only one class

LOL. I know. But really, this is nice. Don't laugh. There's not one god damn interface in this project (yet).

The `Bc` (BengalCat) class makes up the core by determining the correct route for your controllers.

- The core loads a few constants
    - **INDEX_DIR** constant which points to `html/` folder (where index.php is..)
    - **CLASSES_DIR** constant which points to `app/classes/`
    - **SRC_DIR** constant which points to `html/src/`
    - **ASSETS_DIR** constant which points to `html/assets/`

- The core also makes a new Util
    - All methods on Util are static. In your files at the top `use \Bc\App\Util;` and then you can call Util::anyMethod()
    - Util has several useful methods!
        - **makeCurlCall()** - makes a curl call pretty simple.
        - **var_print** - like var_dump, but prettier (use for debugging)
        - **slugify** - makes spaced phrases into slugs (ie Something Cool -> something-cool)
        - **getTemplateContents** - uses output buffering to take the outputted contents of a php file and stores it into a variable.
        - **trigger404()** - call this whenever you need to 404, it will throw the error, include the 404 page and exit.
            - You should design the 404.php page in `html/src/`
        - **triggerError()** - pass an array like:
            
    ```
    array(
        'success' => false,
        'error_code' => 502,
        'message' => 'This timeout error will never happen'
    );
    ```