<template>
    <Layout>
      <Head title="Preview Temporary Licence" />
     
    <div class="container-fluid mt-6">
    <!-- <Banner/> -->
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row">
    <div class="col-lg-6 col-7">
    <h6 class="mb-1">{{ form.company_name }}</h6>
    </div> 
    <!-- <div class="col-lg-6 col-5 my-auto text-end">
      <button @click="mergeAndDownload" type="button" class="btn btn-sm btn-dark mx-2"> <i class="fa fa-download text-md"></i>
       Merge And Download
      </button>
    
    </div> -->
    </div>

<div class="row" >

<PreviewTextComponent
   :value="form.trading_name"
   label="Trading Name"
   type="text"
/>

<PreviewTextComponent
v-if="form.belongs_to == 'Company'"
   :value="form.company"
   label="Applicant"
   type="text"
/>

<PreviewTextComponent
   v-if="form.belongs_to == 'Individual'"
      :value="form.person"
      label="Applicant"
      type="text"
/>

<PreviewTextComponent
   :value="getLicenceTye.licence_type"
   label="Licence Type"
   type="text"
/>

<PreviewTextComponent
   :value="form.licence_date"
   label="Licence Date"
   type="text"
/>

<PreviewTextComponent
   :value="form.licence_number"
   label="Licence Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.old_licence_number"
   label="Old Licence Number"
   type="text"
/>



<PreviewTextComponent
   :value="form.address"
   label="Address Line 1"
   type="text"
/>

<PreviewTextComponent
   :value="form.address2"
   label=" Address Line 2"
   type="text"
/>

<PreviewTextComponent
   :value="form.address3"
   label="Address Line 3"
   type="text"
/>

<PreviewTextComponent
   :value="form.province"
   label="Province"
   type="text"
/>

<PreviewTextComponent
   :value="form.postal_code"
   label="Postal Code"
   type="text"
/>
<PreviewTextComponent
   :value="form.board_region"
   label="Liquor Board Region"
   type="text"
/>
<PreviewTextComponent
   :value="form.latest_renewal"
   label="Latest Renewal"
   type="text"
/>

<PreviewTextComponent
   :value="form.renewal_amount"
   label="Renewal Amount"
   type="text"
/>



        

  <div class="col-md-12" >
   <template v-if="licence.licence_documents">      
        <div v-for="doc in licence.licence_documents" :key="doc.id" class="mb-2">
          <iframe v-if="doc.document_type === 'Original-Licence'" :src="`${$page.props.blob_file_path}${doc.document_file}`" 
          frameborder="0" width="100%" height="600px"></iframe>

          <iframe v-if="doc.document_type === 'Duplicate-Licence'" :src="`${$page.props.blob_file_path}${doc.document_file}`" 
          frameborder="0" width="100%" height="600px"></iframe>

          <iframe v-else-if="doc.document_type === 'Payment To The Liquor Board' && !hasOriginalLicence(licence.licence_documents)" 
          :src="`${$page.props.blob_file_path}${doc.document_file}`" frameborder="0" width="100%" height="600px"></iframe>

          <!-- Latest Renewal -->
          <iframe v-if="doc.document_type === 'Latest Renewal'" :src="`${$page.props.blob_file_path}${doc.document_file}`" 
          frameborder="0" width="100%" height="600px"></iframe>

          <iframe v-else-if="doc.document_type === 'Payment To The Liquor Board'" 
          :src="`${$page.props.blob_file_path}${doc.document_file}`" frameborder="0" width="100%" height="600px"></iframe>

          <!-- Latest appointment of managers(Noms) certificate issued -->
          <!-- <template v-if="licence.nominations" v-for="nom in licence.nominations">            
            <div v-if="nom.nomination_documents" v-for="nomDoc in nom.nomination_documents" :key="nomDoc.id">

            
            <iframe v-if="doc.document_type === 'Latest Renewal'" :src="`${$page.props.blob_file_path}${doc.document}`" 
            frameborder="0" width="100%" height="600px"></iframe>

            <iframe v-else-if="doc.document_type === 'Application Lodged'" 
            :src="`${$page.props.blob_file_path}${doc.document_name}`" frameborder="0" width="100%" height="600px"></iframe>
         </div>
         </template> -->


         <!-- Transfers -->
         <template v-if="transfer" v-for="transDoc in transfer?.transfer_documents" :key="transDoc.id"> 
            
            <iframe v-if="transDoc.doc_type === 'Transfer Issued'" :src="`${$page.props.blob_file_path}${transDoc.document}`" 
            frameborder="0" width="100%" height="600px"></iframe>

            <iframe v-else-if="transDoc.doc_type === 'Transfer Logded'" 
            :src="`${$page.props.blob_file_path}${transDoc.document}`" frameborder="0" width="100%" height="600px"></iframe>
        
         </template>

         <!-- Alterations -->
         <template v-if="licence?.alterations" v-for="alteration in licence.alterations">            
            <div v-if="alteration?.documents" v-for="altDoc in alteration.documents" :key="altDoc.id">

            
            <iframe v-if="altDoc.doc_type === 'Alterations Certificate Issued'" :src="`${$page.props.blob_file_path}${altDoc.path}`" 
            frameborder="0" width="100%" height="600px"></iframe>

            <iframe v-else-if="altDoc.doc_type === 'Alterations Lodged'" 
            :src="`${$page.props.blob_file_path}${altDoc.path}`" frameborder="0" width="100%" height="600px"></iframe>
         </div>
         </template>

        </div>
      
    </template>
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
import { ref,computed } from 'vue';
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
    transfer: Object,
    licenceTypes: Object,
  },
  setup(props) {

    const { notifySuccess, notifyError } = useToaster();
 
      let showMenu = ref(false);

      const form = useForm({
         trading_name: props.licence.trading_name,
         licence_type: props.licence.licence_type_id,
         belongs_to: props.licence.belongs_to,
         is_licence_active: props.licence.is_licence_active,
         licence_number: props.licence.licence_number,
         old_licence_number: props.licence.old_licence_number,
         postal_code: props.licence.postal_code,
         address: props.licence.address,
         address2: props.licence.address2,
         address3: props.licence.address3,
         province: props.licence.province,
         licence_date: props.licence.licence_date,
         board_region: props.licence.board_region,
         renewal_amount: props.licence.renewal_amount,
         latest_renewal: props.licence.latest_renewal,
         company: props.licence.belongs_to === 'Company' ? props.licence.company.name : '',
         person: props.licence.belongs_to === 'Individual' ? props.licence.people.full_name : '',
         company_id: props.licence.belongs_to === 'Company' ? props.licence.company.id : '',
         person_id: props.licence.belongs_to === 'Individual' ? props.licence.people.id : '',
         change_company: '',  
    })

    
 
        const previewTemp = () =>{

          let endpoint = `/preview-temp-licence/${props.licence.slug}`
          window.open(endpoint,'_blank');
        }

const getLicenceTye = computed(() => {
  return props.licenceTypes.find(licenceType => licenceType.id === props.licence.licence_type_id) 
})


const filterDocs = (docs) => {
   return docs.filter(doc =>doc.document_type === 'Original-Licence' || doc.document_type === 'Duplicate-Licence' || doc.document_type === 'Payment To The Liquor Board'
   );
   
}
const hasOriginalLicence = (docs) => {
      return docs.some(doc => doc.document_type === 'Original-Licence');
    }

    return {
      getLicenceTye,
      showMenu,previewTemp,
      form,filterDocs,
      hasOriginalLicence
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
    