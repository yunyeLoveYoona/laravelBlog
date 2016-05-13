var gulp = require('gulp');
var rename = require('gulp-rename');
var elixir = require('laravel-elixir');

/**
 * 拷贝所有需要的文件
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

    // 拷贝 jQuery, Bootstrap, 和 FontAwesome
    gulp.src("bower_components/jquery/dist/jquery.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("bower_components/bootstrap/less/**")
        .pipe(gulp.dest("resources/assets/less/bootstrap"));

    gulp.src("bower_components/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/"));

    gulp.src("bower_components/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/assets/fonts"));

    gulp.src("bower_components/font-awesome/less/**")
        .pipe(gulp.dest("resources/assets/less/fontawesome"));

    gulp.src("bower_components/font-awesome/fonts/**")
        .pipe(gulp.dest("public/assets/fonts"));

    // 拷贝 datatables
    var dtDir = 'bower_components/datatables-plugins/integration/';

    gulp.src("bower_components/datatables/media/js/jquery.dataTables.js")
        .pipe(gulp.dest('resources/assets/js/'));

    gulp.src(dtDir + 'bootstrap/3/dataTables.bootstrap.css')
        .pipe(rename('dataTables.bootstrap.less'))
        .pipe(gulp.dest('resources/assets/less/others/'));

    gulp.src(dtDir + 'bootstrap/3/dataTables.bootstrap.js')
        .pipe(gulp.dest('resources/assets/js/'));
		
		 // Copy selectize
    gulp.src("bower_components/selectize/dist/css/**")
        .pipe(gulp.dest("public/assets/selectize/css"));

    gulp.src("bower_components/selectize/dist/js/standalone/selectize.min.js")
        .pipe(gulp.dest("public/assets/selectize/"));

    // Copy pickadate
    gulp.src("bower_components/pickadate/lib/compressed/themes/**")
        .pipe(gulp.dest("public/assets/pickadate/themes/"));

    gulp.src("bower_components/pickadate/lib/compressed/picker.js")
        .pipe(gulp.dest("public/assets/pickadate/"));

    gulp.src("bower_components/pickadate/lib/compressed/picker.date.js")
        .pipe(gulp.dest("public/assets/pickadate/"));

    gulp.src("bower_components/pickadate/lib/compressed/picker.time.js")
        .pipe(gulp.dest("public/assets/pickadate/"));
    gulp.src("bower_components/clean-blog/less/**").pipe(gulp.dest("resources/assets/less/clean-blog"));
	


});

/**
 * Default gulp is to run this elixir stuff
 */
elixir(function(mix) {

    // Combine scripts
    mix.scripts([
        'js/jquery.js',
        'js/bootstrap.js',
        'js/jquery.dataTables.js',
        'js/dataTables.bootstrap.js'
    ],
    'public/assets/js/admin.js', 'resources//assets');

    // Combine blog scripts
    mix.scripts([
        'js/jquery.js',
        'js/bootstrap.js',
        'js/blog.js'
    ], 'public/assets/js/blog.js', 'resources//assets');

    // Compile CSS
    mix.less('admin.less', 'public/assets/css/admin.css');
    mix.less('blog.less', 'public/assets/css/blog.css');
});