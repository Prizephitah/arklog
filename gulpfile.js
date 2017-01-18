var gulp = require('gulp'),
  less = require('gulp-less'),
  clone = require('gulp-clone'),
  sourcemaps = require('gulp-sourcemaps'),
  cleanCss = require('gulp-clean-css'),
  rename = require('gulp-rename'),
  browserify = require('browserify'),
  sourceStream = require('vinyl-source-stream'),
  buffer = require('vinyl-buffer'),
  uglify = require('gulp-uglify')
;

gulp.task('default', ['less', 'javascript']);

gulp.task('less', function() {
  var lessSource = gulp.src('./src/Web/View/style.less');

  lessSource
    .pipe(clone())
    .pipe(sourcemaps.init())
    .pipe(less())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./public/css/'))
  ;
  lessSource
    .pipe(rename('style.min.css'))
    .pipe(less())
    .pipe(cleanCss())
    .pipe(gulp.dest('./public/css/'))
  ;
});

gulp.task('javascript', function() {
  var jsFile = browserify({
    entries: './src/Web/View/main.js',
    debug: true
  });

  jsFile
    .bundle()
    .pipe(sourceStream('app.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./public/js/'))
  ;

  var jsMinFile = browserify({
    entries: './src/Web/View/main.js',
    debug: false
  });

  jsMinFile
    .bundle()
    .pipe(sourceStream('app.min.js'))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(gulp.dest('./public/js/'))
  ;
})
