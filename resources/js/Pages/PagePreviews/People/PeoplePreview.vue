<template>
    <Layout>
      <Head title="View Person" />
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
    
    <div class="row">
         

          <TextInputComponent 
          :inputType="'text'"
          v-model="form.name" 
          :value="form.name"  
          :column="'col-4'" 
          :label="'Full Name And Surname *'" 
          :errors="errors.name"
          :input_id="name"
          :required="true"
        />


        <TextInputComponent 
          :inputType="'text'"
          v-model="form.id_or_passport"  
          :value="form.id_or_passport" 
          :column="'col-4'" 
          :label="'ID/Passport Number'" 
          :errors="errors.id_or_passport"
          :input_id="id_or_passport"
        />

        <TextInputComponent 
          :inputType="'text'"
          v-model="form.date_of_birth"  
          :value="form.date_of_birth" 
          :column="'col-4'" 
          :label="'Date of Birth'" 
          :errors="errors.date_of_birth"
          :input_id="date_of_birth"
          :disabled="true"
        />

        <TextInputComponent 
            :inputType="'email'"
            v-model="form.email_address_1" 
            :value="form.email_address_1"  
            :column="'col-4'" 
            :label="'Email Address'" 
            :errors="errors.email_address_1"
            :input_id="email_address_1"
         />

                      
                      
         <TextInputComponent 
            :inputType="'email'"
            v-model="form.email_address_2"
            :value="form.email_address_2"  
            :column="'col-4'" 
            :label="'Email Address'" 
            :errors="errors.email_address_2"
            :input_id="email_address_2"
         />

         <TextInputComponent 
            :inputType="'text'"
            v-model="form.cell_number"
            :value="form.cell_number"  
            :column="'col-4'" 
            :label="'Phone Number'" 
            :errors="errors.cell_number"
            :input_id="cell_number"
         />

         <TextInputComponent 
            :inputType="'tel'"
            v-model="form.telephone" 
            :value="form.telephone"
            :column="'col-4'" 
            :label="'Work Number'" 
            :errors="errors.telephone"
            :input_id="telephone"
         />

         <div >
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
   import TextInputComponent from '../../components/input-components/TextInputComponent.vue';
   import { useForm,Link, Head } from '@inertiajs/inertia-vue3';

   export default {
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

            const mergeAndDownload = () => {
                alert("WooooHoooooo")
                //form.post('/people/merge-and-download')
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

           return {form,mergeAndDownload,checkDocType}
       },

       components: {
           Layout,
           TextInputComponent
       }
   }
</script>

    
    