<template>

  <!-- Button without submenu -->
  <k-button
    v-if="!hasMenu"
    :data-langcode="language.code"
    :icon="computedLanguage.icon"
    :class="getLangButtonClasses()"
    :theme="language.theme"
    :key="language.code"
    :responsive="hasMenu"
    :click="languageClick"
  >
    <span class="longname">{{ language.name }}</span>
    <span class="shortname">{{ language.code }}</span>
  </k-button>

  <!-- Button with submenu -->
  <k-dropdown
    v-else
    :key="language.code"
  >
    <!-- Clickable button -->
    <k-button
      :ref="'translations-button-'+language.code"
      :data-langcode="language.code"
      :icon="language.icon"
      :class="getLangButtonClasses()"
      :theme="language.theme"
      :responsive="hasMenu"
      :click="languageClick"
    >
      <span class="longname">{{ language.name }}</span>
      <span class="shortname">{{ language.code }}</span>
      <!-- note: clicks are ignored on k-icon ! -->
      <span v-if="hasMenu" @click.stop="toggleMenu" class="k-translations-menu-icon-link" ><k-icon class="k-translations-menu-icon" type="angle-down" /></span>
    </k-button>

    <!-- Language menu + options -->
    <k-dropdown-content v-if="hasMenu" class="k-translations-options" :ref="'menu-toggle-'+language.code" data-theme="light">

      <k-button icon="globe" class="k-dropdown-item k-translations-menu-title k-translations-button no-focus" @click.stop :disabled="true">
        <h3>{{ language.name }} ({{ language.code }})
          <span v-if="language.default" class="k-translations-tag">{{$t('daandelange.translations.default')}}</span>
          <span v-if="language.isCurrent" class="k-translations-tag">{{$t('daandelange.translations.current')}}</span>
        </h3>
      </k-button>

      <hr v-if="menuItems && menuItems.length>0"/>
      <template v-for="menuItem in menuItems">
        <k-dropdown-item v-if="true"
          :click="function(){if(menuItem.click)menuItem.click();}"
          :icon="menuItem.icon"
          :link="menuItem.link"
          :target="menuItem.target"
          :theme="menuItem.theme"
          class="k-translations-button"
          :disabled="!(menuItem.link || menuItem.click)"
          :focus="()=>{}"
        ><!-- note: focus is needed on k-dropdown-item that resolves to k-button-link which don't have focus(), glitching the naviagtion in K3.6.2 -->
          {{menuItem.text}}
        </k-dropdown-item>
      </template>

      <hr v-if="language.isDeleteable || language.isRevertable" />
      <k-dropdown-item v-if="language.isDeleteable" icon="trash" @click.stop="deleteTranslation()" theme="warning" class="k-translations-button">
        {{ $t('delete') }} {{ language.code.toUpperCase() }}
      </k-dropdown-item>
      <k-dropdown-item v-if="language.isRevertable" icon="refresh" @click.stop="revertTranslation()" theme="negative" class="k-translations-button">
        {{ $t('revert') }} {{ language.code.toUpperCase() }}
      </k-dropdown-item>

    </k-dropdown-content>
  </k-dropdown>

</template>

<script>

// This component represents a single language button

export default {
  name: 'k-translations-button', // Vue component name

  props: {
    language: {
      type: Array|Object,
      required: true,
    },
    allowMenu: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    hasMenu() {
      return this.allowMenu;// && !(this.language?.default) &&  this.language?.isCurrent ;// todo: restore this: && (this.language.isRevertable || this.language.isDeleteable);
    },
    menuItems(){
      //if(!this.hasMenu) return []; // exit early
      let items = [];
      if(!this.language.isCurrent)
        items.push( {
          icon: 'edit',
          text: 'Switch to '+ this.language.name,
          click: ()=>this.languageClick(this.language),
        });
      if( this.$permissions.pages?.preview && this.$view.props?.model?.previewUrl )
        items.push( {
          icon: 'preview',
          text: 'Visit this page in '+this.language.name,
          link: this.$view.props?.model?.previewUrl,
          target: '_blank',
        });
      //if(true)
        items.push( {
          icon: 'cog',
          text: 'Edit language in panel',
          link: 'languages?language='+(this.$translation.code),
        });
      //if(true)
        items.push( {
          icon: 'document',
          text: 'Status: '+(this.language.isTranslated===true? 'Translated' : (this.language.isTranslated===false ? 'Not translated' : 'Unknown')),
          theme: (this.language.isTranslated===true? 'positive' : (this.language.isTranslated===false ? 'negative' : 'unknown')),
        });
      //if(this.language.default)
      //  items.push( { icon:'check', text: 'Default language' } );
      //if(this.language.isCurrent)
      //  items.push( { icon:'check', text: 'Currently active language' } );
      return items;
    },
    computedLanguage(){
      return this.language;
    }
  },
  methods: {
    // Applies custom styles for a particular language
    getLangButtonClasses(extra = {}) {
      if(!this.language) return extra;
      return {
        'k-translations-default'                      : this.language.default,            // default language
        'k-translations-active'                       : this.language.isCurrent??false,   // mark active
        'k-translations-button'                       : true,                             // global style element
        ['k-translations-button-'+this.language.code] : (this.language.code?.length > 0), // custom style element
        'k-translations-button-compact'               : this.allowMenu,                   // Styles for compactmode
        ...extra
      };
    },
    // UI helpers

    // So the parent can receive the clicks
    toggleMenu(){
      if(this.hasMenu) this.$refs['menu-toggle-'+this.language.code]?.toggle();
      return false; // needed to intercept events
    },

    // Tiggered by childs to close the menu
    closeMenu(){
      if(this.hasMenu) this.$refs['menu-toggle-'+this.language.code]?.close();
    },

    revertTranslation(){
      this.$emit('revertLanguage', this.language);
    },

    deleteTranslation(){
      this.$emit('deleteLanguage', this.language);
    },
    languageClick() {
      // Clicking on the current lang toggles the submenu
      if (this.language.isCurrent) {
        // Only toggle if hasMenu
        if(this.hasMenu) this.toggleMenu();
      }
      // Trigger change language
      else{
        this.$emit('changeLanguage', this.language);
      }
    },
  },
};
</script>

<style lang="scss">
/* Our buttons always have text but their icons can be hidden (overrides some Kirby css too) */
.k-translations-button {
  $btnRoot: &;

  .k-button-text {
    --theme: var(--color-text);
    color: var(--theme);

    #{$btnRoot}:hover & {
      //color: red;
    }
  }

  &.k-button[data-responsive] .k-button-text {
    //display: inline;
  }

  .shortname {
    display: none;
    text-transform: capitalize;

    #{$btnRoot}.k-translations-button-compact & {
      display: inline;
      text-transform: capitalize;
    }
  }
  .longname {
    display: inline;

    #{$btnRoot}.k-translations-button-compact & {
      display: none;
    }
  }

  .k-translations-menu-icon-link {
    display: inline-block;
  }

  .k-translations-menu-icon {
    display: none;
    color: var(--color-text-light);
    display: inline-block;
    padding: .5rem;
    padding-right: 0;
    //display: none;

    &:hover {
      color: var(--color-text);
      //background-color: var(--color-gray-300);
    }

    #{$btnRoot}.k-translations-button-compact & {
      display: inline;
    }
  }

  .k-button-icon {
    //margin-left: .5rem;
  }

  &.k-translations-active {
    //background-color: rgba(255,255,255,.3);
    background-color: var(--field-input-background);
    //border-top: 2px solid black;
    //border-color: var(--border-color);

    .k-button-text {
      opacity: 1;
    }
  }
  &.k-translations-default {
    > .k-button-text {
      //text-decoration: underline;

      &::after {
        //position: absolute;
        //float: right;
        display: inline-block;
        content:"*";
      }
    }
    > .k-button-icon {
      //border: 1px solid var(--color-text-light);
      //border-radius: 1rem;
      //background-color: var(--color-gray-300);//#e9e9e9; /* Same as .k-tabs from layout/tabs.vue */
      //padding: 3px;
      //color: var(--color-white);
    }
  }
  // No select on click
  &.no-focus:focus {
    box-shadow: none;
    outline: none;
    cursor: default;

    &:hover, &:focus {
      .k-button-text {
        opacity: .75; // cancels hover effect
      }
    }
  }
}
/* Cancel "data-disabled" as hidden */
.k-translations-button.k-button[data-disabled]{
  opacity: 1;
}
.k-button-group.k-translations-buttons {
  &, &>.k-dropdown {
    > .k-translations-button {
      padding: calc( 0.5 * var(--padding-y) ) var(--padding-x);
      //color: red;
    }
  }
}

[dir=ltr] .k-translations-button .k-button-icon ~ .k-button-text {
  //padding-left: 0;
}

.k-button-group .k-dropdown-content.k-translations-options {
  margin-left: 0;
}


.k-dropdown-content.k-translations-options {
  > hr {
    border-top: 1px solid var(--color-text);
    opacity: .5;
    border-bottom: 1px solid var(--field-input-border);
  }
}

.k-dropdown-item.k-translations-menu-title {
  //border-bottom: 1px solid var(--color-text);
/*
  opacity: 1;
  cursor: default;
*/

  .k-button-text {
    opacity: 1;
  }

  h3 {
    //width: 100%;
  }

  .k-translations-tag {
    text-transform: uppercase;
    background-color: var(--color-text-light);
    color: var(--color-background);
    font-size: .5rem;
    padding: .25rem;
    display: inline-flex;
    margin-left: .5rem;
    vertical-align: middle;
  }

}

/* New theme colors */
[data-theme=unknown] {
  --theme: var(--color-text-light);
  --theme-light: var(--color-light);
  //--theme-bg: var(--color-gray-300);
}
[data-theme=warning] {
  --theme: var(--color-notice);
  --theme-light: var(--color-notice-light);
  //--theme-bg: var(--color-gray-300);
}

</style>
