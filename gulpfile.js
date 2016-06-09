// Project Specific

var plugin_filename = 'jck-woosocial',
    plugin_zip_name = plugin_filename+'.zip',
    plugin_main_file = plugin_filename+'.php';


// load plugins

var gulp            = require('gulp'),
    argv            = require('yargs').argv,
    sass            = require('gulp-sass'),
    autoprefixer    = require('gulp-autoprefixer'),
    minifycss       = require('gulp-minify-css'),
    jshint          = require('gulp-jshint'),
    uglify          = require('gulp-uglify'),
    rename          = require('gulp-rename'),
    concat          = require('gulp-concat'),
    notify          = require('gulp-notify'),
    zip             = require('gulp-zip'),
    replace         = require('gulp-replace');

var paths = {
    frontend_scripts: ['source/frontend/js/**/*.js'],
    frontend_styles: ['source/frontend/scss/**/*.scss'],
    admin_scripts: ['source/admin/js/**/*.js'],
    admin_styles: ['source/admin/scss/**/*.scss'],
    src: ['inc/**/*', 'templates/**/*', 'assets/**/*', 'languages/**/*', plugin_main_file],
    cc_src: ['**/*']
};

var deps = {
    // 'src' : 'dest'

    // Settings Framework
    'vendor/jamesckemp/WordPress-Settings-Framework/wp-settings-framework.php' : 'inc/admin/wp-settings-framework',
    'vendor/jamesckemp/WordPress-Settings-Framework/assets/css/main.css' : 'inc/admin/wp-settings-framework/assets/css',
    'vendor/jamesckemp/WordPress-Settings-Framework/assets/js/main.js' : 'inc/admin/wp-settings-framework/assets/js',
    'vendor/jamesckemp/WordPress-Settings-Framework/assets/vendor/jquery-timepicker/jquery.ui.timepicker.js' : 'inc/admin/wp-settings-framework/assets/vendor/jquery-timepicker',
    'vendor/jamesckemp/WordPress-Settings-Framework/assets/vendor/jquery-timepicker/jquery.ui.timepicker.css' : 'inc/admin/wp-settings-framework/assets/vendor/jquery-timepicker',

    'vendor/iconicwp/template-loader/class-template-loader.php' : 'inc',
    'vendor/gamajo/template-loader/class-gamajo-template-loader.php' : 'inc/vendor'

};

/**	=============================
    *
    * Tasks
    *
    ============================= */

	gulp.task('frontend_scripts', function() {

        return gulp.src(paths.frontend_scripts)
            .pipe(jshint('.jshintrc'))
            .pipe(jshint.reporter('default'))
            .pipe(concat('main.js'))
            .pipe(gulp.dest('assets/frontend/js'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(uglify())
            .pipe(gulp.dest('assets/frontend/js'))
            .pipe(notify({ message: 'Frontend scripts task complete' }));

	});

	gulp.task('frontend_styles', function() {

        return gulp.src(paths.frontend_styles)
            .pipe(sass({outputStyle: 'expanded'}))
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
            .pipe(gulp.dest('assets/frontend/css'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(minifycss())
            .pipe(gulp.dest('assets/frontend/css'))
            .pipe(notify({ message: 'Frontend styles task complete' }));

	});

	gulp.task('admin_scripts', function() {

        return gulp.src(paths.admin_scripts)
            .pipe(jshint('.jshintrc'))
            .pipe(jshint.reporter('default'))
            .pipe(concat('main.js'))
            .pipe(gulp.dest('assets/admin/js'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(uglify())
            .pipe(gulp.dest('assets/admin/js'))
            .pipe(notify({ message: 'Frontend scripts task complete' }));

	});

	gulp.task('admin_styles', function() {

        return gulp.src(paths.admin_styles)
            .pipe(sass({outputStyle: 'expanded'}))
            .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
            .pipe(gulp.dest('assets/admin/css'))
            .pipe(rename({ suffix: '.min' }))
            .pipe(minifycss())
            .pipe(gulp.dest('assets/admin/css'))
            .pipe(notify({ message: 'Admin styles task complete' }));

	});

    // Rerun the task when a file changes
    gulp.task('watch', function () {

        gulp.watch(paths.frontend_scripts, ['frontend_scripts']);
        gulp.watch(paths.frontend_styles, ['frontend_styles']);
        gulp.watch(paths.admin_scripts, ['admin_scripts']);
        gulp.watch(paths.admin_styles, ['admin_styles']);

    });

    /**
     * Move components
     */
    gulp.task('deps', function() {

        for (var key in deps) {
            gulp.src( key )
                .pipe( gulp.dest( deps[key] ) );
        }

    });

	// The default task (called when you run `gulp` from cli)
	gulp.task('default', ['watch']);

/**	=============================
    *
    * Compile for CodeCanyon
    *
    ============================= */

	// Run to compile plugin zip
	gulp.task('prepare_plugin_files', function () {

    	var plugin_src = 'tmp/'+plugin_filename+'/';

    	return gulp.src(paths.src, {base: "."})
            .pipe(gulp.dest(plugin_src));

	});

	// Run to compile plugin zip
	gulp.task('create_plugin_zip', ['prepare_plugin_files'], function () {

    	var plugin_src = 'tmp/'+plugin_filename+'/';

	    return gulp.src(plugin_src+"**/*", {base: "./tmp"})
	        .pipe( zip(plugin_zip_name) )
	        .pipe( gulp.dest('dist') )
	        .pipe( notify({ message: 'Plugin zip Created' }) );

	});

	// Run to compile zip of plugin, readme and licenses
	gulp.task('create_main_zip', ['create_plugin_zip'], function () {

	    return gulp.src(paths.cc_src, {cwd: __dirname + "/dist"})
	        .pipe(zip('main-files-'+plugin_filename+'.zip'))
	        .pipe(gulp.dest('codecanyon'))
	        .pipe(notify({ message: 'Main files zipped for CodeCanyon' }));

	});

	// RUN THIS TO COMPILE FOR CC (gulp compile)
	gulp.task('compile', ['deps', 'create_main_zip']);

/**
 * Replace strings in plugin files to rename
 *
 * $ gulp install --plugin_name="Iconic Base Plugin" --plugin_shortname="Base Plugin" --class_name=Iconic_Base_Plugin --class_prefix=Iconic_BP_
 *
 * @param str class_name hyphenated string e.g. Iconic_Base_Plugin
 */

gulp.task('install', ['deps'], function(){

    var class_name_lower = argv.class_name.toLowerCase();

    gulp.src(['baseplugin.php','package.json','composer.json'], {base: './'})
        .pipe(replace('{{class-name}}', argv.class_name))
        .pipe(replace('{{text-domain}}', class_name_lower.split('_').join('-')))
        .pipe(replace('{{global-variable}}', class_name_lower))
        .pipe(replace('{{class-prefix}}', argv.class_prefix))
        .pipe(replace('{{plugin-name}}', argv.plugin_name))
        .pipe(replace('{{plugin-shortname}}', argv.plugin_shortname))
        .pipe(gulp.dest('./'))
        .pipe(notify({ message: 'Plugin files updated to '+argv.plugin_name }));

});