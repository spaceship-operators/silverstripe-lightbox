var gulp = require('gulp'),

	// Gulp modules
	autoprefixer = require('gulp-autoprefixer'),
	cssnano      = require('gulp-cssnano'),
	rename       = require('gulp-rename'),
	sass         = require('gulp-sass'),

	path = {
		css  : './css',
		scss : './scss/**/*.scss'
	},

	options = {

		// Autoprefixer options
		autoprefixer: {
			browsers: ['last 2 versions', 'IE 8', 'IE 9']
		},

		// gulp-rename options
		rename: {
			suffix: '.min'
		},

		// gulp-sass options
		sass: {
			indentType  : 'tab',
			outputStyle : 'expanded'
		}
	};

// Build CSS
gulp.task('build-css', function() {
	gulp.src(path.scss)
		.pipe( sass(options.sass).on('error', sass.logError))
		.pipe( autoprefixer(options.autoprefixer))
		.pipe( gulp.dest(path.css))
		.pipe( cssnano())
		.pipe( rename(options.rename))
		.pipe( gulp.dest(path.css));
});

// Minify CSS


// Watch task
gulp.task('watch', function() {
	gulp.watch(path.scss, ['build-css']);
});

// Default task
gulp.task('default', ['build-css']);