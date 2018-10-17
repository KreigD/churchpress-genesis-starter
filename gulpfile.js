/**
 * ChurchPress Genesis Starter.
 *
 * This file adds Gulp tasks to the ChurchPress Genesis Starter theme.
 *
 * @author Soulheart
 */

// Set up dependencies.
const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync');
const bump = require('gulp-bump');
const cache = require('gulp-cached');
const cleancss = require('gulp-clean-css');
const cssnano = require('gulp-cssnano');
const del = require('del');
const fs = require('fs');
const gulp = require('gulp');
const imagemin = require('gulp-imagemin');
const minify = require('gulp-minify');
const mqpacker = require('css-mqpacker');
const notify = require('gulp-notify');
const pixrem = require('gulp-pixrem');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const prettierEslint = require('gulp-prettier-eslint');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sortCSSmq = require('sort-css-media-queries');
const sourcemaps = require('gulp-sourcemaps');

/**
 * Error handling
 *
 * @function
 */
function handleErrors() {
	const args = Array.prototype.slice.call(arguments);

	notify
		.onError({
			title: 'Task Failed [<%= error.message %>]',
			message: '<%= error %> - See console or enable logging in the plugin.'
		})
		.apply(this, args);

	// Prevent the 'watch' task from stopping
	this.emit('end');
}

/*************
 * CSS Tasks
 ************/

/**
 * PostCSS Task Handler
 */
gulp.task('postcss', function () {
	gulp
		.src('./assets/sass/style.scss')

		// Error handling.
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)

		// Wrap tasks in a sourcemap.
		.pipe(sourcemaps.init())

		// Sass magic.
		.pipe(
			sass({
				errLogToConsole: true,
				outputStyle: 'nested' // Options: nested, expanded, compact, compressed
			})
		)

		// Pixel fallbacks for rem units.
		.pipe(pixrem())

		// PostCSS magic.
		.pipe(
			postcss([
				autoprefixer({
					browsers: ['last 4 versions']
				}),
				mqpacker({
					sort: true
				})
			])
		)

		// Create the source map.
		.pipe(
			sourcemaps.write('./', {
				includeContent: false
			})
		)

		// Write the CSS file.
		.pipe(gulp.dest('./'))

		// Inject CSS into Browser.
		.pipe(browserSync.stream());
});

/*******************
 * JavaScript Tasks
 *******************/

/**
 * JavaScript Task Handler.
 */
gulp.task('js', function () {
	gulp
		.src(['!./assets/js/*.min.js', './assets/js/*.js'])

		// Error handling.
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)

		// Linting and Pretty Printing.
		.pipe(prettierEslint())

		// Minify JavaScript.
		.pipe(
			minify({
				ext: {
					src: '.js',
					min: '.min.js'
				},
				noSource: true
			})
		)
		.pipe(gulp.dest('./assets/js'))

		// Inject changes via browserSync.
		.pipe(
			browserSync.reload({
				stream: true
			})
		)

		.pipe(
			notify({
				message: 'Scripts are minified.'
			})
		);
});

/************************
 * Optimize theme images
 ***********************/
gulp.task('images', function () {
	return (
		gulp
		.src('./assets/images/*')

		// Error handling.
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)

		// Cache files to avoid processing files that haven't changed.
		.pipe(cache('images'))

		// Optimize images.
		.pipe(
			imagemin([
				imagemin.gifsicle({
					interlaced: true
				}),
				imagemin.jpegtran({
					progressive: true
				}),
				imagemin.optipng({
					optimizationLevel: 5
				}),
				imagemin.svgo({
					plugins: [{
						removeViewBox: true
					}, {
						cleanupIDs: false
					}]
				})
			])
		)

		// Output the optimized images to this directory.
		.pipe(gulp.dest('./assets/images'))

		// Inject changes via browsersync.
		.pipe(
			browserSync.reload({
				stream: true
			})
		)

		.pipe(
			notify({
				message: 'Images are optimized.'
			})
		)
	);
});

/********************************************
 * Bump theme version.
 *
 * Usage:
 *  gulp bump --major -> from 1.0.0 to 2.0.0
 *  gulp bump --minor -> from 1.0.0 to 1.1.0
 *  gulp bump --patch -> from 1.0.0 to 1.0.1
 *
 * The theme version in PHP is updated
 * automatically from the stylesheet.
 *******************************************/
gulp.task('bump', function () {
	let versionbump;

	if (arg.major) {
		versionBump = 'major';
	}

	if (arg.minor) {
		versionBump = 'minor';
	}

	if (arg.patch) {
		versionBump = 'patch';
	}

	gulp
		.src(['./package.json', './style.css'])
		.pipe(
			bump({
				type: versionBump
			})
		)
		.pipe(gulp.dest('./'));

	gulp
		.src('./assets/sass/style.scss')
		.pipe(
			bump({
				type: versionBump
			})
		)
		.pipe(gulp.dest('./assets/sass/'));
});

/**********************
 * All Tasks Listeners
 *********************/

const siteName = 'genesis.test';

gulp.task('watch', function () {

	// HTTPS (optional).
	browserSync({
		proxy: `http://${siteName}`,
		host: siteName,
		port: 8000,
		notify: false,
		open: 'external',
		browser: 'chrome'

		// https: {
		// 	key: 'path/to/your/key/file/genesis.key',
		// 	cert: `path/to/your/cert/file/${siteName}.crt`
		// }
	});

	// Watch Scss files. Changes are injected into the browser from within the task.
	gulp.watch('./assets/sass/**/*.scss', ['styles']);

	// Watch JavaScript files. Changes are injected into the browser from within the task.
	gulp.watch(['./assets/js/*.js', '!./assets/js/*.min.js'], ['scripts']);

	// Watch Image files. Changes are injected into the browser from within the task.
	gulp.watch('./assets/images/*', ['images']);

	// Watch PHP files and reload the browser if there is a change. Add directories if needed.
	gulp
		.watch([
			'./*.php',
			'./config/*.php',
			'./lib/*.php',
			'./lib/**/*.php',
			'./lib/**/**/*.php'
		])
		.on('change', browserSync.reload);
});

/********************
 * Individual tasks.
 *******************/
gulp.task('scripts', ['js']);
gulp.task('styles', ['postcss']);

gulp.task('default', ['watch'], function () {
	gulp.start('styles', 'scripts', 'images');
});