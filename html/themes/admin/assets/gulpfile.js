console.time('Gulp Run Time');

/**
 * Tasks
 *
 * Configure the tasks to be loaded.
 * Comment out what is not needed.
 */
var tasks = [
  'clean',
  'styles',
  'jshint',
  'scripts',
  'scripts-bundles',
  'images',
  'copy',
  'watch',
  'runner'
];

/**
 * Task Loader (DO NOT EDIT)
 */
require('./gulp/util/loader')(tasks);
