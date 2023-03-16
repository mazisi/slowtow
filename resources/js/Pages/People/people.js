import Layout from "../../Shared/Layout.vue";
import { useForm ,Link } from '@inertiajs/inertia-vue3';
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue'
import Paginate from "@/Shared/Paginate.vue";

export default {
  props: {
    success: Object,
    people: Object,
    links: Array,
  },
  setup(props){
    const term = ref('');

    const form = useForm({
          term: term,
          active_status: ''
        })

    function search(){
      form.get(`/people`, {
            replace: true,
            preserveState: true
            
        })
    }
    watch(term, _.debounce(function (value) {
          Inertia.get('/people', { term: value }, { preserveState: true, replace: true });
        }, 2000));

    return{
      term,
      search,
      form
    }
  },
 components: {
    Layout,
    Link,
    useForm,
    Banner,
    Paginate
},
}