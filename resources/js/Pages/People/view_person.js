import { useForm,Link, Head } from '@inertiajs/inertia-vue3';
import Layout from "../../Shared/Layout.vue";
import { ref,onMounted } from "vue";
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Paginate from "../../Shared/Paginate.vue";
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import CheckBoxInputComponent from '../components/input-components/CheckBoxInputComponent.vue';
import useToaster from '../../store/useToaster';
import DocComponent from "@/Pages/components/slotow-components/DocComponent.vue";

export default {

props:{
      tasks: Object,
      errors: Object,
      person: Object,
      message: String,
      success: String,
      error: String,
      id_document: Object,
      police_clearance: Object,
      passport_doc: Object,
      work_permit_doc: Object,
      fingerprints: Object,
      linked_licences: Object
},

setup (props) {
  let show_file_name = ref(false);
  let file_name = ref('');
  const { notifySuccess, notifyError } = useToaster();

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


    const uploadDoc = useForm({
          doc_type: null,
          document: null,
          doc_expiry: null,
          file_name: file_name,
          people_id: props.person.id
    });
    // function hasFile(doc_type) {
    //
    //         return {}; // Return an empty object if no document satisfies the conditions
    //
    //     }


    function hasFile(doc_type) {
        if (props.person.people_documents) {
            const foundDocument = props.person.people_documents.find(doc =>
                doc.people_id === props.person.id &&
                doc.doc_type === doc_type &&
                doc.document_name &&
                doc.path &&
                doc.id
            );

            console.log(foundDocument, "found")
            if (foundDocument) {
                return {
                    fileName: foundDocument.document_name,
                    docPath: foundDocument.path,
                    id: foundDocument.id
                };
            }
            return {}; // Return an empty object if no document satisfies the conditions

        }
    }

      let show_doc_modal = ref(true);

      function submitDocument () {
        uploadDoc.post('/upload-person-documents', {
           onSuccess: () => {
            this.show_file_name = false;
            this.show_doc_modal = false;
            document.querySelector('.modal-backdrop').remove();
            if(props.success){
               notifySuccess(props.success)
             }else if(props.error){
                 notifyError(props.error)
             }
            this.uploadDoc.reset()
           },
        })
      }

      function getDocType (doc_type) {
        this.show_doc_modal = true;
        this.show_file_name = true;
        uploadDoc.doc_type = doc_type;
      }

      function deleteDocument (document_name,slug) {
        if(confirm(document_name + ' will be deleted permanently...Continue ??')){
          Inertia.delete(`/delete-person-document/${slug}`, {
            onSuccess: () => {
               if(props.success){
                  notifySuccess(props.success)
               }else if(props.error){
                 notifyError(props.error)
             }
            },
          })
        }
      }



    function updatePerson () {
      form.post('/update-person', {
        onSuccess: () => {
          if(props.success){
            notifySuccess(props.success)
          }else if(props.error){
            notifyError(props.error)
          }

         },
      })
    };

    function assignActiveValue(e,status_value){

       const updateStatusForm = useForm({
           unChecked: null,
           status: null,
        });

       if (e.target.checked) {
             updateStatusForm.status = status_value;
             updateStatusForm.unChecked = false;
           }else if(!e.target.checked){
             updateStatusForm.unChecked = true
             updateStatusForm.status = status_value;
           }
           updateStatusForm.patch(`/update-person-active-status/${props.person.slug}`,{
            onSuccess: () => {
               if(props.success){
                  notifySuccess(props.success)
               }else if(props.error){
                   notifyError(props.error)
               }
              },
            })

     }

    function deletePerson (full_name) {
         if (confirm('Are you sure you want to delete ' + full_name + '??')) {
            Inertia.delete(`/delete-person/${props.person.slug}`,{
              onSuccess: () => {
                 if(props.success){
            notifySuccess(props.success)
          }else if(props.error){
            notifyError(props.error)
          }
              },
            })
          }
      };

      function computeExpiryDate (date_param) {
        return new Date(date_param).toLocaleString().split(',')[0]
      };



     let file_has_apostrophe = ref();
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

        function checkingFileProgress(message){
          setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading(message);
        }



         function previewPerson() {
          let url = `/preview-person/${props.person.slug}`
          window.open(url,'_blank');
         }



     return{
        form,show_file_name,computeExpiryDate,deletePerson,checkingFileProgress,
        assignActiveValue,updatePerson,deleteDocument,getDocType,submitDocument,
        show_doc_modal,uploadDoc,file_name,getFileName,file_has_apostrophe,previewPerson, hasFile

     }
},
 components: {
     DocComponent,
    Layout,
    Banner,
    Head,
    Link,
    Paginate,
    Task,
    TextInputComponent,
    CheckBoxInputComponent
  },

}
