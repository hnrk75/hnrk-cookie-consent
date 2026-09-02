import gulp from "gulp";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";

const sass = gulpSass(dartSass);
const isProd = process.env.NODE_ENV === "production";
const sassOptions = { outputStyle: isProd ? "compressed" : "expanded" };

const paths = {
  scss: "assets/scss/*.scss",
  css: "assets/css",
};

function compileSass() {
  return gulp
    .src(paths.scss)
    .pipe(sass(sassOptions).on("error", sass.logError))
    .pipe(autoprefixer())
    .pipe(gulp.dest(paths.css));
}

function watchFiles() {
  gulp.watch("assets/scss/**/*.scss", compileSass);
}

export const build = compileSass;
export default gulp.series(compileSass, watchFiles);
