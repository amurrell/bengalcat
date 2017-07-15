var gulp = require('gulp');
var handleErrors = require('../util/handleErrors');
var autoprefixer = require('autoprefixer');
var $ = {
  sass: require('gulp-sass'),
  postcss: require('gulp-postcss'),
  concat: require('gulp-concat'),
  rename: require('gulp-rename'),
};

var styles = require('../paths').styles;
var cssToSass = require('../paths').cssToSass;
var images = require('../paths').images;

var AUTOPREFIXER_OPTIONS = {
  browsers: [
    '> 1%',
    'last 3 version',
    'safari >= 5',
    'ie >= 8',
    'ie_mob >= 10',
    'opera 12.1',
    'ios >= 6',
    'android >= 4',
    'ff >= 30',
    'Firefox ESR',
    'bb >= 10',
  ]
};

var SASS_OPTIONS = {
  // If using Bootstrap Grid, `precision: 10` is required.
  precision: 10,
  imagePath: images.dest,
  includePaths: [
    'bower_components',
  ]
};

// Build SASS, run Autoprefixer on the generated CSS
// then concatinate the css files into one and output.
gulp.task('styles', ['cssToSass'], function() {
  return gulp.src(styles.src)
    .pipe($.sass(SASS_OPTIONS))
    .on('error', handleErrors)
    .pipe($.postcss([ autoprefixer(AUTOPREFIXER_OPTIONS) ]))
    .pipe($.concat('main.css'))
    .pipe(gulp.dest(styles.dest));
});

// Copy all CSS files in CSS folder into `sass/css` folder.
// This way you can import CSS files in your main SASS file.
// e.g. In your `main.scss` file: `@import "css/normalize";`
gulp.task('cssToSass', function() {
  return gulp.src(cssToSass.src)
    .pipe($.rename(function(path) {
      path.basename = '_' + path.basename;
      path.extname = '.scss';
    }))
    .pipe(gulp.dest(cssToSass.dest));
});
