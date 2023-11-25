import Layout from "../../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import '@vuepic/vue-datepicker/dist/main.css';
  import Task from "../../Tasks/Task.vue";
  import Banner from '../../components/Banner.vue';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  import AdditionalDocsComponent from './../components/AdditionalDocsComponent.vue';
  import StageComponent from './../components/StageComponent.vue';
  import DocComponent from './../components/DocComponent.vue';
  import MergeDocumentComponent from './../components/MergeDocumentComponent.vue';  
  import DateComponent from './../components/DateComponent.vue';  
  import MergeButtonComponent from './../components/MergeButtonComponent.vue';  
  
  export default {
    props: {
      tasks: Object,
      errors: Object,
      licence: Object,
      success: String,
      error: String,
    },
  
    setup (props) {      

      const form = useForm({
        status: [],
        unChecked: false,
        prevStage: ''
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
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
        })
      }
 

      function pushData(e,status_value, prevStage){
           if (e.target.checked) {
              form.status[0] = status_value;
              form.unChecked = false;
            }else if(!e.target.checked){
              form.unChecked = true
              form.status[0] = status_value;
            }
            form.prevStage = prevStage;
            updateRegistration();
            
        }

        function mergeDocs(){
          Inertia.post(`/merge-licence-docs/${props.licence.id}`, {
          preserveScroll: true,
          onStart: () => {                  
                  checkingFileProgress('This operation can take a while depending on number of files...')                
              },
          onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
        })
        }


        function deleteRegistration(){
          form.patch(`/update-registration-date/${props.licence.slug}`, {
          preserveScroll: true,
          onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
        })   
        }
       
      

      const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(props.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }

        function checkingFileProgress(message){
          setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading(message);
        }


        function getLicenceDate(licence_id, stage){
          // console.log('licence_stage_dates',props.licence.licence_stage_dates)
          if(! props.licence.licence_stage_dates){
            return ''
          }else{
            let licence_dates = props.licence.licence_stage_dates;//object with all licence stages
            for (let i = 0; i < licence_dates.length; i++) {
              if (licence_dates[i].licence_id === licence_id && licence_dates[i].stage === stage) {
                return licence_dates[i].dated_at;
              }
            }
            return '';
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
        

        
         function getStatus(status_param) {
          let status;
          switch (status_param) {
            case '100':
              status = 'Client Quoted'
              break;
              case '200':
              status = 'Deposit Invoiced'
              break;
              case '300':
              status = 'Deposit Paid'
              break;
              case '400':
              status = 'Prepare New Application'
              break;
              case '500':
              status = 'Application Submitted'
              break;
              case '600':
              status = 'Initial Application Fee'
              break;
              case '700':
              status = 'Application Lodged'
              break;
              case '800':
              status = 'Additional Documents Request'
              break;  
              case '900':
              status = 'NLA 6 Proposed'
              break;
              case '1000':
              status = 'NLA 7 Submitted'
              break;
              case '1100':
              status = 'NLA 8 Issued'
              break;
              case '1200':
              status = 'Activation Fee'
              break; 
              case '1300':
              status = 'NLA 9 Issued'
              break;
              case '1400':
              status = 'Original Licence'
              break;
              case '1500':
              status = 'Original Licence Delivered'
              break;
            default:
              status='Not Set';
              break;
          }
          return status;
        }

      return { 
        notify,hasFile,
        form,getStatus,
        updateRegistration,
        pushData,
        getLicenceDate,
        mergeDocs,
        deleteRegistration,
        toast
       }
    },
     components: {
      Layout,
      Link,
      Head,
      Task,
      AdditionalDocsComponent,
      StageComponent,
      DocComponent,
      DocComponent,
      DateComponent,
      MergeDocumentComponent,
      MergeButtonComponent,
      Banner
    },
    
  };
