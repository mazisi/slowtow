<template>
  <button 
     :disabled="canMerge() < 27"
      @click="mergeDocs"
       type="button" 
       class="btn btn-success w-65">
       {{ title }}
      </button>
       <a v-if="licence.merged_document" 
       :href="`/storage/app/public/${licence.merged_document}`" 
       target="_blank" class="btn btn-success w-20 mx-2">View</a>
</template>

<script>
import { computed } from 'vue';

export default{
  
  props: {
    errors: Object,
    success: Object,
    title: String,
    licence: Object
  },
  setup(props, context){
    function mergeDocs(event) {
      context.emit('stage-value-changed', event, props.stageValue);
    }
    // let docCount =  props.licence.documents.filter(doc => doc.num !== null && doc.num !== '');
   

    function canMerge() {
            let baseDocs = [
              "Newspaper Adverts", "GLB Application Forms", "Application Forms","Police Clearance","Tax Clearance","500m Affidavit",
              "Company Documents","CIPC Documents", "ID Documents","LTA Certificate","Shareholding Info","Financial Interests",
              "Government Gazette Adverts","Advert Affidavit","Proof of Occupation","Representations","Menu","Site Plans",
              "Photographs","Municipal Consent Ltr","Zoning Certificate","Mapbook Plans","Google Map Plans","Description",
              "Full Dimensioned Plans","Advert Photographs","Payment To The Liquor Board"
            ];

              const allDocTypesPresent = baseDocs.every(docType => {
                return props.licence.documents.some(document => document.document_type == docType);
              });
              console.log('All',allDocTypesPresent);
              if (allDocTypesPresent) {
                return baseDocs.length;
              } else {
                return 0;
              }

          }
          canMerge()
    return {
      mergeDocs,canMerge
    }
  }
}
</script>