<template>

  <div class="k-translations-field">
    <h2 v-if="label">{{label}}</h2>
    <k-translations
      v-bind="getTranslationsProviderPropsBinding()"
      ref="translations"
    >
      <template v-slot:extrabuttons="translationsData">
          <k-button
            class="k-translations-button"
            @click.stop="$refs.translations.revertTranslationOpen(translationsData.actualLanguage)"
            :text="'Revert '+ translationsData.actualLanguage.name"
            icon="refresh"
            theme="warning"
          />
          <k-button
            class="k-translations-button"
            @click.stop="$refs.translations.deleteTranslationOpen(translationsData.actualLanguage)"
            :text="'Delete '+ translationsData.actualLanguage.name"
            icon="trash"
            theme="negative"
          />
      </template>
    </k-translations>


  </div>

</template>

<script>
import TranslationsProvider from "./TranslationsProvider.js";
export const TranslationsProviderSelf = { ...TranslationsProvider };

export default {
  name: 'k-translations-field',
  mixins: [
    TranslationsProviderSelf,
  ],
};
</script>

<style lang="less">

</style>
