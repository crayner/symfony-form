var Encore = require('@symfony/webpack-encore');
//const CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('Resources/public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/bundles/hillrangeform/build/')
    // only needed for CDN's or sub-directory deploy
    .setManifestKeyPrefix('bundles/hillrangeform/build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('form', './assets/js/form.js')
    .splitEntryChunks()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())

    // enables hashed filenames (e.g. app.abc123.css)
    //.enableVersioning(Encore.isProduction())
    .enableReactPreset()
    .enableSingleRuntimeChunk()
    .configureBabel((babelConfig) => {
        if (Encore.isProduction()) {
            babelConfig.plugins.push(
                'transform-react-remove-prop-types'
            );
        }
        babelConfig.plugins.push(
            'babel-plugin-transform-object-rest-spread'
        );
    })
//    .addPlugin(new CopyWebpackPlugin([
        // copies to {output}/static
  //      { from: './assets/static', to: '/static' }
  //  ]))


    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
