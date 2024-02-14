import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import Banner from '../components/Banner.vue'
import { ref,onMounted } from 'vue';
import Paginate from "../../Shared/Paginate.vue";
import Task from "../Tasks/Task.vue";
import common from '../common-js/common.js';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../store/useToaster';
import { CircleProgressBar } from 'circle-progress.vue';
// import vueFilePond from 'vue-filepond';
// import "filepond/dist/filepond.min.css"
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import LicenceTypeDropDownComponent from '../components/input-components/LicenceTypeDropDownComponent.vue';
import CheckBoxInputComponent from '../components/input-components/CheckBoxInputComponent.vue';
 
// const FilePond = new vueFilePond();
export default {
 props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    tasks: Object,
    companies: Object,
    people: Object,
    original_lic: Object,
    licence_issued: Object,
    duplicate_original_lic: Object,
    original_lic_delivered: Object,
    licence_delivered: Object,
    duplicate_original_lic_delivered: Object
  },
  
  
  setup (props) {
        let showMenu = false;
        let companyOptions = props.companies;
        let peopleOptions = props.people;
        let show_current_company = ref(true);
        let change_company = ref(false);
        let show_modal = ref(true);
        let file_name = ref(''); 
        let file_has_apostrophe = ref();
        let all_licences = ref([]);
        
      
    const { notifySuccess, notifyError } = useToaster();
      //These are only for  company admin menu
    const campanyMenuOptions = [
      {id: 1, name: 'Registration', endpoint: 'my-registration'},
      {id: 2, name: 'Renewals', endpoint: 'my-renewals'},
      {id: 3, name: 'Transfers', endpoint: 'my-transfer-history'},
      {id: 4, name: 'Nominations', endpoint: 'my-nominations'},
      {id: 5, name: 'Alterations', endpoint: 'my-alterations'}
  ];

    const form = useForm({
         trading_name: props.licence.trading_name,
         licence_type: props.licence.licence_type_id,
         belongs_to: props.licence.belongs_to,
         is_licence_active: props.licence.is_licence_active,
         licence_number: props.licence.licence_number,
         old_licence_number: props.licence.old_licence_number,
        //  licence_date: props.licence.licence_date,
         postal_code: props.licence.postal_code,
         address: props.licence.address,
         address2: props.licence.address2,
         address3: props.licence.address3,
         province: props.licence.province,
         board_region: props.licence.board_region,
         renewal_amount: props.licence.renewal_amount,
         latest_renewal: props.licence.latest_renewal,
         company: props.licence.belongs_to === 'Company' ? props.licence.company.name : '',
         person: props.licence.belongs_to === 'Individual' ? props.licence.people.full_name : '',
         company_id: props.licence.belongs_to === 'Company' ? props.licence.company.id : '',
         person_id: props.licence.belongs_to === 'Individual' ? props.licence.people.id : '',
         change_company: '',  
    })

    //assign licences based on province
    all_licences.value =  props.licence_dropdowns
    .filter(obj => obj.province === form.province); 


    
    //list licence types based on province selected
     function  selectedProvince(){ 
      const filteredLicenses = props.licence_dropdowns
      .filter(obj => obj.province === form.province); 
      console.log(filteredLicenses);
      all_licences.value = filteredLicenses; 
      console.log(all_licences.value);
                    
    }

    


//Insert original licence 
       const originalLicenceForm = useForm({
          document_file: null,
          licence_id: props.licence.id,
          doc_type: null,
       })
 
      function getDocType(doc_type){
        this.originalLicenceForm.doc_type = doc_type;
        this.show_modal = true
      }

     function changeCompany(){
          this.show_current_company=false;
          this.change_company=true;
      }
      
      Inertia.on('navigate', (event) => {
        if (event.detail.page.url.includes("view-licence")) {
       
          Inertia.visit(`${event.detail.page.url}`,{preserveScroll: true,preserveState: true})

        } else {
          //console.log("The URL does not contain 'view-licence'");
        }
        // console.log(`Navigated to ${event.detail.page.url}`)
      })
      function updateLicence() {
         form.patch(`/update-licence/${props.licence.slug}`, {
          preserveScroll: true,
          onSuccess: () => { 
            if(props.success){
                   notifySuccess(props.success)
                    }else if(props.error){
                      notifyError(props.error)
                    }
         }
        })    
        }

      
        function deleteDocument(id){
          if(confirm('Document will be deleted permanently!! Continue??')){
            Inertia.delete(`/delete-licence-document/${id}`,{
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

       function deleteLicence(){
          if(confirm('Are you sure you want to delete this licence??')){
            Inertia.delete(`/delete-licence/${props.licence.slug}`,{
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

        
        function assignActiveValue(e,status_value){       
                const updateStatusForm = useForm({
                    unChecked: null,
                    status: null,
                  });

                if (e.target.checked) {
                      updateStatusForm.status = status_value;
                      updateStatusForm.unChecked = false;
                    }else if(!e.target.checked){
                      updateStatusForm.unChecked = true;
                      updateStatusForm.status = status_value;
                    }
                    updateStatusForm.patch(`/update-licence-active-status/${props.licence.slug}`,{
                      onSuccess: () => { 
                        if(props.success){
                            notifySuccess(props.success)
                         }else if(props.error){
                           notifyError(props.error)
                         }
                      },
                      })
          
     }

        function limit(string = '', limit = 25) {
          if(string.length >= limit){
            return string.substring(0, limit) + '...'
          }  
            return string.substring(0, limit)
        }

        
          function upload(e, doc_type){
            originalLicenceForm.document_file = e.target.files[0];
            this.file_name = e.target.files[0].name;
            file_has_apostrophe=false;
            originalLicenceForm.doc_type=doc_type

            if(e.target.files[0].name.includes("'")){
              this.file_has_apostrophe = true
              return;
            }

            originalLicenceForm.post(`/upload-licence-document`, {
              preserveScroll: true,
              onSuccess: () => { 
                    if(props.success){
                      e.target.value = '';
                       notifySuccess(props.success)
                        }else if(props.error){
                          notifyError(props.error)
                    }
             },
            })    
          }

      
  

      function removeFilePath(file_name){
        if(file_name.includes('mrnlabs')){
          let getFileName = file_name.split('/');
            return  getFileName[1];
          }  
            return file_name;
      }
      
      const preview = () => {
        let url = `/preview-licence/${props.licence.slug}`
        window.open(url,'_blank');
      }


    

    return {
      upload,
      preview,
      showMenu,
      file_name,
      removeFilePath,
      show_current_company,
      change_company,
      companyOptions,peopleOptions,
      form,toast,
      limit,common,
      changeCompany,
      updateLicence,
      deleteLicence,
      assignActiveValue,
      originalLicenceForm,
      getDocType,
      deleteDocument,
      selectedProvince,
      all_licences,
      campanyMenuOptions
    }
  },


  


  computed: {
    computedProvinces() {
      return common.getProvinces();
    },
    
    computedBoardRegions() {
      return common.getBoardRegions();
    }
  },


   components: {
    CircleProgressBar,
    // FilePond,
    Layout,
    Link,
    Head,
    Multiselect,
    Banner,
    Paginate,
    Task,
    TextInputComponent,
    LicenceTypeDropDownComponent,
    CheckBoxInputComponent,
  },
  
};