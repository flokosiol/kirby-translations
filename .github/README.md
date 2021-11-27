# Kirby Translations

![Version](https://img.shields.io/badge/Version-1.0.1-blue.svg) ![License](https://img.shields.io/badge/License-MIT-green.svg) ![Kirby](https://img.shields.io/badge/Kirby-3.x-f0c674.svg)

This plugin enhaces the translation handling of pages for [Kirby CMS](http://getkirby.com) with the following features:

## How it works

The plugin automatically detects if there's a language `.txt` file for the current page in your content folder and displays additional language tabs. For now there are two possible states:

+ **RED**: The translated `.txt`-file does not exist
+ **GREEN**: The translated `.txt`-file exists

For all non-default languages you can:

- delete a translation (without deleting the whole page)
- resynchronize translations with the default language file


## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to.

+ [make a donation](https://www.paypal.me/flokosiol/10) or
+ [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/36201?link=1170)


## Installation

### Download

Download and extract this repository, rename the folder to `translations` and drop it into the plugins folder of your Kirby installation. You should end up with a folder structure like this:

```
site/plugins/translations/
```

### Composer

Coming soon.


### Git submodule

```
git submodule add https://github.com/flokosiol/kirby-translations.git site/plugins/translations
```


## Setup

Add the following `section` to your blueprint.

```yaml
sections:
  translations:
    type: translations
```

To disable the possibillity to delete language textfiles you can use …

```yaml
sections:
  translations:
    type: translations
    deletable: false
```

To disable the possibillity to revert the content of a language textfile to the default language do …

```yaml
sections:
  translations:
    type: translations
    revertable: false
```

Of course, you can combine both options.

## Known issues

For Kirby 3.5 and before, using the default language switcher in the Panel breaks the display of the actions (delete, revert), but this should be fixed for Kirby 3.6+


## License

[MIT](https://github.com/flokosiol/kirby-translations/blob/main/LICENSE)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

Special thanks to all [contributors](https://github.com/flokosiol/kirby-translations/main/contributors)!
