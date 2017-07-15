var gulp = require('gulp');
var $ = {
  clean: require('gulp-clean'),
  cached: require('gulp-cached'),
  cache: require('gulp-cache'),
};

var clean = require('../paths').clean;

// Remove `build` folder.
gulp.task('clean', ['clear-cache'], function() {
  return gulp.src(clean, {read: false})
    .pipe($.clean());
});

// Clear all caches to allow for full build.
gulp.task('clear-cache', function(done) {
  $.cached.caches = {};
  return $.cache.clearAll(done);
});
