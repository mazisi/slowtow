import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import Banner from '../components/Banner.vue'
import { ref,onMounted, computed } from 'vue';
import Paginate from "../../Shared/Paginate.vue";
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import  common from '../common-js/common.js';
import 'vue3-toastify/dist/index.css';
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import LicenceTypeDropDownComponent from '../components/input-components/LicenceTypeDropDownComponent.vue';
import ProvinceSelectDropdownComponent from '../components/input-components/ProvinceSelectDropdownComponent.vue';
import CheckBoxInputComponent from '../components/input-components/CheckBoxInputComponent.vue';
import DocumentListComponent from './licence-components/DocumentListComponent.vue';


export default {
 props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    tasks: Object,
    companies: Object,
    original_lic: Object,
    duplicate_original_lic: Object,
    original_lic_delivered: Object,
    duplicate_original_lic_delivered: Object
  },
  
  
  setup (props) {
        let showMenu = false;
        let options = props.companies;
        let show_current_company = ref(true);
        let change_company = ref(false);
                

    const form = useForm({
         trading_name: props.licence.trading_name,
         licence_type: props.licence.licence_type_id,
         is_licence_active: props.licence.is_licence_active,
         licence_number: props.licence.licence_number,
         old_licence_number: props.licence.old_licence_number,
         licence_date: props.licence.licence_date,
         postal_code: props.licence.postal_code,
         address: props.licence.address,
         address2: props.licence.address2,
         address3: props.licence.address3,
         province: props.licence.province,
         company: props.licence.belongs_to === 'Company' ? props.licence.company.name : '',
         person: props.licence.belongs_to === 'Person' ? props.licence.people.full_name : '',
         company_id: props.licence.belongs_to === 'Company' ? props.licence.company.id : '',
         person_id: props.licence.belongs_to === 'Person' ? props.licence.people.id : '',
         change_company: '',  
    })


//Insert original licence 
      

     function changeCompany(){
          this.show_current_company=false;
          this.change_company=true;
      }

      function updateLicence() {
         form.patch(`/update-licence/${props.licence.slug}`, {
          preserveScroll: true,
          onSuccess: () => { 
            if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
         }
        })    
        }

        

       function deleteLicence(){
          if(confirm('Are you sure you want to delete this licence??')){
            Inertia.delete(`/delete-licence/${props.licence.slug}`,{
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
                      updateStatusForm.status = e.target.value;
                    }
                    updateStatusForm.patch(`/update-licence-active-status/${props.licence.slug}`,{
                      onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
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

        

        //  onMounted(() => {
        //   if(props.success){
        //     notify(props.success)
        //   }else if(props.error){
        //     notify(props.error)
        //   }
        // });
        const computedProvinces = computed(() => {
          return common.getProvinces();
        })

    return {
      showMenu,
      show_current_company,
      change_company,
      options,
      form,toast,notify,
      limit,
      changeCompany,
      updateLicence,
      deleteLicence,
      assignActiveValue,
      computedProvinces
    }
  },

   components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Banner,
    Paginate,
    Task,
    TextInputComponent,
    LicenceTypeDropDownComponent,
    ProvinceSelectDropdownComponent,
    CheckBoxInputComponent,
    DocumentListComponent,
    
  },
  
};