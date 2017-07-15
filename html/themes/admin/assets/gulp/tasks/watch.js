var gulp = require('gulp');
// var $ = {
//   livereload: require('gulp-livereload'),
// };

var paths = require('../paths');

// Files to always watch.
function alwaysWatch() {

  // Watch .scss files
  gulp.watch(paths.styles.watch, ['styles']);

  // Watch .css files to copy into `sass` as .scss files
  gulp.watch(paths.cssToSass.src, ['cssToSass']);

  // Watch Gulp files
  gulp.watch(paths.jshint.gulp, ['jshint-gulp']);

  // Watch image files
  gulp.watch(paths.images.src, ['images']);

  // Watch JS files
  gulp.watch(paths.scripts4watch.src, ['bundles', 'scripts']);
}

// Watch Development
gulp.task('watch', function() {
  alwaysWatch();

  // Lint .js files
  gulp.watch(paths.scripts.src, ['jshint']);
});

// Watch Production Build
gulp.task('watch-prod', function() {
  alwaysWatch();

  // Lint and build .js files
  gulp.watch(paths.scripts.src, ['scripts', 'bundles']);
});
