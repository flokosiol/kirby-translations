panel.plugin('flokosiol/translations', {
  sections: {
    translations: {
      data: function () {
        return {
          deletable: null,
          revertable: null,
          translations: null
        }
      },
      computed: {
        defaultLanguage() {
          return this.$store.state.languages.default;
        },
        language() {
          return this.$store.state.languages.current;
        },
        languages() {
          return this.$store.state.languages.all.filter(language => language.default === false);
        }
      },
      created: function() {
        this.load().then(response => {
          this.deletable    = response.deletable;
          this.revertable   = response.revertable;
          this.translations = response.translations;

          // loop through all panel languages
          let languages = this.languages;
          for (let i = 0; i < languages.length; i++) {
            let languageCode = languages[i].code;

            // check if page is translated for this language
            if (this.translations.indexOf(languageCode) >= 0) {
              this.languages[i].icon = 'check';
              this.languages[i].theme = 'positive';
            }
            else {
              this.languages[i].icon = 'add';
              this.languages[i].theme = 'negative';
            }
          }
        });
      },
      methods: {
        change(language) {
          this.$store.dispatch("languages/current", language);
          this.$emit("change", language);
        }
      },
      template: `
        <div class="k-field k-translations-field">
          <div v-if="languages.length">
            <k-button-group>
              <k-button icon="check" theme="positive" :key="defaultLanguage.code" @click="change(defaultLanguage)">
                  {{ defaultLanguage.name }}
              </k-button>
              <k-button v-for="language in languages" :icon="language.icon" :theme="language.theme" :key="language.code" @click="change(language)">
                {{ language.name }}
              </k-button>
            </k-button-group>
          </div>

          <div v-if="deletable">
            {{deletable}}
          </div>
          <div v-if="revertable">
            {{revertable}}
          </div>
        </div>
      `
    }
  }
});