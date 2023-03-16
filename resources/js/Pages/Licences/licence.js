import Layout from "../../Shared/Layout.vue";
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import Banner from '../components/Banner.vue';
import Paginate from "../../Shared/Paginate.vue";

export default {
    props: {
      licences: Object,
      success: String,
      error: String,
      errors: Object,
      all_licence_types: Object
    },

    components: {
        Layout,
        Link,
        Banner,
        Paginate
    },

    setup(props) {

    const term = ref('')
    const form = useForm({
          term: term,
          active_status: '',
          licence_type: '',
          licence_date: '',
          province: ''
        })

       function limit(string='', limit = 25) {
        if(string !== ''){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }

       function search(){
          form.get(`/licences`, {
            replace: true,
            preserveState: true,
            onSuccess: () => {},
            
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

        watch(term, _.debounce(function (value) {
          Inertia.get('/licences', { term: value }, { preserveState: true, replace: true });
        }, 1000));
        
        onMounted(() => {
          if(props.success){
            notify(props.success)
          }else if(props.error){
            notify(props.error)
          }
        });

        return {
          limit,
          form,
          term,
          search,
          notify
        }
    },

    
}