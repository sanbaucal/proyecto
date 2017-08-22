var gulp   = require('gulp');
var stylus = require('gulp-stylus');
var concat = require('gulp-concat');
var watch  = require('gulp-watch');
var uglify = require('gulp-uglify');
var nib    = require('nib');

var nib = require('nib');

var path = {
	stylus: ['css/base/*.styl', 'css/components/*.styl', 'css/mediaquerys/*.styl', 'css/default.styl']
};

gulp.task('stylus', function () {
	gulp.src('css/default.styl')
		.pipe(stylus({
			use      : nib(),
			compress : true
		}))
		.pipe(concat('app-min.css'))
		.pipe(gulp.dest('css'));
});

gulp.task('watch', function() {
	watch(path.stylus, function(){
		gulp.start('stylus');
	});
});

gulp.task('default', ['stylus', 'watch']);
