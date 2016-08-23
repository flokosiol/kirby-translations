# Kirby Translations

![Version](https://img.shields.io/badge/version-0.2-green.svg)

With this field plugin for [Kirby 2](http://getkirby.com) you can display the translation status for pages in the panel. At the moment there are 3 possible states:

+ **RED**: The translated `.txt`-file does not exist
+ **YELLOW**: The translated `.txt`-file exists, but the content is not up to date (checkbox unchecked)
+ **GREEN**: The translated `.txt`-file exists and the content is up to date (checkbox checked)

## Preview

![Screenshot](screenshot.png)


## Installation

### Kirby CLI

Coming soon …

### Copy & Paste

Add (if necessary) a new `fields` folder to your `site` directory. Then copy the whole content of this repository in a new folder called `translations`. Your directory structure should now look like this:

```
site/
  fields/
    translations/
      ...
```

### Git Submodule

It is possible to add this plugin as a Git submodule.

```
$ cd your/project/root  
$ git submodule add https://github.com/flokosiol/kirby-translations.git site/fields/translations
```

For more information, have a look at [Working with Git](https://getkirby.com/docs/cookbook/working-with-git) in the Kirby cookbook.


## Usage

Now you are ready to use the new field `translations` in your blueprints. 

```
...
fields:
  mytranslations:
    type: translations
...
```