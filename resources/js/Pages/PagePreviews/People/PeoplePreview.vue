<template>
    <Layout>
      <Head title="Preview Person" />
     
    <div class="container-fluid mt-6">
    <!-- <Banner/> -->
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row">
    <div class="col-lg-6 col-7">
    <h6 class="mb-1">{{ person.full_name }} - {{ person.id_or_passport }}</h6>
    </div> 
    <div class="col-lg-6 col-5 my-auto text-end">
      <button @click="mergeAndDownload" type="button" class="btn btn-sm btn-dark mx-2"> <i class="fa fa-download text-md"></i>
       Merge And Download
      </button>
    
    </div>
    </div>


    
    <!-- <template v-slot:pdf-content> -->
    
    <div class="row" >
     
     
    
<PreviewTextComponent
   :value="person.full_name"
   label="Full Name And Surname"
/>
<div class="row">
  <div class="col-md-6">ID/Passport Number</div>
  <div class="col-md-6">
    <input 
      type="text"
      v-model="form.id_or_passport"
      class="form-control"
      placeholder="ID/Passport Number"
    />
  </div>
</div>

<div class="row">
  <div class="col-md-6">Date of Birth</div>
  <div class="col-md-6">
    <input 
      type="text"
      v-model="form.date_of_birth"
      class="form-control"
      placeholder="Date of Birth"
      :disabled="true"
    />
  </div>
</div>

<div class="row">
  <div class="col-md-6">Email Address</div>
  <div class="col-md-6">
    <input 
      type="email"
      v-model="form.email_address_1"
      class="form-control"
      placeholder="Email Address"
    />
  </div>
</div>

<div class="row">
  <div class="col-md-6">Email Address</div>
  <div class="col-md-6">
    <input 
      type="email"
      v-model="form.email_address_2"
      class="form-control"
      placeholder="Email Address"
    />
  </div>
</div>

<div class="row">
  <div class="col-md-6">Phone Number</div>
  <div class="col-md-6">
    <input 
      type="text"
      v-model="form.cell_number"
      class="form-control"
      placeholder="Phone Number"
    />
  </div>
</div>

<div class="row">
  <div class="col-md-6">Work Number</div>
  <div class="col-md-6">
    <input 
      type="tel"
      v-model="form.telephone"
      class="form-control"
      placeholder="Work Number"
    />
  </div>
</div>


        

         <div class="col-md-12" >
            <div v-for="docType in ['ID Document', 'Passport', 'Work Permit', 'Police Clearance']" :key="docType" class="mb-2">
                <iframe v-if="checkDocType(docType)?.id" :src="`https://slotowstorage.blob.core.windows.net/${checkDocType(docType)?.docPath}`" frameborder="0" width="100%" height="600px"></iframe>
              </div>
         </div>
</div>
        
      <!-- <iframe :src="`${$page.props.blob_file_path}${filePath.docPath}`" frameborder="0" width="100%" height="600px"></iframe> -->
    </div>
    </div>
    
  
    </Layout>
    </template>
    
    
    <style>
        .columns{
          margin-bottom: 1rem;
        }
        #active-checkbox{
          margin-top: -3px;
          margin-left: 3px;
        }
        .display-text-length{
          margin-left: 10rem;
          font-size: 14px;
        }
        .fa-file-pdf{
          font-size: 30em;
        }
    </style>

    <script>
   import Layout from '../../../Shared/Layout.vue';
   import { ref } from 'vue'
   import { useForm,Link, Head } from '@inertiajs/inertia-vue3';
   import PreviewTextComponent from '../components/PreviewTextComponent.vue';
   import Vue3Html2pdf from 'vue3-html2pdf'

   export default {
    name: 'PeoplePreview',
       props: {
           person: Object,
           errors: Object
       },

       setup(props) {

        const form = useForm({
        name: props.person.full_name,
            date_of_birth: props.person.date_of_birth,
            id_or_passport: props.person.id_or_passport,
            email_address_1: props.person.email_address_1,
            email_address_2: props.person.email_address_2,
            cell_number: props.person.cell_number,
            telephone: props.person.telephone,
            valid_saps_clearance: props.person.valid_saps_clearance,
            saps_clearance_valid_until: props.person.saps_clearance_valid_until,
            passport_valid_until: props.person.passport_valid_until,
            valid_fingerprint: props.person.valid_fingerprint,
            fingerprint_valid_until: props.person.fingerprint_valid_until,
            active: props.person.active,
            slug: props.person.slug,      
            });
            const html2Pdf = ref(null)
            const mergeAndDownload = () => {
                // alert("WooooHoooooo")
       
      const options = {
        filename: 'example.pdf', // Change the filename as needed
        image: { type: 'jpeg', quality: 0.98 }, // Optional
        html2canvas: {}, // Optional
        jsPDF: {} // Optional
      };
      html2Pdf.download()
            }

            const checkDocType = (doc_type) => {
                if (!props.person.people_documents) {
                return {}; 
            } else {
                let people_documents = props.person.people_documents; 

                const foundDocument = people_documents.find(
                    (doc) =>
                        doc.people_id === props.person.id &&
                        doc.doc_type === doc_type &&
                        doc.path &&
                        doc.document_name &&
                        doc.id
                );

                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.path,
                        id: foundDocument.id,
                    };
                } else {
                    return {};
                }
            }
            }

           return {form,mergeAndDownload,checkDocType,html2Pdf}
       },

       components: {
           Layout,
           PreviewTextComponent,
           Vue3Html2pdf
       }
   }
</script>

    
    