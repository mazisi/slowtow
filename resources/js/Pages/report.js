import Layout from "../Shared/Layout.vue";
import { useForm, Link, Head} from '@inertiajs/inertia-vue3';
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Banner from './components/Banner.vue';
import { ref } from 'vue'

export default {
  props: {
    licenceTypes: Object,
    companies: Object,
    new_applications: Object,
    years: Object,
    people: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },

  setup(props) {
  let isActive = ref(false);
  let notActive = ref(true);
  const btnSecondary = ref('btn-secondary');
  const btnSuccess = ref('btn-success');
  const btnPrimary = ref('btn-primary');
  const months = {
    "1": "January",
    "2" : "February",
    "3" : "March",
    "4":   "April",
    "5": "May",
    "6": "June",
    "7": "July",
    "8": "August",
    "9": "September",
    "10": "October",
    "11": "November",
    "12": "December",
}

const new_app_stages = {

     "1" : "Client Quoted",
     "2" : "Deposit Invoiced",
     "3": "Deposit Paid",
     "4": "Payment to the Liquor Board",
     "5": "Prepare New Application",
     "6": "Scanned Application",
     "7": "Application Lodged",
     "8": "Initial Inspection",
     "9": "Liquor Board Requests",
     "10": "Final Inspection",
     "11": "Activation Fee Requested",
     "12": "Client Finalisation Invoice",
     "13": "Client Paid",
     "14": "Activation Fee Paid",
     "15": "Licence Issued",
     "16": "Licence Delivered",

}
    let years = props.years;
    const provinces = ['Eastern Cape','Free State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','Northern Cape','North West','Western Cape'];

    const boardRegion = ['Eastern Cape','Free State','Gauteng Ekurhuleni','Gauteng Johannesburg',
                        'Gauteng Sedibeng','Gauteng Tshwane',
                        'KwaZulu-Natal','Limpopo','Mpumalanga',
                        'Gauteng West Rand','Northern Cape','North West','Western Cape'];
                        
    let licenceTypes = props.licenceTypes;
    let companies = props.companies;
    let people = props.people;

    const date = ref('');
   
    const form = useForm({
      variation: null,
      activeStatus: '',
      month_from: '',
      month_to: '',
      year: '',
      applicant: '',
      is_license_complete: '',
      person: [],
      company: [],
      province: [],
      selectedDates:[],
      boardRegion: [],
      licence_types: [],
      new_app_stages: [],
      renewal_stages: [],
      transfer_stages: [],
      nomination_stages: [],
      alteration_stages: [],
      temp_licence_stages: [],
      temp_licence_region: []
    })

    const renewal_stages = {
    "1": "Client Quoted",
    "2" : "Client Invoiced",
    "3" : "Client Paid",
    "4":  "Payment To Liquor Board",
    "5": "Renewal Issued",
    "6": "Renewal Delivered"
    }

    const transfer_stages = {
      "1" : "Client Quoted",
      "2" : "Client Invoiced",
      "3" : "Client Paid",
      "4" : "Prepare Transfer Application",
      "5" : "Payment To The Liquor Board",
      "6" : "Scanned Application",
      "7" : "Application Logded",
      "8" : "Activation Fee Paid",
      "9" : "Transfer Issued",
      "10" : "Transfer Delivered"
    }

    const nomination_stages = {
      "1" : "Client Quoted",
      "2" : "Client Invoiced",
      "3" : "Client Paid",
      "4" : "Payment to the Liquor Board",
      "5" : "Select nominees",
      "6" : "Prepare Nomination Application", 
      "7" : "Scanned Application",
      "8" : "Nomination Lodged", 
      "9" : "Nomination Issued", 
      "10" : "Nomination Delivered" 
    }

    const alteration_stages = {
        "1" : "Client Quoted",
        "2" : "Client Invoiced",
        "3" : "Client Paid", 
        "4" : "Prepare Alterations Application",
        "5" : "Payment to the Liquor Board",
        "6" : "Alterations Lodged",
        "7" : "Alterations Certificate Issued",
        "8" : "Alterations Delivered",

    }
    const temp_licence_regions = {
      "Ekurhuleni": "Ekurhuleni",
      "Johannesburg": "Johannesburg",
      "Sedibeng" : "Sedibeng",
      "Tshwane" : "Tshwane",
      "West Rand" : "West Rand"
  }

    const temp_licence_stages = {
     "1" : "Client Quoted",
     "2" : "Client Invoiced",
     "3" : "Client Paid",
     "4" : "Collate Temporary Licence Documents ",
     "5" : "Payment To The Liquor Board ",
     "6" : "Scanned Application",
     "7" : "Temporary Licence Lodged",  
     "8" : "Temporary Licence Issued", 
     "9" : "Temporary Licence Delivered", 
    }

  //  REFACTOR ME PLEASE
   function addClass(event,type){
    const buttons = document.querySelectorAll('.w-50')
  

      if(type == 'All'){
        event.target.classList.remove('type')
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')

        buttons.forEach(button => {
          if(event.target !== button){
            button.classList.remove('btn-info')
            button.classList.add('btn-success')
          }      
        })

      }else if(type == 'Alterations'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')
        buttons.forEach(button => {
          if(event.target !== button){
            button.classList.remove('btn-info')
            button.classList.add('btn-success')
          }      
        })
      }else if(type == 'Existing Licences'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')

        buttons.forEach(button => {
      if(event.target !== button){
          button.classList.remove('btn-info')
          button.classList.add('btn-success')
         }      
        })
        
      }else if(type == 'New Applications'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')

        buttons.forEach(button => {
      if(event.target !== button){
        button.classList.remove('btn-info')
        button.classList.add('btn-success')
      }      
    })
        
      }else if(type == 'Nominations'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')

        buttons.forEach(button => {
      if(event.target !== button){
        button.classList.remove('btn-info')
        button.classList.add('btn-success')
      }      
    })
        
      }else if(type == 'Temporary Licences'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')
        buttons.forEach(button => {
      if(event.target !== button){
        button.classList.remove('btn-info')
        button.classList.add('btn-success')
      }      
    })

      }else if(type == 'Transfers'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info')

        buttons.forEach(button => {
      if(event.target !== button){
        button.classList.remove('btn-info')
        button.classList.add('btn-success')
      }      
    })
        
      }else if(type == 'Renewals'){
        event.target.classList.remove('btn-success')
        event.target.classList.add('btn-info') 
        buttons.forEach(button => {
      if(event.target !== button){
        button.classList.remove('btn-info')
        button.classList.add('btn-success')
      }      
    })   

      }
    }
   


  function getType(event,type){
     form.variation=type;
     addClass(event,type);
     form.reset('activeStatus','month','year','applicant','person','company',
     'province','month','selectedDates','boardRegion','licence_types','new_app_stages')
   }

   const handleDate = (modelData) => {
          date.value = modelData;
       form.selectedDates.push(date.value)
   }

   const removeDate = (index) => {
       form.selectedDates.splice(index, 1)
   }

   const exportReport = () => {
    let url =
    `/export-report?variation=${form.variation}&month_from=${form.month_from}
    &month_to=${form.month_to}&year=${form.year}&applicant=${form.applicant}
    &person=${form.person}&company=${form.company}&province=${form.province}
    &selectedDates=${form.selectedDates}&boardRegion=${form.boardRegion}
    &licence_types=${form.licence_types}&new_app_stages=${form.new_app_stages}
    &renewal_stages=${form.renewal_stages}&transfer_stages=${form.transfer_stages}
    &nomination_stages=${form.nomination_stages}&alteration_stages=${form.alteration_stages}
    &temp_licence_stages=${form.temp_licence_stages}&temp_licence_region=${form.temp_licence_region}
    &activeStatus=${form.activeStatus}&is_licence_complete=${form.is_license_complete}`
    
    window.open(url,'_blank');
     
   }

   const fetchNewAppWithStages = () => {
    if(form.variation === 'New Applications'){
      form.get(`/reports`, {
            preserveScroll: true,
            onSuccess: () => {
              //
            },
            replace: true,
            preserveState: true
            })
    } 
       
   }

   function limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }


      function resetFilters(){
        form.activeStatus = '';
        form.month_from = '';
        form.month_to = '';
        form.year = '';
        form.applicant = '';
        form.is_license_complete = '';
        form.person = [];
        form.company = [];
        form.province = [];
        form.selectedDates = [];
        form.boardRegion = [];
        form.licence_types = [];
        form.new_app_stages = [];
        form.renewal_stages = [];
        form.transfer_stages = [];
        form.nomination_stages = [];
        form.alteration_stages = [];
        form.temp_licence_stages = [];
        form.temp_licence_region = []
      }

return{
  limit,addClass,
  renewal_stages,
  transfer_stages,
  nomination_stages,
  alteration_stages,
  temp_licence_stages,
  temp_licence_regions,
  getType,
  form,
  months,
  provinces,
  boardRegion,
  licenceTypes,
  handleDate,
  removeDate,
  exportReport,
  people,
  companies,
  new_app_stages,
  fetchNewAppWithStages,
  isActive,
  btnSecondary,
  btnSuccess,
  btnPrimary,
  years,
  notActive,
  resetFilters
}
},
 components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Datepicker,
    Banner
},


}