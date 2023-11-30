import Layout from "../../Shared/Layout.vue";
import Multiselect from "@vueform/multiselect";
import { Link, useForm, Head } from "@inertiajs/inertia-vue3";
import Banner from "../components/Banner.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, onMounted } from "vue";
import Task from "../Tasks/Task.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import DocComponent from "../components/slotow-components/DocComponent.vue";
import StageComponent from "../components/slotow-components/StageComponent.vue";
// import DateComponent from "../components/slotow-components/DateComponent.vue";
import MergeDocComponent from './components/MergeDocComponent.vue'

export default {
    name: "ViewAlteration",
    props: {
        errors: Object,
        alteration: Object,
        success: String,
        error: String,
        tasks: Object,
        client_quoted: Object,
        client_invoiced: Object, //doc
        liqour_board: Object, // secod doc
        site_map_file: Object, //doc
        application_form: Object,
        dimensional_plans: Object,
        poa_res: Object,
        smoking_affidavict: Object,
        payment_to_liquor_board: Object,
        alteration_logded: Object,
        certification_issued: Object,
        alteration_delivered: Object,
    },
    setup(props) {
        let showMenu = false;
        const form = useForm({
            licence_slug: props.alteration.licence.slug,
            slug: props.alteration.slug,
            client_paid_at: props.alteration.client_paid_at,
            status: [],
            unChecked: false,
            invoiced_at: props.alteration.invoiced_at,
            liquor_board_at: props.alteration.liquor_board_at,
            date: props.alteration.logded_at,
            certification_issued_at: props.alteration.certification_issued_at,
            delivered_at: props.alteration.delivered_at,
        });

       

        function update() {
            form.patch(`/update-alteration`, {
                onStart: () => {
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                    toast.loading("Updating stage...");
                },
                onSuccess: () => {
                    if (props.success) {
                        notify(props.success);
                    } else if (props.error) {
                        notify(props.error);
                    }
                },
            });
        }

        function deleteAlteration(
            slug,
            licence_slug = props.alteration.licence.slug
        ) {
            if (confirm("Are you sure you want to delete this alteration?")) {
                Inertia.delete(
                    `/delete-altered-licence/${slug}/${licence_slug}`
                );
            }
        }

        function updateDate() {
            form.patch(`/update-alteration-date/${props.alteration.slug}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if (props.success) {
                        notify(props.success);
                    } else if (props.error) {
                        notify(props.error);
                    }
                },
            });
        }

        const notify = (message) => {
            if (props.success) {
                toast.success(message, {
                    autoClose: 2000,
                });
            } else if (props.error) {
                toast.error(message, {
                    autoClose: 2000,
                });
            }
        };

        function pushData(e, status_value) {
            if (e.target.checked) {
                this.form.status[0] = status_value;
                this.form.unChecked = false;
            } else if (!e.target.checked) {
                this.form.unChecked = true;
                this.form.status[0] = status_value;
            }
            update();
        }

        function hasFile(doc_type) {
            if (!props.alteration.documents) {
                return {}; // Return an empty object if props.licence.documents doesn't exist
            } else {
                let alteration_documents = props.alteration.documents; // Object with all licence docs

                const foundDocument = alteration_documents.find(
                    (doc) =>
                        doc.licence_id === props.licence.id &&
                        doc.doc_type === doc_type &&
                        doc.path &&
                        doc.document_name &&
                        doc.id
                );

                if (foundDocument) {
                    return {
                        fileName: foundDocument.document_name,
                        docPath: foundDocument.path,
                        id: foundDocument.id,
                    };
                } else {
                    return {}; // Return an empty object if no document satisfies the conditions
                }
            }
        }

        function getAlterationDate(alteration_id, stage) {
            

            if (!props.alteration.dates) {
              return {}; // Return an empty object if props.alteration.dates doesn't exist
            } else {
              let alteration_dates = props.alteration.dates;
          
              const dateFound = alteration_dates.find(date =>
                date.alteration_id === props.alteration.id &&
                date.stage === stage 
              );
          
              if (dateFound) {
                return {
                  dated_at: dateFound.dated_at
                };
              } else {
                return {}; // Return an empty object if no date satisfies the conditions
              }
            }
        }

        function updateAlterationDate(form_data){
          form_data.patch(`/update-alteration-date/${props.alteration.id}`, {
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
  
        

        return {
            form,
            updateAlterationDate,
            showMenu,
            updateDate,
            update,
            pushData,
            hasFile,
            toast,
            getAlterationDate,
            deleteAlteration,
            notify,
        };
    },

    components: {
        Layout,
        Multiselect,
        Link,
        Banner,
        Head,
        DocComponent,
        StageComponent,
        // DateComponent,
        MergeDocComponent,
        Task,
    },
    beforeUnmount() {
        this.$store.state.isAbsolute = false;
    },
};
//Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered
