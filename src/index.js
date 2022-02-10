import TranslationsSection from "./components/TranslationsSection.vue";
import Translations from "./components/Translations.vue";
import TranslationsMenu from "./components/TranslationsMenu.vue";
import TranslationsField from "./components/TranslationsField.vue";
//import TranslationsButtons from "./components/TranslationsButtons.vue";

panel.plugin('daandelange/translations', {
  components: {
    'k-original-languages-dropdown': {
      extends: 'k-languages-dropdown', // Note: Fragile, it has to extend k-languages-dropdown before k-languages-dropdown does
    },
    //'k-translations-buttons': TranslationsButtons, // In here so that it can extent k-languages-dropdown
    'k-translations': Translations,
    'k-languages-dropdown': TranslationsMenu, // Replaces original kirby component !
    'k-button-link': {extends:'k-button-link',methods:{focus:function(){this.$el.focus();}}} // Replaces kirby component, injecting focus() on 'k-button-link' for K3.6.2
  },
  sections: {
    translations: TranslationsSection,
  },
  fields: [
    TranslationsField
  ],
});
