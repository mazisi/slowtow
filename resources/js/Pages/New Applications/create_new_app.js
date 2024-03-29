
  import Layout from "../../Shared/Layout.vue";
  import Multiselect from '@vueform/multiselect';
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import Banner from '../components/Banner.vue';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  import common from '../common-js/common.js';
  import {computed, ref} from 'vue';
  import LiquorBoardRegionComponent from '../components/input-components/LiquorBoardRegionComponent.vue'
  import TextInputComponent from '../components/input-components/TextInputComponent.vue';
  import ProvinceSelectDropdownComponent from '../components/input-components/ProvinceSelectDropdownComponent.vue'
  import LicenceTypeDropDownComponent from '../components/input-components/LicenceTypeDropDownComponent.vue'

  export default {
   props: {
      errors: Object,
      licence_dropdowns: Object,
      companies: Array,
      persons: Array,
      success: String,
      error: String,
      type: String,
      wholesaleLicenceTypes: Object,
      get_reg_num_or_id_number: String
    },


    setup (props) {

      const wholesaleLicences =  [
        {
            "id": 102,
            "licence_type": "Distribution and Manufacturing Liquor Licence",
            "province": "Wholesale",
            "created_at": "2024-01-16T20:07:23.000000Z",
            "updated_at": "2024-01-16T20:07:23.000000Z"
        },
        {
            "id": 103,
            "licence_type": "Distribution Liquor Licence",
            "province": "Wholesale",
            "created_at": "2024-01-16T20:08:17.000000Z",
            "updated_at": "2024-01-16T20:08:17.000000Z"
        },
        {
            "id": 104,
            "licence_type": "Manufacturing Liquor Licence",
            "province": "Wholesale",
            "created_at": "2024-01-16T20:08:17.000000Z",
            "updated_at": "2024-01-16T20:08:17.000000Z"
        }
    ]
      let options;

        let licenceByProvince = ref([]);

      const form = useForm({
            trading_name: '',
            licence_type: '',
            belongs_to: '',
            reg_number: '',
            id_number: '',
            address: '',
            address2: '',
            address3: '',
            province: '',
            company: '',
            person: '',
            board_region: '',
            import_export: '',
            type: props.type
      })

      const idRegForm = useForm({
            person : '',
            company: ''
      })



      function submit() {
        form.post('/submit-new-app', {
          onSuccess: () => {
              //notify(props.success)
           },
          preserveScroll: true,
        })

      }

        function filterLicenceTypes(){
          if(props.type == 'wholesale') {
           
              // licenceByProvince.value = props.licence_dropdowns
              //     .filter(obj => obj.province === 'Wholesale');
          }else{
              licenceByProvince.value = props.licence_dropdowns
                  .filter(obj => obj.province === form.province && obj.province !== 'Wholesale');
          }

          console.log(licenceByProvince);

        }

      const filterForm = useForm({
        variation: '',
        id: form.company ? form.company : form.person
      })

      function selectApplicant(){
          if(form.belongs_to === 'Company'){
            filterForm.variation = 'Company';
            filterForm.id = form.company;
            form.person='';
          }else if(form.belongs_to === 'Individual'){
            filterForm.variation = 'Individual';
            filterForm.id = form.person;
            form.company='';
          }

          filterForm.get(`/create-new-app?type=${props.type}&id=${filterForm.id}`, {
                      onStart: () => {
                        let mess = filterForm.variation === 'Individual' ? ' ID Number' : ' Registration Number';
                        getchIdOrRegNumber(mess);
                      },
            preserveScroll: true,
            replace: true,
            preserveState: true
            })

     }

     function getchIdOrRegNumber(message){
          setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading(`Fetching${message}`);
        }



        const computedProvinces = computed(() => {
          //   exclude a province called Wholesale
            return common.getProvinces().filter(province => province !== 'Wholesale');
        })


        const computedBoardRegions = computed(() => {
          return common.getBoardRegions();
        })

        const filteredLicenceTypes = props.licence_dropdowns.filter(
          type => [102, 103, 104].includes(type.id)
        );

        console.log(filteredLicenceTypes);

      return { submit, form ,options, idRegForm,
        selectApplicant, filterForm,toast,filteredLicenceTypes,
        getchIdOrRegNumber, computedProvinces,wholesaleLicences,
        computedBoardRegions, licenceByProvince, filterLicenceTypes
      }

    },

     components: {
      Layout,
      Link,
      Head,
      Multiselect,
      Banner,
      TextInputComponent,
      ProvinceSelectDropdownComponent,
      LiquorBoardRegionComponent,
      LicenceTypeDropDownComponent
    }

  };
