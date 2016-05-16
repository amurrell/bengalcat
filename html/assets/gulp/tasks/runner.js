var gulp = require('gulp');
var $ = {
  size: require('gulp-size'),
};

var paths = require('../paths');

/**
 * Development Task Runner
 *
 * Lint all JavaScript and build SASS.
 */
gulp.task('dev', ['jshint-gulp', 'jshint', 'styles'], function() {
  console.timeEnd('Gulp Run Time');
});

/**
 * Production Task Runner
 *
 * Remove `build` folder for a fresh, full build.
 * Lint/concatinate/minify/bundle JavaScript and build SASS.
 */

// Show file sizes gzipped and not.
var showFileSize = function() {
  return gulp.src(paths.fileSize)
    .pipe($.size({showFiles: true}))
    .pipe($.size({gzip: true, showFiles: true}));
};

// Core tasks to run
var core = function() {
  gulp.start('jshint-gulp', 'scripts', 'bundles', 'styles', 'images', function() {
    console.timeEnd('Gulp Run Time');
    return showFileSize();
  });
};

// Clean built files to get a full, fresh build of everything.
gulp.task('prod', ['clean'], function() {

  // Copy over files that will be needed in the build, then run core tasks.
  gulp.start('copy', core);
});

/**
 * Default Task Runner
 *
 * Uses the Production Task Runner.
 */
gulp.task('default', ['prod']);
