panel.plugin('flokosiol/translations', {
  sections: {
    translations: {
      data: function () {
        return {
          id: null,
          deletable: null,
          revertable: null,
          translations: null,
          compactmode: null,
          portaled: null
        }
      },
      computed: {
        // Computed functions very similar to /panel/src/components/Navigation/Languages.vue
        defaultLanguage() {
          // Kirby 3.6 Fiber
          if (this.hasFiber) {
            return window.panel.$languages.find(l => l.default == true) ?? window.panel.$languages[0];
          }
          // Pre 3.6
          return this.$store.state.languages.default;
        },
        language() {
          if (this.hasFiber) {
            return window.panel.$language;
          }
          return this.$store.state.languages.current;
        },
        languages() {
          if (this.hasFiber) {
            return window.panel.$languages.filter(language => language.default === false);
          }
          return this.$store.state.languages.all.filter(language => language.default === false);
        },
        hasFiber() {
          return window.panel && window.panel.$languages;
        },
        portalSelector() {
          return '.k-header-buttons div.k-bar-slot[data-position=left] .k-button-group';
        },
        compactModeEnabled() {
          return this.compactmode || this.portaled;
        }
      },
      created: function() {
        this.updateButtons();
        this.$events.$on("model.save", this.updateButtons);
        this.$events.$on("model.update", this.updateButtons);
      },
      methods: {
        updateButtons() {
          this.load().then(response => {
            // populate the data object when the section gets loaded
            // @see https://getkirby.com/docs/reference/plugins/extensions/sections#vue-component
            this.id           = response.id;
            this.deletable    = response.deletable;
            this.revertable   = response.revertable;
            this.translations = response.translations;
            this.compactmode  = response.compactmode;
            this.updatePortaled(response.portaled);

            // loop through all panel languages (but default)
            let languages = this.languages;
            for (let i = 0; i < languages.length; i++) {
              // set icon + color if page is translated
              if (this.translations.indexOf(languages[i].code) >= 0) {
                this.languages[i].icon = 'check';
                this.languages[i].theme = 'positive';
              }
              else {
                this.languages[i].icon = 'cancel';
                this.languages[i].theme = 'negative';
              }
            }

            // hide actions (delete + revert) for default language + untranslated languages
            if (this.language.code === this.defaultLanguage.code || this.translations.indexOf(this.language.code) < 0) {
              this.deletable = false;
              this.revertable = false;
            }
          });
        },
        changeLanguage(language) {
          this.updateButtons();

          // execute language change
          if (this.hasFiber) {
            this.$emit("change", language);
            this.$go(this.$view.path, {
              query: {
                language: language.code
              }
            });
          }
          else {
            this.$store.dispatch("languages/current", language);
            this.$emit("change", language);
          }
        },
        deleteTranslationOpen(id, language) {
          this.$refs.deleteDialog.open(id, language);
        },
        deleteTranslationSubmit(id, language) {
          this.$api.post('flokosiol/translations/delete', {id: id, languageCode: language.code})
            .then(response => {
              this.$refs.deleteDialog.close();
              if (response.code === 200) {
                this.$store.dispatch('notification/success', response.text);
                this.changeLanguage(this.defaultLanguage);
              }
              else {
                this.$store.dispatch('notification/error', response.text);
              }
            })
            .catch(error => {
              this.$store.dispatch('notification/error', error);
            });
        },
        revertTranslationOpen(id, language) {
          this.$refs.revertDialog.open(id, language);
        },
        revertTranslationSubmit(id, language) {
          this.$api.post('flokosiol/translations/revert', {id: id, languageCode: language.code})
            .then(response => {
              this.$refs.revertDialog.close();
              if (response.code === 200) {
                this.$store.dispatch('notification/success', response.text);
                this.updateButtons();

                if (this.hasFiber) this.$go(this.$view.path);
              }
              else {
                this.$store.dispatch('notification/error', response.text);
              }
            })
            .catch(error => {
              this.$store.dispatch('notification/error', error);
            });

          // reload panel to read changed textfile
          if(!this.hasFiber) this.$router.go();
        },
        updatePortaled(value) {
          if(value) this.portaled = value;

          // Update portaled state to hide panel lang switcher via css
          this.portaled ? document.body.classList.add('k-translations-hide-kirbylangs') : document.body.classList.remove('k-translations-hide-kirbylangs');
        },
        menuLanguageClick(language) {
          // Clicking on the current lang toggles the submenu
          if (language.code == this.language.code) {
            if ( this.compactModeEnabled ) {
              if (this.getLanguageOptionsToggleRef()) this.getLanguageOptionsToggleRef().toggle();
            }
            else {
              // ignore clicks on current language (triggers a useless reload)
              // this.changeLanguage(language); to reload page to replicate original behaviour
            }
          }
          else this.changeLanguage(language);
        },
        getLanguageOptionsToggleRef() {
          // Sometimes when 2 items fill the same $ref, it becomes an array...
          if(this.$refs.languageOptionsToggle){
            return (this.$refs.languageOptionsToggle instanceof Array) ? this.$refs.languageOptionsToggle.at(0) : this.$refs.languageOptionsToggle;
          }
          return null;
        },
        getLangButtonClass(lang, extra = {}) {
          return {
            'translations--active': lang.code === this.language.code, // mark active
            'k-translations-button': true, // custom styles
            ...extra
          };
        },
      },
      template: `
        <div :class="{'portaled': portaled, 'k-field':true, 'k-translations-field':true}">
          
          <k-button-group v-if="languages.length" class="k-translations-buttons">
            <portal :selector="portalSelector" :disabled="!portaled">
              <k-button :data-langcode="defaultLanguage.code" icon="check" :class="getLangButtonClass(defaultLanguage, {'k-languages-dropdown': true, 'k-translations-languages-dropdown': true})" theme="positive" :key="defaultLanguage.code" @click.stop="menuLanguageClick(defaultLanguage)" :responsive="compactModeEnabled">
                  <span :class="{'longname':compactModeEnabled}">{{ defaultLanguage.name }}</span>
                  <span class="shortname" v-if="compactModeEnabled">{{ defaultLanguage.code }}</span>
              </k-button>
            </portal>
            <portal :selector="portalSelector" v-for="lang in languages" :key="lang.code" :disabled="!portaled">
                <k-dropdown :class="getLangButtonClass(lang, {'k-languages-dropdown': true, 'k-translations-languages-dropdown': true})">
                  <k-button class="k-translations-button" :icon="lang.icon" :theme="lang.theme" @click.stop="menuLanguageClick(lang)" :responsive="compactModeEnabled">
                    <span :class="{'longname':compactModeEnabled}">{{ lang.name }}</span>
                    <span v-if="compactModeEnabled" class="shortname">{{ lang.code }}</span>
                    <k-icon v-if="compactModeEnabled && language.code == lang.code" class="k-translations-menu-icon" type="angle-down" />
                  </k-button>
                  <k-dropdown-content v-if="compactModeEnabled && (lang.code === language.code)" class="k-translations-options" ref="languageOptionsToggle">
                    <k-button-group>
                      <k-dropdown-item v-if="deletable" icon="trash" @click.stop="deleteTranslationOpen(id, language)">
                        {{ $t('delete') }} {{ language.code.toUpperCase() }}
                      </k-dropdown-item>
                      <k-dropdown-item v-if="revertable" icon="refresh" @click.stop="revertTranslationOpen(id, language)">
                        {{ $t('revert') }} {{ language.code.toUpperCase() }}
                      </k-dropdown-item>
                    </k-button-group>
                  </k-dropdown-content>
                </k-dropdown>
              </portal>
          </k-button-group>
          
          <k-button-group v-if="!compactModeEnabled" class="k-translations-options">
            <k-button v-if="deletable" icon="trash" @click.stop="deleteTranslationOpen(id, language)">
              {{ $t('delete') }} {{ language.code.toUpperCase() }}
            </k-button>
            <k-button v-if="revertable" icon="refresh" @click.stop="revertTranslationOpen(id, language)">
              {{ $t('revert') }} {{ language.code.toUpperCase() }}
            </k-button>
          </k-button-group>

          <k-dialog
            ref="deleteDialog"
            :button="$t('delete')"
            theme="negative"
            icon="trash"
            @submit="deleteTranslationSubmit(id, language)"
          >
            <k-text v-html="$t('flokosiol.translations.delete.confirm', { code: language.code.toUpperCase() })" />
          </k-dialog>

          <k-dialog
            ref="revertDialog"
            :button="$t('revert')"
            theme="negative"
            icon="refresh"
            @submit="revertTranslationSubmit(id, language)"
          >
            <k-text v-html="$t('flokosiol.translations.revert.confirm', { code: language.code.toUpperCase() })" />
          </k-dialog>
        </div>
      `
    }
  }
});
