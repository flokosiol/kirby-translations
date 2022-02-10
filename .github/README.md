# Kirby Translations

Beta : This is a work in progress port of translations to Kirby 3. The "saved" state of a field is not yet enabled.

![Version](https://img.shields.io/badge/Version-1.0.1-blue.svg) ![License](https://img.shields.io/badge/License-MIT-green.svg) ![Kirby](https://img.shields.io/badge/Kirby-3.x-f0c674.svg)

This plugin enhances the translation handling of pages for [Kirby 3](http://getkirby.com) with the following features:

- The language menu explicitly shows if the translation exists :
  - **RED**: The translated `.txt`-file does not exist
  - **GREEN**: The translated `.txt`-file exists

For all non-default languages you can:

- Delete a translation (without deleting the whole page).
- Resynchronise translations with the default language file (revert).

Also, the plugin offers an option to replace the kirby languages menu by an enhanced one.


## Requirements
This plugin works with Fiber, so you probably need Kirby 3.6.

Although, there are is a chance that it works on versions below. _(if so, please report back!)_


## Installation

### Download

Download and extract this repository, rename the folder to `translations` and drop it into the plugins folder of your Kirby installation. You should end up with a folder structure like this:

```
site/plugins/translations/
```

### Composer

If you are using Composer, you can install the plugin with

```
composer require daandelange/k3-translations
```

### Git submodule

```
git submodule add https://github.com/daandelange/k3-translations.git site/plugins/translations
```


## Setup

### Replacing Kirby's native language menu
Within your website project, you can customise the header language menu as such:

```php
// Site /site/config/config.php
return [
  'daandelange.translations.options.header.replaceKirbyLanguages': false, // To disable replacing the native lang menu
  'daandelange.translations.options.header.compactMode': true, // To enable compact mode
  'daandelange.translations.options.header.delete': false, // To disallow deleting a language
  'daandelange.translations.options.header.revert': false, // To disallow reverting a language
];
```

### Translations Section
Add the following `section` to your blueprint. (optional)

```yaml
sections:
  translations:
    type: translations
```

To disable the possibility to delete language textfiles you can use …

```yaml
sections:
  translations:
    type: translations
    deletable: false
```

To disable the possibility to revert the content of a language textfile to the default language do …

```yaml
sections:
  translations:
    type: translations
    revertable: false
```


To use a more compact layout, do …

```yaml
sections:
  translations:
    type: translations
    compactmode: true
```

Of course, you can combine all options.


## Development

This plugin follows the [standard Kirby PluginKit](https://github.com/getkirby/pluginkit/tree/4-panel) structure, see [their plugin guide](https://getkirby.com/docs/guide/plugins/plugin-setup-basic) for more details on using it.
*These steps are optional, for building development versions.*

If you're using a modified Kirby folder structure, you probably have to fix the relative path to the `kirby` folder in `kirbyup.config.ts`.

- Npm requirements (optional) : `npm install -g kirbyup`
- Setup                       : `cd /path/to/website/site/plugins/translations && npm install`
- While developing            : `npm run dev`
- Compile a production build  : `npm run build`
- Update dependencies         : `npm update`
- Composer install & update   : `composer update`


## License

[MIT](https://github.com/daandelange/k3-translations/blob/main/.github/LICENSE)

### Commercial Usage

This plugin is free but if you use it in a commercial project please consider to contribute an improvement, or hire someone to do so.


## Credits

This is a Kirby 3 port of @Flokosiol's [kirby-translations](https://github.com/flokosiol/kirby-translations) which is for Kirby 2; thanks to him for
Special thanks to all [contributors](https://github.com/daandelange/k3-translations/graph/contributors) as well as the original [kirby2-translations contributors](https://github.com/flokosiol/kirby-translations/graphs/contributors) !
