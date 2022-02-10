
// Utility for providing dynamic variables to k-translations
export default {

  data: function() {
    return {
      isLoading: false,
      replaceKirbyLangs: true,
      translationStatuses: [], // Array of translation statuses
      deletable: false,
      revertable: false,
      isInHeader: false,
      showLoader: false,
      compactMode: false,
      label: null,
      contentID: null,
    };
  },
  async created(){
  //async mounted() { // Needs to wait for mounted to inherit props before requesting
    await this.reload();
  },
  computed: {
    apiUrl(){
      let url = this.$view?.props?.model?.link;
      return  (url && url!='') ? (''+url+'/translations-info') : 'plugin-translations/load-header';
    },
  },
  methods: {
    reload() { // Note: cannot be async with mixins...
      this.isLoading = true;
      let name = this.name;
      let me = this;
      const response = this.load().then(function(response){
        me.onLoad(response);
      }).catch((error)=>{
        // todo : Handle request failed.
        console.log('ERROR! on loading translations =', error, ', component = ',me);
      }).finally( () => {
        this.isLoading = false;
      });
    },
    onLoad(response){

      if(response.options?.header?.replaceKirbyLanguages >= 0){
        this.replaceKirbyLangs = response.options.header.replaceKirbyLanguages;
      }
      if(response.translations){
        this.translationStatuses = response.translations; // Not reactive with arrays/objects
        //this.$set(this, 'translationStatuses', response.translations); // Reactive ?
      }
      if(response.deletable){ // Parses options from section / field response
        console.log('deletable=', response.deletable, this);
        this.deletable = response.deletable;
      }
      if(response.revertable){ // Parses options from section / field response
        this.revertable = response.revertable;
      }
      if(response.compactMode){ // Parses options from section / field response
        this.compactMode = response.compactMode;
      }
      if(response.label){ // Parses options from section / field response
        this.label = response.label;
      }
      if(response.id){
        this.contentID = response.id;
      }
    },
    // Load fallback, hopefully replaced by component
//     load() {
//       return this.$api.get(this.apiUrl);
//     },
    getTranslationsProviderPropsBinding(){
      return {
        translationStatuses:  this.translationStatuses,
        isInHeader:           this.isInHeader,
        showLoader:           this.showLoader,
        isLoading:            this.isLoading,
        replaceKirbyLangs:    this.replaceKirbyLangs,
        deletable:            this.deletable,
        revertable:           this.revertable,
        label:                this.label,
      };
    },
  }
};
