## Kirby 3 Translations

This ist the first draft of the Kirby 3 version.

- - -
**DON'T USE IT IN PRODUCTION !**
- - -

### Download

Download and copy this repository to `/site/plugins/translations`.

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