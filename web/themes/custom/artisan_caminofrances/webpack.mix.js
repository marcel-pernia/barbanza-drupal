
require('dotenv').config({ path: '.env.local' });
const mix = require('laravel-mix');
const glob = require('glob');
require('laravel-mix-copy-watched');

mix
  .sourceMaps()
  .webpackConfig({
    devtool: 'source-map'
  })
  .disableNotifications()
  .options({
    processCssUrls: false
  });

mix.browserSync({
  proxy: process.env.DRUPAL_BASE_URL,
  files: [
    'components/**/*.css',
    'components/**/*.js',
    'components/**/*.twig',
    'templates/**/*.twig'
  ],
  stream: true
});

mix.sass("src/scss/style.scss", "build/css/style.css");

for (const sourcePath of glob.sync("components/**/*.scss")) {
  const destinationPath = sourcePath.replace(/\.scss$/, ".css");
  mix.sass(sourcePath, destinationPath);
}

mix.js("src/js/script.js", "build/js/script.js");
mix.js("src/js/main.script.js", "build/js/main.script.js");

for (const sourcePath of glob.sync("components/**/_*.js")) {
  const destinationPath = sourcePath.replace(/\/_([^/]+\.js)$/, "/$1");
  mix.js(sourcePath, destinationPath);
}

mix.copyDirectoryWatched('src/assets/images', 'build/assets/images');
mix.copyDirectoryWatched('src/assets/fonts/**/*', 'build/fonts');
