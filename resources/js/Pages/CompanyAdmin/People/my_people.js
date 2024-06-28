import Layout from "../../../Shared/Layout.vue";
import { useForm ,Link, Head } from '@inertiajs/inertia-vue3';
import { ref, watch, onMounted, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../../components/Banner.vue'
import Paginate from "@/Shared/Paginate.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../../store/useToaster';

export default {
  
  props: {
    success: String,
    error: String,
    company: Object,
    people: Array,
    linked_companies: Object,
    links: Array,
  },
  setup(props){

    const { notifySuccess, notifyError } = useToaster();
//   console.log(props.company.data[0].users)
    const [search_query, active_status] = getUrlParam();

    function getUrlParam(){
      const urlParams = new URLSearchParams(window.location.search);
      const search_query = urlParams.get('term')
      const status = urlParams.get('active_status')
      return [search_query,status];
    }

    const term = search_query ? search_query : ref('');

   let searchTerm = ref('');

    const filteredPeople = computed(() => {
      console.log(searchTerm.value)
      if (!searchTerm.value) return props.people;
   
      return props.people.filter(person => 
        person.full_name.toLowerCase().includes(searchTerm.value.toLowerCase())
      );
    });

    const form = useForm({
          term: term,
          active_status: ''
        })

    Inertia.on('navigate', (event) => {
      Inertia.visit(`${event.detail.page.url}`, { preserveState: true, preserveScroll: true });
    })

    function search(){
      // return props.people = props.people.filter(obj => obj.full_name.toLowerCase().includes(form.term.toLowerCase()))     
    }
    // watch(form.term, _.debounce(function (value) {
    //    Inertia.get('/people', { term: value, active_status: form.active_status }, { preserveState: true, replace: true });
    //     }, 2000));


        onMounted(() => {
          if(props.success){
            notifySuccess(props.success)
          }else if(props.error){
            notifyError(props.error)
          }
        });

    return{
      getUrlParam,
      searchTerm,
      search,
      form,
      filteredPeople,
      toast
    }
  },
 components: {
    Layout,
    Link,
    Head,
    useForm,
    Banner,
    Paginate
},
}