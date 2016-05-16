// Root path to assets.
var ROOT = './';

// Path to build compiled assets to.
var DEST = './build/';

// Build
var paths = {};

/**
 * Clean
 *
 * Folders to remove before doing a full production build.
 */
paths.clean = DEST;

/**
 * Styles
 *
 * Source SASS files, output to `DEST` folder
 */
var CSS = ROOT + 'css/';
var SASS = ROOT + 'sass/';

paths.styles = {
  src:   SASS + 'main.scss',
  dest:  DEST + 'css',
  watch: SASS + '**/*.scss',
};

paths.cssToSass = {
  src:   CSS + '**/*.css',
  dest: SASS + 'css/',
};

/**
 * Scripts
 *
 * Source JavaScript files, output minified to `DEST` folder
 */
var JS = ROOT + 'js/';
var JS_EX = '!' + JS;

paths.scripts = {
  src: [
    JS + '**/*.js',
  ],
  dest: DEST + 'js/',
};

/**
 * JS Hint
 *
 * Lint our source JavaScript files and Gulp files.
 * Exclude files to not be linted with the `JS_EX` path.
 */
paths.jshint = {
  src: [
    JS + '**/*.js',
    JS_EX + 'vendor/**/*.js',
  ],
  gulp: [
    './gulpfile.js',
    './gulp/**/*.js',
  ]
};

/**
 * JavaScript Bundles
 *
 * Add more properties to the `paths.bundles` object with
 * arrays of file names. Each group will be built into a
 * bundle file named: `{property name}.bundle.js`
 * e.g. `main.bundle.js`
 */
paths.bundles = {
  'main': [
//    JS + 'vendor/initr.js',
//    JS + 'initr.config.js',
  ]
};

/**
 * Exclude main bundle files from being minified
 *
 * They are loaded in a minified bundle, no need to build them otherwise.
 */
var exMainBundles = paths.bundles.main.map(function( src ) {
  return src.replace(JS, JS_EX);
});

paths.scripts.src = paths.scripts.src.concat(exMainBundles);

/**
 * Copy
 *
 * Copy JavaScript files from one place (usually `bower_components`)
 * into the `js/vendor` folder.
 */
paths.copy = {
  'copy-fonts': {
    src:  ROOT + 'fonts/**/*',
    dest: DEST + 'fonts'
  }
};

/**
 * Images
 *
 * Compress images and output to `build/images` folder.
 */
paths.images = {
  src:  ROOT + 'img/**/*',
  dest: DEST + 'img'
};

/**
 * File Size
 *
 * Show size of files gzipped and not.
 */
paths.fileSize = [
  paths.styles.dest  + '*',
  paths.scripts.dest + 'main.bundle.js'
];

/**
 * Docs
 *
 * Build JavaScript documentation with JSDocs.
 */
paths.docs = {
  dest : 'docs',
  js: [
    JS + '**/*.js',
    JS_EX + 'vendor/**/*.js',
    'README.md'
  ]
};
paths.docs.js = paths.docs.js.concat(paths.jshint.gulp);

module.exports = paths;
