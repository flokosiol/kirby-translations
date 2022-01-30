import TranslationsSection from "./components/TranslationsSection.vue";

panel.plugin('daandelange/translations', {
  components: {
    TranslationsSection,
  },
  sections: {
    translations: TranslationsSection,
  },
});
