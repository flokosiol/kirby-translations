# Kirby Translations

Beta : This is a work in progress port of translations to Kirby 3. The "saved" state of a field is not yet enabled.

![Version](https://img.shields.io/badge/Version-1.0.1-blue.svg) ![License](https://img.shields.io/badge/License-MIT-green.svg) ![Kirby](https://img.shields.io/badge/Kirby-3.x-f0c674.svg)

This plugin enhances the translation handling of pages for [Kirby 3](http://getkirby.com) with the following features:

## How it works

The plugin automatically detects if there's a language `.txt` file for the current page in your content folder and displays additional language tabs. For now there are two possible states:

+ **RED**: The translated `.txt`-file does not exist
+ **GREEN**: The translated `.txt`-file exists

For all non-default languages you can:

- delete a translation (without deleting the whole page)
- resynchronise translations with the default language file


## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to contribute improvement or hire someone to do so.

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

Add the following `section` to your blueprint.

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

To replace kirby's default language switcher in the header, do …

```yaml
sections:
  translations:
    type: translations
    portaled: false
```

To use a more compact layout, do … *(automatically enabled if portaled)*

```yaml
sections:
  translations:
    type: translations
    compactmode: true
```

Of course, you can combine all options.

## Known issues

For Kirby 3.5 and before, using the default language switcher in the Panel breaks the display of the actions (delete, revert), but this should be fixed for Kirby 3.6+


## Development

This plugin follows the [standard Kirby PluginKit](https://github.com/getkirby/pluginkit/tree/4-panel) structure, see [their plugin guide](https://getkirby.com/docs/guide/plugins/plugin-setup-basic) for more details on using it.
*These steps are optional, for building development versions.*

- Npm requirements (optional) : `npm install -g kirbyup`
- Setup                       : `cd /path/to/website/site/plugins/translations && npm install`
- While developing            : `npm run dev`
- Compile a production build  : `npm run build`
- Update dependencies         : `npm update`
- Composer install & update   : `composer update`


## License

[MIT](https://github.com/daandelange/k3-translations/blob/main/.github/LICENSE)


## Credits

This is the Kirby3 port of @Flokosiol's [kirby-translations](https://github.com/flokosiol/kirby-translations) for Kirby 2.
Special thanks to all [contributors](https://github.com/daandelange/k3-translations/graph/contributors)!
