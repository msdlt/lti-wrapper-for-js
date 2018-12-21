# LTI Wrapper for Neurosim - Neurosimulator by Sanjay Manohar

## PHP Installation

Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.

From the root directory of the app, if Composer is installed globally, run
```bash
composer install
```

Or, if Composer is only installed locally run `php composer.phar install`.

## Javascript Installation

### Install Dependencies
In the webroot/js directory, run `npm install` to install dependencies.
The dependencies are listed in package.json.

### Gulp
The, to build the js bundle, run `gulp`. The gulp build steps are defined in 
gulpfile.js

gulp is set to watch the js directories for any changes and will automatically
run the build steps and reload the browser using livereload when changes are 
detected. 

### LiveReload

1. Install the LiveReload desktop app from http://livereload.com/
2. Install the appropriate browser plugin (not IE!)
3. Enable LiveReload for your page by clicking the plugin icon

### Material UI Theme

The Material UI theme can be modified by editing webroot/js/src/theme.jsx

See http://www.material-ui.com/#/customization/themes

## Fonts

The app uses the [Roboto font](https://www.google.com/fonts/specimen/Roboto) 
and [Material icons](https://design.google.com/icons/).

## Licenses
* [CakePHP](http://cakephp.org/): [MIT](https://github.com/cakephp/cakephp/blob/master/LICENSE.txt)
* [PHP LTI Tool Provider class](http://www.spvsoftwareproducts.com/php/lti_tool_provider/): [LGPLv3](http://www.gnu.org/licenses/lgpl.html)
* [React](https://facebook.github.io/react) :[BSD](https://github.com/facebook/react/blob/master/LICENSE)
* [Material-UI](http://www.material-ui.com/) :[MIT](https://github.com/callemall/material-ui/blob/master/LICENSE)
* [react-tap-event-plugin](https://github.com/zilverline/react-tap-event-plugin) :[MIT](https://github.com/zilverline/react-tap-event-plugin/blob/master/LICENSE)
* [HTML5Shiv](https://github.com/aFarkas/html5shiv) :[MIT](https://github.com/aFarkas/html5shiv/blob/master/MIT%20and%20GPL2%20licenses.md)
* [Roboto font](https://www.google.com/fonts/specimen/Roboto) :[Apache License, version 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)
* [Material icons](https://design.google.com/icons/) :[CC-BY 4.0](https://creativecommons.org/licenses/by/4.0/)
