var gulp = require('gulp');
var wpPot = require('gulp-wp-pot');

gulp.task('default', function () {
    return gulp.src('./**/*.php')
        .pipe(wpPot( {
            domain: 'sticky-social-icons',
            package: 'Sticky_Social_Icons'
        } ))
        .pipe(gulp.dest('languages/sticky-social-icons.pot'));
});
