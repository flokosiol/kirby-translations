# Kirby Translations

![Version](https://img.shields.io/badge/version-0.3-green.svg)

With this plugin for [Kirby 2](http://getkirby.com) you can display the translation status for pages in the panel. At the moment there are 3 possible states:

+ **RED**: The translated `.txt`-file does not exist
+ **YELLOW**: The translated `.txt`-file exists, but the content is not up to date (checkbox unchecked)
+ **GREEN**: The translated `.txt`-file exists and the content is up to date (checkbox checked)

## Preview

![Screenshot](screenshot.png)


## Installation

### Kirby CLI

If you are using the [Kirby CLI](https://github.com/getkirby/cli) you can install this field plugin by running the following command in your shell from the root folder of your Kirby installation:

```
kirby plugin:install flokosiol/kirby-translations
```

### Copy & Paste

Add (if necessary) a new `plugins` folder to your `site` directory. Then copy the whole content of this repository in a new folder called `translations`. Your directory structure should now look like this:

```
site/
  plugins/
    translations/
      ...
```

### Git Submodule

It is possible to add this plugin as a Git submodule.

```
$ cd your/project/root  
$ git submodule add https://github.com/flokosiol/kirby-translations.git site/plugins/translations
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