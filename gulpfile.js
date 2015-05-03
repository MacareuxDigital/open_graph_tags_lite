var gulp = require('gulp');
var zip = require('gulp-zip');

gulp.task('zip', function () {
    return gulp.src(['open_graph_tags_lite/**/*'], {base: "."})
        .pipe(zip('open_graph_tags_lite.zip'))
        .pipe(gulp.dest('./build'));
});

gulp.task('default', ['zip']);