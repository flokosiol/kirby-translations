# Kirby 3 Translations

This ist the first draft of the Kirby 3 version.

> **It is not recommended to use it in production!**

## Installation

### Download

Download and extract this repository, rename the folder to `translations` and drop it into the plugins folder of your Kirby 3 installation. You should end up with a folder structure like this:

```
site/plugins/translations/
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

## Known issues

+ Using the default language switcher in the Panel breaks the display of the actions (delete, revert)
+ `revertable` option is not implemented yet
+ `uptodate` option is not implemented (maybe will never be)


## License

MIT