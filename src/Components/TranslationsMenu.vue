<template>
  <k-translations
    v-else
    v-bind="getTranslationsProviderPropsBinding()"
  />
</template>

<script>

import TranslationsProvider from "./TranslationsProvider.js";
export const TranslationsProviderSelf = { ...TranslationsProvider };

// This component replaces the default kirby header translations menu by a translations section !
export default {

  mixins: [
    TranslationsProviderSelf,
  ],
  name: 'k-languages-dropdown', // Vue component name
  data: function(){

    return {
      // Override defaults
      replaceKirbyLangs: true,
      isInHeader: true,
      showLoader: true,
      deletable: true,
      revertable: true,
    };
  },
  props: {
    devInfo: {
      // Vue-Dev-info property to clarify that this is  not the original k-languages-dropdown
      type: String,
      default: "Warning: I'm not the default k-languages-dropdown.\n I have been replaced by a k-translations-dropdown !",
    },
  },
  methods: {
    load() {
      return this.$api.get(this.apiUrl);
    },
  }
};
</script>

<style lang="less">

</style>
