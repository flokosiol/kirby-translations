<template>

  <div :class="{'k-translations':true, 'k-translations-header':isInHeader, 'k-button-group':isInHeader}">

    <slot
      v-if="!isInHeader"
      name="above"
      v-bind:defaultLanguage="formattedDefaultLanguage"
      v-bind:actualLanguage="formattedActualLanguage"
      v-bind:allLanguages="allLanguages"
      v-bind:alternativeLanguages="alternativeLanguages"
    />

    <button
      v-if="isLoading && showLoader"
      class="k-button k-translations-loader"
    >
      <k-loader class="k-translations-loader-icon"/>
      <span class="k-button-text">{{ $t('daandelange.translations.loading') }}</span>
    </button>

    <k-original-languages-dropdown
      v-else-if="!replaceKirbyLangs"
    />
    <k-button-group v-else class="k-translations-buttons">

      <!-- One button per language -->
      <k-translations-button
        v-if="allLanguages"
        v-for="lang in allLanguages"
        :key="'btn-'+lang.code"
        :language="lang"
        :allowMenu="allowMenus||isInHeader"
        @changeLanguage="change(lang)"
        @deleteLanguage="deleteTranslationOpen(lang)"
        @revertLanguage="revertTranslationOpen(lang)"
      />

      <slot
        name="extrabuttons"
        v-bind:defaultLanguage="formattedDefaultLanguage"
        v-bind:actualLanguage="formattedActualLanguage"
        v-bind:allLanguages="allLanguages"
        v-bind:alternativeLanguages="alternativeLanguages"
      />

    </k-button-group>

    <div v-if="$scopedSlots.below && !isInHeader" class="k-translations-below">
    <slot
      name="below"
      v-bind:defaultLanguage="formattedDefaultLanguage"
      v-bind:actualLanguage="formattedActualLanguage"
      v-bind:allLanguages="allLanguages"
      v-bind:alternativeLanguages="alternativeLanguages"
    />
    </div>


    <k-dialog
      ref="deleteDialog"
      :button="$t('delete')"
      theme="negative"
      icon="trash"
      @submit="deleteTranslationSubmit(dialogLanguage)"
    >
      <k-text v-html="$t('daandelange.translations.delete.confirm', { code: language.code.toUpperCase() })" />
    </k-dialog>

    <k-dialog
      ref="revertDialog"
      :button="$t('revert')"
      theme="negative"
      icon="refresh"
      @submit="revertTranslationSubmit(dialogLanguage)"
    >
      <k-text v-html="$t('daandelange.translations.revert.confirm', { code: language.code.toUpperCase() })" />
    </k-dialog>

  </div>
</template>



<script>

// Note: With non-Fiber installations, this method will probably fail (hardcoded on compile time?)
//import LanguagesDropdown from "@KirbyPanel/components/Navigation/Languages.vue";

import TranslationsButton from "./TranslationsButton.vue";

export default {

  // [k-languages-dropdown] Gets: computed.defaultLanguage, computed.language, computed.languages, methods.change
  // These 4 components behave differently before and after K3.6.
  // Re-using them as native components saves us the hasle of fetching them in different ways depending on the kirby installation.
  extends: 'k-languages-dropdown', // For reference, see panel/src/components/navigation/languages.vue

  data() {
    return {
      dialogLanguage: null,
    };
  },
  components: {
    'k-translations-button' : TranslationsButton,
  },
  props: {
    allowMenus: { // Allow menus
      type: Boolean,
      default: false,
    },
    isInHeader: { // Header mode = Force menus and some custom styles
      type: Boolean,
      default: false,
    },
    showLoader: { // To disable showing the loader
      type: Boolean,
      default: false,
    },
    isLoading: { // To control the loading state. (experimental)
      type: Boolean,
      default: false,
    },
    replaceKirbyLangs: { // To replace the kirby languages menu
      type: Boolean,
      default: false,
    },
    deletable: { // Disables deletion
      type: Boolean,
      default: false,
    },
    revertable: { // Disables reverting
      type: Boolean,
      default: false,
    },
    translationStatuses: { // Holds current translation statuses
      type: Array,
      default: function(){return[];},
    },
  },
  computed: {
    // Languages fetching
    // Note: this.languages only holds translations (non-default lang)
    alternativeLanguages() { // Aka. content translations
      return this.formatLanguages(this.sortLanguages(this.languages ?? []));
    },
    allLanguages(){
      return this.formatLanguages([this.defaultLanguage, ...this.sortLanguages(this.languages ?? []) ]);
    },
    formattedDefaultLanguage(){
      return this.formatLanguages([this.defaultLanguage], true);
    },
    formattedActualLanguage(){
      return this.formatLanguages([this.language], true);
    },
    // Utilities
    hasFiber() {
      return window.panel && window.panel.$languages;
    },
    canDelete(){
      return this.deletable;
    },
    canRevert(){
      return this.revertable;
    },
    compactModeEnabled() {
      return this.compactmode;// || this.isPortaled;
    },
    modelUrl(){
      let url = this.$view?.props?.model?.link;
      return  (url && url!='') ? (''+url+'') : null;
    },
  },
  methods: {
    sortLanguages(langs){
      return langs.sort((a,b)=>{
        // Default lang first
        if(a.default) return -999; // move to front
        if(b.default) return 1; // move one back = Compare next
        // Sort alphabetically by name
        return ((a.name<b.name)?-1:((a.name>b.name)?1:0));
      });
    },
    // Languages helpers
    languageIsTranslated(lang){
      if(lang.default) return true; // default lang is always translated!
      if(!this.translationStatuses || !(this.translationStatuses.length > 0) ) return null; // When translations are not set, we don't know the status
      return true && (this.translationStatuses?.some( (translation) => (translation.code === lang.code) && (translation.file) )); // todo: also do something with lang.needsreview ?
    },
    formatLanguages(langs, returnSingle=false) {
      if (langs && langs.length) {
        for (let i = 0; i < langs.length; i++) {
          langs[i].isTranslated = this.languageIsTranslated(langs[i]);

          // Translated
          if ( langs[i].isTranslated === true ) {
            langs[i].icon = 'toggle-on';
            langs[i].theme = 'positive';
          }
          // Not translated
          else if( langs[i].isTranslated === false ) {
            langs[i].icon = 'toggle-off';
            langs[i].theme = 'negative';
          }
          // unknown
          else {
            langs[i].icon = 'globe';
            langs[i].theme = 'unknown';
          }

          //langs[i].isCurrent = (this.language.code === langs[i].code);
          langs[i].isCurrent = (this.language.code === langs[i].code);
          langs[i].isDeleteable = this.canDelete && !langs[i].default && langs[i].isTranslated;
          langs[i].isRevertable = this.canRevert && !langs[i].default;

          // return early ?
          if(returnSingle) return langs[i];
        }
        return langs;
      }
      if(returnSingle) return null;
      return [];
    },
    deleteTranslationOpen(language) {
      this.dialogLanguage = language;
      this.$refs.deleteDialog?.open();
    },
    deleteTranslationSubmit(language) {
      if(!(language?.code)) return;
      this.$api.post('plugin-translations/delete', {id: this.modelUrl, languageCode: language.code})
        .then(response => {
          this.$refs.deleteDialog.close();
          if (response.code === 200) {
            this.$store.dispatch('notification/success', response.text);
            this.change(this.defaultLanguage);
          }
          else {
            this.$store.dispatch('notification/error', response.text);
          }
        })
        .catch(error => {
          this.$store.dispatch('notification/error', error);
        });
    },
    revertTranslationOpen(language) {
      this.dialogLanguage = language;
      this.$refs.revertDialog?.open();
    },
    revertTranslationSubmit(language) {
      if(!(language?.code)) return;
      this.$api.post('plugin-translations/revert', {id: this.modelUrl, languageCode: language.code})
        .then(response => {
          this.$refs.revertDialog.close();
          if (response.code === 200) {
            this.$store.dispatch('notification/success', response.text);

            if (this.hasFiber) this.$go(this.$view.path);
            //else // todo
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

  },
};
</script>

<style lang="less">
.k-translations-header, .k-translations-buttons {
  display: inline-block;
  margin: 0!important;
}

.k-translations-loader {
  .k-button-text {
    margin-left: .5rem;
  }
  .k-translations-loader-icon {
    display: inline-block;
  }
}


/* In portal mode, adding more languages to the headerbar breaks the layout. Ensure our buttons remain small. * /
@media screen and (min-width: 50em) {
  .k-button[data-responsive] .k-button-text .shortname {
    display: none;
  }
  .k-button[data-responsive] .k-button-text .longname {
    display: inline-block;
  }
}
/* Kirby's default responsive rulez from 30 to 35em * /
@media screen and (min-width: 30em) {
  .k-translations-hide-kirbylangs .k-header .k-button[data-responsive]:not(.k-translations-button) .k-button-text {
    display: none;
  }
  .k-translations-button.k-button[data-responsive] .k-button-text {
    display: inline;
  }
}
@media screen and (min-width: 35em) {
  .k-translations-hide-kirbylangs .k-header .k-button[data-responsive]:not(.k-translations-button) .k-button-text {
    display: inline-block;
  }
}
@media screen and (min-width: 40em) {
  .k-translations-button .k-icon {
    display: inherit;
  }
  .k-translations-button .k-button-text {
    display: inherit;
  }
  [dir=ltr] .k-translations-button .k-button-icon~.k-button-text {
    padding-left: 0.5rem;
  }
}

/*.k-button-group > .k-button.translations--active::after {
  content: '';
  display: block;
  width: 100%;
  height: 2px;
  background-color: #999;
  margin-top: .5rem;
}*/
</style>
