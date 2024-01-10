import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../store/useToaster';
import StageComponent from '../components/slotow-components/StageComponent.vue';
import DocComponent from '../components/slotow-components/DocComponent.vue';
import DateComponent from '../components/slotow-components/DateComponent.vue';
import PrepareTemp from './temp-licence-components/PrepareTemp.vue';
import LinkComponent from './temp-licence-components/LinkComponent.vue';

export default {
  props: {
    liqour_board_requests: Object,
    tasks: Object,
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    licence_logded: Object,//doc
    scanned_app: Object,//doc
    client_invoiced: Object,//doc
    licence_issued: Object,//doc
    client_quoted: Object,//doc
    collate: Object,
    liqour_board: Object,
    licence_delivered: Object,
    company_application_form: Object,
    company_poa: Object,
    company_annexure_b: Object,
    company_annexure_c: Object,
    company_cipc: Object,
    company_id_document: Object,
    company_representations: Object,
    company_landlord_letter: Object,
    company_security_letter: Object,
    company_advert: Object,
    company_plan: Object,
    //individual
    
    individual_application_form: Object,
    power_of_attorney: Object,
    individual_annexure_b: Object,
    individual_annexure_c: Object,
    individual_representations: Object,
    individual_landlord_letter: Object,
    individual_security_letter: Object,
    individual_advert: Object,
    individual_plan: Object,
    get_person_id_document: Object
     
  },

  setup (props) {
    const year = ref(new Date().getFullYear());
    let show_modal = ref(true);  
    let show_file_name = ref(false);
    let file_name = ref('');
    const { notifySuccess, notifyError } = useToaster();


    const form = useForm({
      status: [],
      prevStage: '',
      unChecked: false,
      client_paid_at: props.licence.client_paid_at,
      payment_to_liquor_board_at: props.licence.payment_to_liquor_board_at,
      logded_at: props.licence.logded_at,
      issued_at: props.licence.issued_at, 
      delivered_at: props.licence.delivered_at,
      liquor_licence_number: props.licence.liquor_licence_number,
      reg_number: props.licence.company ? props.licence.company.reg_number: '',
      id_number: props.licence.people ? props.licence.people.id_number: '',
      belongs_to: props.licence.belongs_to
     })




      function submitDocument(docForm){
 
        docForm.post('/submit-temporal-licence-document', {
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

         const mergeForm = useForm({ 
          temporal_licence_id: props.licence.id,
          company_id: props.licence.company_id,
          person_id: props.licence.people_id
          })
          function mergeDocuments(type){
            mergeForm.post(`/merge-temporal-documents/${type}`,{
              onStart: () => { 
                checkingFileProgress('Checking file availability...') 
               },
            })
          }


    function deleteDocument(id){
        if(confirm('Document will be deleted permanently...Continue ??')){
          Inertia.delete(`/delete-temporal-licence-document/${id}`, {
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

      function deleteTemporalLicence(){
        if(confirm('Are you sure you want to delete this temporary licence??')){
          Inertia.delete(`/delete-temporal-licence/${props.licence.slug}`, {
            //
          })
        }
      }

    function updateLicence() {
      form.patch(`/update-prepared-temp-app/${props.licence.slug}`, {
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

    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }

    function computeDocumentDate(date_param){
        return new Date(date_param).toLocaleString().split(',')[0]
    };

    function pushData(e,status_value,prevStage){
     
      if (e.target.checked) {
            form.status[0] = status_value;
            form.unChecked = false;
          }else if(!e.target.checked){
            form.unChecked = true
            form.status[0] = status_value;
          }
          form.prevStage = prevStage;
          updateLicence();
      }


      

      function updateStageDate(form_param){
        console.log(form_param)
        form_param.patch(`/update-process-app-date/${props.licence.slug}`, {
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

      


        function getStatus(status_param) {
          let status;

          switch (status_param) {
            case '100':
              status = 'Client Quoted'
              break;
              case '200':
              status = 'Client Invoiced'
              break;
              case '300':
              status = 'Client Paid'
              break;
              case '400':
              status = 'Collate Temporary Licence Documents'
              break;
              case '500':
              status = 'Payment To The Liquor Board'
              break;
              case '600':
              status = 'Scanned Application'
              break; 
              case '700':
              status = 'Temporary Licence Lodged'
              break; 
              case '800':
              status = 'Temporary Licence Issued'
              break; 
              case '900':
              status = 'Temporary Licence Delivered'
              break;              
            default:
              status='Not Set';
              break;
          }
          return status;
        }
       

        function hasFile(doc_type) {
          if (!props.licence.temp_documents) {
            return {}; // Return an empty object if props.licence.documents doesn't exist
          } else {
            let licence_documents = props.licence.temp_documents; // Object with all licence docs

            const foundDocument = licence_documents.find(doc =>
              doc.temporal_licence_id === props.licence.id &&
              doc.doc_type === doc_type &&
              doc.document &&
              doc.document_name &&
              doc.id
            );

            if (foundDocument) {
              return {
                fileName: foundDocument.document_name,
                docPath: foundDocument.document,
                id: foundDocument.id
              };
            } else {
              return {}; // Return an empty object if no document satisfies the conditions
            }
          }
        }

        function hasMergeFile(doc_type, belongsTo) {
          if (!props.licence.temp_documents) {
            return {}; // Return an empty object if props.licence.documents doesn't exist
          } else {
            let licence_documents = props.licence.temp_documents; // Object with all licence docs

            const foundDocument = licence_documents.find(doc =>
              doc.temporal_licence_id === props.licence.id &&
              doc.doc_type === doc_type && doc.belongs_to === belongsTo &&
              doc.document &&
              doc.document_name &&
              doc.id
            );

            if (foundDocument) {
              return {
                fileName: foundDocument.document_name,
                docPath: foundDocument.document,
                id: foundDocument.id
              };
            } else {
              return {}; // Return an empty object if no document satisfies the conditions
            }
          }
        }
        
    return { year,form,
     updateLicence,hasMergeFile,
     getRenewalYear, pushData,getStatus,
     submitDocument,
     computeDocumentDate,deleteDocument,
     updateStageDate,
     mergeDocuments,
     mergeForm,
     deleteTemporalLicence,
     toast,hasFile
     }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker,
    StageComponent,
     DocComponent,
     DateComponent,
     PrepareTemp,
     LinkComponent,
    LiquorBoardRequest,
    Banner,
    Task
  },

  watch: {
    'form.start_date'() {
      var d = new Date(this.form.start_date);
      d.setDate(d.getDate() - 14);
      this.form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }
  },
  
};
