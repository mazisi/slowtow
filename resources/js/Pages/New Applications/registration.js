import Layout from "../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import '@vuepic/vue-datepicker/dist/main.css';
  import Task from "../Tasks/Task.vue";
  import Banner from '../components/Banner.vue';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  import AdditionalDocsComponent from '../components/slotow-components/AdditionalDocsComponent.vue';
  import StageComponent from '../components/slotow-components/StageComponent.vue';
  import DocComponent from '../components/slotow-components/DocComponent.vue';
  import MergeDocumentComponent from '../components/slotow-components/MergeDocumentComponent.vue';
  import LinkComponent from './LinkComponent.vue'
  import DateComponent from '../components/slotow-components/DateComponent.vue';
  import MergeButtonComponent from '../components/slotow-components/MergeButtonComponent.vue';
  import useToaster from '../../store/useToaster';
  import useLicenceStatus from '../../store/useLicenceStatus';
import licence from "../Licences/licence";

  export default {
    props: {
      tasks: Object,
      errors: Object,
      licence: Object,
      success: String,
      error: String
    },

    setup (props) {
    const { notifySuccess, notifyError } = useToaster();
    const { getPlainStatus } = useLicenceStatus();
      const form = useForm({
        status: [],
        unChecked: false,
        prevStage: '',
        stage: '',
       })


      function updateRegistration() {//handles dates updates
        form.patch(`/update-new-registration/${props.licence.slug}`, {
          preserveScroll: true,
          onStart: () => {
            setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading('Updating stage...');
          },
          onSuccess: () => {
                        if(props.success){
                            notifySuccess(props.success)
                         }else if(props.error){
                           notifyError(props.error)
                         }
                      },
        })
      }


      function pushData(e,status_value, prevStage,stageTitle){
           if (e.target.checked) {
              form.status[0] = status_value;
              form.unChecked = false;
            }else if(!e.target.checked){
              form.unChecked = true
              form.status[0] = status_value;
            }
            form.prevStage = prevStage;
            form.stage = stageTitle
            updateRegistration();

        }

        const redirect = (licence) => {
          let url = '';
        if(licence.type == 'retail'){
           url = `/view-licence?slug=${licence.slug}`
        }else{
           url = `/view-wholesale-licence?slug=${licence.slug}`
        }
          Inertia.get(url);
        }
      

        function mergeDocs(){
          Inertia.post(`/merge-licence-docs/${props.licence.id}`, {
          preserveScroll: true,
          onStart: () => {
                  checkingFileProgress('This operation can take a while depending on number of files...')
              },
        onSuccess: () => {
          if(props.success){
              notifySuccess(props.success)
            }else if(props.error){
              notifyError(props.error)
            }
        },
        })
        }


        function deleteRegistration(){
          form.patch(`/update-registration-date/${props.licence.slug}`, {
          preserveScroll: true,
          onSuccess: () => {
                        if(props.success){
                            notifySuccess(props.success)
                         }else if(props.error){
                           notifyError(props.error)
                         }
                      },
        })
        }

        function checkingFileProgress(message){
          setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading(message);
        }


        function getLicenceDate(licence_id, stage){

          if(! props.licence.licence_stage_dates){
            return {}; // Return an empty object if props.licence.dates doesn't exist
          } else {
            let licence_dates = props.licence.licence_stage_dates;

            const dateFound = licence_dates.find(date =>
              date.licence_id === props.licence.id &&
              date.stage === stage
            );

            if (dateFound) {
              return {
                dated_at: dateFound.dated_at
              };
            } else {
              return {};
            }
          }

        }



        function hasFile(doc_type) {
          if (!props.licence.documents) {
            return {}; // Return an empty object if props.licence.documents doesn't exist
          } else {
            let licence_documents = props.licence.documents; // Object with all licence docs

            const foundDocument = licence_documents.find(doc =>
              doc.licence_id === props.licence.id &&
              doc.document_type === doc_type &&
              doc.document_file &&
              doc.document_name &&
              doc.id
            );

            if (foundDocument) {
              return {
                fileName: foundDocument.document_name,
                docPath: foundDocument.document_file,
                id: foundDocument.id
              };
            } else {
              return {}; // Return an empty object if no document satisfies the conditions
            }
          }
        }

        function updateStageDate(form_data){
          form_data.patch(`/update-registration-date/${props.licence.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                      if(props.success){
                          notifySuccess(props.success)
                        }else if(props.error){
                          notifyError(props.error)
                        }
                        },
          })
        }

        function submitDocument(file_data){
          file_data.post('/upload-licence-document', {
            preserveScroll: true,
            onSuccess: () => {
                if(props.success){
                              notifySuccess(props.success)
                           }else if(props.error){
                             notifyError(props.error)
                           }
              uploadDoc.doc_type = null;
             },
          })
        }

        function deleteDocument(id,prevStage){
          console.log(id,prevStage)
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-licence-document/${id}/${prevStage}`, {
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


function getStatus(status_param) {
    return getPlainStatus(status_param);
}

       
      return {
        hasFile,
        form,getStatus,
        updateRegistration,
        pushData,updateStageDate,
        getLicenceDate,
        mergeDocs,
        deleteRegistration,
        submitDocument,
        deleteDocument,
        toast,
        redirect
       }
    },
     components: {
      Layout,
      Link,
      Head,
      Task,
      LinkComponent,
      AdditionalDocsComponent,
      StageComponent,
      DocComponent,
      DateComponent,
      MergeDocumentComponent,
      MergeButtonComponent,
      Banner
    },

  };
