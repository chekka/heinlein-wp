const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create();

const paths = {
  scss: {
    src: './assets/scss/heinlein.scss',
    dest: './assets/css',
    watch: './scss/**/*.scss',
  }
}

// Compile sass into CSS & auto-inject into browsers
function styles () {
  return gulp.src([paths.scss.src])
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(paths.scss.dest))
    .pipe(browserSync.stream())
}

function compileSass() {
  return gulp.src('./assets/scss/*.scss') // Path to your SCSS files
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./assets/css/')); // Output directory for CSS files
}
gulp.task('sass', compileSass);

function watchSass() {
  gulp.watch('./assets/scss/*.scss', compileSass);
}
gulp.task('watch', watchSass);

// Static Server + watching scss/html files
function serve () {
  gulp.watch([paths.scss.watch], styles).on('change', browserSync.reload)
}

exports.default = exports.styles = styles
exports.serve = serve
