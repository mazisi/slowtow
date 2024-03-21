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

    <!-- <template v-slot:pdf-content> -->
    
<div class="row" >

<PreviewTextComponent
   :value="form.company_name"
   label="Company Name"
   type="text"
/>

<PreviewTextComponent
   :value="form.company_type"
   label="Company Type"
   type="text"
/>
<PreviewTextComponent
   :value="form.reg_number"
   label="Registration Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.vat_number"
   label="Vat Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.website"
   label="Website"
   type="text"
/>


<PreviewTextComponent
   :value="form.email"
   label="Email Address"
   type="text"
/>

<PreviewTextComponent
   :value="form.email1"
   label="Email Address"
   type="text"
/>
<PreviewTextComponent
   :value="form.email2"
   label="Email Address"
   type="text"
/>

<PreviewTextComponent
   :value="form.tel_number"
   label="Phone Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.tel_number1"
   label="Phone Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.business_address"
   label="Business Address Line 1"
   type="text"
/>

<PreviewTextComponent
   :value="form.business_address2"
   label="Business Address Line 2"
   type="text"
/>

<PreviewTextComponent
   :value="form.business_address3"
   label="Business Address Line 3"
   type="text"
/>

<PreviewTextComponent
    v-if="form.belongs_to =='Company'"
   :value="form.reg_number"
   label="Registration Number"
   type="text"
/>

<PreviewTextComponent
   :value="form.business_province"
   label="Province"
   type="text"
/>

<PreviewTextComponent
   :value="form.business_address_postal_code"
   label="Postal Code"
   type="text"
/>
<PreviewTextComponent
   :value="form.postal_address"
   label="Postal Address 1"
   type="text"
/>
<PreviewTextComponent
   :value="form.postal_address2"
   label="Postal Address Line 2"
   type="text"
/>

<PreviewTextComponent
   :value="form.postal_address3"
   label="Postal Address Line 3"
   type="text"
/>

<PreviewTextComponent
   :value="form.postal_province"
   label="Province"
   type="text"
/>

<PreviewTextComponent
   :value="form.postal_code"
   label="Postal Code"
   type="text"
/>


        

  <div class="col-md-12" >
   <template v-if="company.licences" v-for="licence in company.licences" :key="licence.id">
      <div v-if="licence?.licence_documents">
        <div v-for="doc in filterDocs(licence.licence_documents)" :key="doc.id" class="mb-2">
          <iframe v-if="doc.document_type === 'Original-Licence'" :src="`${$page.props.blob_file_path}storage/${doc.document_name}`" frameborder="0" width="100%" height="600px"></iframe>
          <iframe v-else-if="doc.document_type === 'Duplicate-Licence'" :src="`${$page.props.blob_file_path}storage/${doc.document_name}`" frameborder="0" width="100%" height="600px"></iframe>
          <iframe v-else-if="doc.document_type === 'Payment To The Liquor Board' && !hasOriginalLicence(licence.licence_documents)" 
          :src="`${$page.props.blob_file_path}storage/${doc.document_name}`" frameborder="0" width="100%" height="600px"></iframe>
        </div>
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
    company: Object
  },
  setup(props) {
   console.log(props.company);
    const { notifySuccess, notifyError } = useToaster();
 
      let showMenu = ref(false);

      const form = useForm({
          slug: props.company.slug,
          company_name: props.company.name,
          company_type: props.company.company_type,
          business_address: props.company.business_address,
          business_address2: props.company.business_address2,
          business_address3: props.company.business_address3,
          reg_number: props.company.reg_number,
          business_province: props.company.business_province,
          business_address_postal_code: props.company.business_address_postal_code,
          postal_address: props.company.postal_address,
          postal_province: props.company.postal_province,
          postal_code: props.company.postal_code,
          postal_code2: props.company.postal_code2,
          postal_code3: props.company.postal_code3,
          website: props.company.website,
          email: props.company.email,
          email1: props.company.email1,
          email2: props.company.email2,
          phone: props.company.phone,
          tel_number: props.company.tel_number,
          vat_number: props.company.vat_number,
          tel_number1: props.company.tel_number1,
      })
    
 
        const previewTemp = () =>{

          let endpoint = `/preview-temp-licence/${props.company.slug}`
          window.open(endpoint,'_blank');
        }


          const checkDocType = (doc_type) => {
                if (!props.company.temp_documents) {
                return {}; 
            } else {
                let temp_documents = props.company.temp_documents; 

                const foundDocument = temp_documents.find(
                    (doc) =>
                        doc.temporal_licence_id === props.company.id &&
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

const filterDocs = (docs) => {
   return docs.filter(doc =>doc.document_type === 'Original-Licence' || doc.document_type === 'Duplicate-Licence' || doc.document_type === 'Payment To The Liquor Board'
   );
   
}
const hasOriginalLicence = (docs) => {
      return docs.some(doc => doc.document_type === 'Original-Licence');
    }

    return {
      showMenu,previewTemp,
      form,filterDocs,
      checkDocType,hasOriginalLicence
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
    