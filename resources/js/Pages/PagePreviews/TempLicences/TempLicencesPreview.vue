<template>
    <Layout>
      <Head title="Preview Temporary Licence" />
     
    <div class="container-fluid mt-6">
    <!-- <Banner/> -->
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row">
    <div class="col-lg-6 col-7">
    <h6 v-if="form.belongs_to == 'Individual'" class="mb-1">{{ form.full_name }} - {{ form.id_or_passport }}</h6>
    <h6 v-else class="mb-1">{{ form.company_name }} - {{ form.reg_number }}</h6>
    </div> 
    <div class="col-lg-6 col-5 my-auto text-end">
      <button @click="mergeAndDownload" type="button" class="btn btn-sm btn-dark mx-2"> <i class="fa fa-download text-md"></i>
       Merge And Download
      </button>
    
    </div>
    </div>


    
    <!-- <template v-slot:pdf-content> -->
    
    <div class="row" >
     
     
    
<!-- <PreviewTextComponent
    v-if="form.belongs_to == 'Company'"
   :value="form.full_name"
   label="Company Name"
   type="text"
/> -->

<PreviewTextComponent
   :value="form.event_name"
   label="Event Name"
   type="text"
/>

<PreviewTextComponent
   :value="returnMomentDate(form.start_date)"
   label="Event Start Date"
   type="date"
/>
<PreviewTextComponent
   :value="form.end_date"
   label="Event Ending Date"
   type="date"
/>

<PreviewTextComponent
   :value="form.end_date"
   label="Event Ending Date"
   type="date"
/>

<PreviewTextComponent
   :value="form.latest_lodgment_date"
   label="Latest Lodgment Date"
   type="text"
/>

<PreviewTextComponent
   :value="form.liquor_licence_number"
   label="Licence Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.address"
   label="Event Address Region"
   type="text"
/>

<PreviewTextComponent
   :value="form.application_type"
   label="Application Type"
   type="text"
/>

<PreviewTextComponent
    v-if="form.belongs_to =='Company'"
   :value="form.company_name"
   label="Company Name"
   type="text"
/>

<PreviewTextComponent
    v-if="form.belongs_to =='Individual'"
   :value="form.person"
   label="Person Name"
   type="text"
/>

<PreviewTextComponent
    v-if="form.belongs_to =='Company'"
   :value="form.reg_number"
   label="Registration Number"
   type="text"
/>

<PreviewTextComponent
    v-if="form.belongs_to =='Individual'"
   :value="form.id_or_passport"
   label="ID Number"
   type="text"
/>




        

  <div class="col-md-12" >
  <div v-if="checkDocType('Licence Issued')?.id" class="mb-2">
    <iframe :src="`https://slotowstorage.blob.core.windows.net/${checkDocType('Licence Issued')?.docPath}`" 
    frameborder="0" width="100%" height="600px"></iframe>
  </div>
  <div v-else-if="checkDocType('Payment To The Liquor Board')?.id" class="mb-2">
    <iframe :src="`https://slotowstorage.blob.core.windows.net/${checkDocType('Payment To The Liquor Board')?.docPath}`" 
    frameborder="0" width="100%" height="600px"></iframe>
  </div>
  <div v-else>
    <p>No document available</p>
  </div>
  </div>
</div>
        
      <!-- <iframe :src="`${$page.props.blob_file_path}${filePath.docPath}`" frameborder="0" width="100%" height="600px"></iframe> -->
    </div>
    </div>
    
  
    </Layout>
    </template>

<script>
import Layout from "../../../Shared/Layout.vue";
import Banner from '../../components/Banner.vue';
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import TextInputComponent from '../../components/input-components/TextInputComponent.vue';
import LiquorBoardRegionComponent from '../../components/input-components/LiquorBoardRegionComponent.vue';
import PreviewTextComponent from '../components/PreviewTextComponent.vue';
import common from '../../common-js/common.js';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../../store/useToaster';
import moment from 'moment'

export default {
 name: "PreviewTemporalLicence",
 props: {
    errors: Object,
    success: String,
    licence: Object,
    people: Array
  },
  setup(props) {
    const { notifySuccess, notifyError } = useToaster();
 
      let showMenu = ref(false);

      const form = useForm({
          slug: props.licence.slug,
          event_name: props.licence.event_name,
          start_date: props.licence.start_date,
          end_date: props.licence.end_date,
          address: props.licence.address,
          application_type: props.licence.application_type,
          liquor_licence_number: props.licence.liquor_licence_number,
          reg_number: props.licence.company ? props.licence.company.reg_number: '',
          id_or_passport: props.licence.people ? props.licence.people.id_or_passport: '',
          belongs_to: props.licence.belongs_to,
          company_name: props.licence.company ? props.licence.company.name : '',
          person: props.licence.people ? props.licence.people.full_name : '',
          latest_lodgment_date: props.licence.latest_lodgment_date
      })
    
  
    function computeLogdementDate(){
      var d = new Date(props.licence.start_date);
      d.setDate(d.getDate() - 14);
      return form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }

 
        const previewTemp = () =>{

          let endpoint = `/preview-temp-licence/${props.licence.slug}`
          window.open(endpoint,'_blank');
        }


          function returnMomentDate(date){
            return date
          }

          const checkDocType = (doc_type) => {
                if (!props.licence.temp_documents) {
                return {}; 
            } else {
                let temp_documents = props.licence.temp_documents; 

                const foundDocument = temp_documents.find(
                    (doc) =>
                        doc.temporal_licence_id === props.licence.id &&
                        doc.doc_type === doc_type &&
                        doc.document &&
                        doc.document_name &&
                        doc.id
                );
                console.log(foundDocument)

                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.document,
                        id: foundDocument.id,
                    };
                } else {
                    return {};
                }
            }
            }

    return {
      showMenu,previewTemp,
      form,returnMomentDate,
      computeLogdementDate,
      checkDocType
    }
  },
  
  computed: {

    computedBoardRegions() {
          return common.getBoardRegions();
        }
  },

  components: {
    Layout,
    LiquorBoardRegionComponent,
    PreviewTextComponent,
    TextInputComponent,
    Banner,
    Head
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
    