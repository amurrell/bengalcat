var gulp = require('gulp');
var $ = {
  imagemin: require('gulp-imagemin'),
  cache: require('gulp-cache'),
};

var images = require('../paths').images;

var IMAGEMIN_OPTIONS = {
  optimizationLevel: 3,
  progressive: true,
  interlaced: true,
};

// Optimize images and output to `build` folder.
gulp.task('images', function() {
  return gulp.src(images.src)
    .pipe($.cache($.imagemin(IMAGEMIN_OPTIONS)))
    .pipe(gulp.dest(images.dest));
});
