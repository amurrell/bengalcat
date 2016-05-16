var gulp = require('gulp');
var $ = {
  uglify: require('gulp-uglify'),
  concat: require('gulp-concat'),
  rename: require('gulp-rename'),
};

var bundles = require('../paths').bundles;
var scripts = require('../paths').scripts;

var bundleTasks = Object.keys(bundles);

// Create a JavaScript bundle for each group of files.
bundleTasks.forEach(function(name) {
  gulp.task(name, function() {
    return gulp.src(bundles[name])
      .pipe($.concat(name + '.js'))
      .pipe($.uglify())
      .pipe($.rename({ suffix: '.bundle' }))
      .pipe(gulp.dest(scripts.dest));
  });
});

// Alias all bundle tasks under the `bundles` task name.
gulp.task('bundles', bundleTasks);
