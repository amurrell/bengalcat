var gulp = require('gulp');
var stylish = require('jshint-stylish');
var $ = {
  jshint: require('gulp-jshint'),
  cached: require('gulp-cached'),
};

var jshint = require('../paths').jshint;

// Lint all Gulp files.
gulp.task('jshint-gulp', function() {
  return gulp.src(jshint.gulp)
    .pipe($.cached('jshint-gulpfile'))
    .pipe($.jshint('.jshintrc'))
    .pipe($.jshint.reporter(stylish));
});

// Lint all source JavaScript files.
gulp.task('jshint', function() {
  return gulp.src(jshint.src)
    .pipe($.cached('jshint-js'))
    .pipe($.jshint('.jshintrc-src'))
    .pipe($.jshint.reporter(stylish));
});
