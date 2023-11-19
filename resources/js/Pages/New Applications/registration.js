import Layout from "../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import '@vuepic/vue-datepicker/dist/main.css';
  import Task from "../Tasks/Task.vue";
  import Banner from '../components/Banner.vue';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  import AdditionalDocsComponent from './components/AdditionalDocsComponent.vue';
  import StageComponent from './components/StageComponent.vue';
  import DocComponent from './components/DocComponent.vue';
  import MergeDocumentComponent from './components/MergeDocumentComponent.vue';  
  import DateComponent from './components/DateComponent.vue';  
  // import MergeButtonComponent from './components/MergeButtonComponent.vue';  
  import licence from "../Licences/licence";
  
  export default {
    props: {
      tasks: Object,
      errors: Object,
      licence: Object,
      licence_stage_dates: Object,
      success: String,
      error: String,
      client_quoted: Object,
      gba_application_form: Object,//doc
      client_invoiced: Object,//doc
      application_forms: Object,//doc
      company_docs: Object,
      cipc_docs: Object,
      id_docs: Object,
      police_clearance: Object,
      tax_clearance: Object,
      lta_certificate: Object,
      shareholding_info: Object,
      financial_interests: Object,
      _500m_affidavict: Object,
      government_gazette: Object,
      advert_affidavict: Object,
      proof_of_occupation: Object,
      representations: Object,
      menu: Object,
      photographs: Object,
      consent_letter: Object,
      zoning_certificate: Object,
      local_authority: Object,
      mapbook_plans: Object,
      google_map_plans: Object,
      description: Object,
      site_plans: Object,
      dimensional_plans: Object,
      advert_photographs: Object,
      newspaper_adverts: Object,
      payment_to_liqour_board: Object,
      scanned_application: Object,
      application_logded: Object,
      initial_inspection_doc: Object,
      final_inspection_doc: Object,
      client_finalisation: Object,
      client_paid: Object,
      activation_fee_paid: Object,
      licence_issued_doc: Object,
      licence_delivered: Object,
      lodged_with_municipality: Object,
      additional_docs: Object,
    },
  
    setup (props) {      

      const form = useForm({
        deposit_paid_at: props.licence.deposit_paid_at,
        liquor_board_at: props.licence.liquor_board_at,
        application_lodged_at: props.licence.application_lodged_at,
        initial_inspection_at: props.licence.initial_inspection_at,
        final_inspection_at: props.licence.final_inspection_at,
        activation_fee_requested_at	: props.licence.activation_fee_requested_at,
        client_paid_at: props.licence.client_paid_at,
        activation_fee_paid_at: props.licence.activation_fee_paid_at,
        licence_issued_at: props.licence.licence_issued_at,
        licence_delivered_at: props.licence.licence_delivered_at,
        status: [],
        unChecked: false
       })
  
    
  
      function deleteDocument(id){
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-licence-document/${id}`, {
              onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
            })
          }
        }
  
        
  
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
 

      function pushData(e,status_value){
           if (e.target.checked) {
              form.status[0] = status_value;
              form.unChecked = false;
            }else if(!e.target.checked){
              form.unChecked = true
              form.status[0] = status_value;
            }
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

      function computeBoardRequestDate(datetime){
        return new Date(datetime).toLocaleString()
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
                console.log('stage',licence_dates[i].stage)
                return licence_dates[i].dated_at;
              }
            }
            return '';
          }
         

        }

         function getStatus(status_param) {
          let status;
          switch (status_param) {
            case '100':
              status = 'Client Quoted'
              break;
              case '200':
              status = 'Deposit Invoice'
              break;
              case '300':
              status = 'Deposit Paid'
              break;
              case '400':
              status = 'Payment to the Liquor Board'
              break;
              case '500':
              status = 'Prepare New Application'
              break;
              case '600':
              status = 'Scanned Application'
              break;
              case '700':
              status = 'Lodged with Municipality'
              break;
              case '80':
              status = 'Additional Documents/Information'
              break;  
              case '90':
              status = 'Initial Inspection'
              break;
              case '100':
              status = 'Final Inspection'
              break;
              case '110':
              status = 'Activation Fee Requested'
              break;
              case '120':
              status = 'Client Finalisation Invoice'
              break; 
              case '130':
              status = 'Finalisation Paid'
              break;
              case '140':
              status = 'Activation Fee Paid'
              break;
              case '150':
              status = 'Licence Issued'
              break;
            case '160':
              status = 'Licence Delivered'
              break;
            default:
              status='Not Set';
              break;
          }
          return status;
        }

      return { 
        notify,
        form,getStatus,
        updateRegistration,
        pushData,
        deleteDocument,
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
      Banner
    },
    
  };
  //The following are status keys
  // 1. Client Quoted
  // 2. Deposit Invoice
  // 3. Deposit Paid
  // 4. Payment to the Liquor Board
  // 5. Prepare New Application
  // 6. Scanned Application
  // 7. Application Lodged
  // 8. Initial Inspection
  // 9. Liquor Board Requests
  // 10. Final Inspection
  // 11. Activation Fee Requested
  // 12. Client Finalisation Invoice
  // 13. Client Paid
  // 14. Activation Fee Paid
  // 15. Licence Issued
  // 16. Licence Delivered 