<script>
import { useForm } from '@inertiajs/inertia-vue3';
// import useToaster from '../../store/useToaster';

export default {
    setup(props) {
       
        // const { notifySuccess, notifyError } = useToaster();

        const form = useForm({
            volume_of_beer: '',
            volume_of_wine: '',
            volume_of_spirit: '',
            exact_turnover_amount: '',
            number_of_employees: '',
            annual_turnover: ''
        })

        const options = [
            {id: 1, name: 'Between R5 Million and R15 Million'},
            {id: 2, name: 'Between R15 Million and R250 Million'},
            {id: 3, name: 'Less than R5 Million'},
            {id: 4, name: 'Between R250 Million and R1 Billion'},
            {id: 5, name: 'More than R1 Billion'},
            {id: 6, name: 'Dormant'}
        ]

        form.post(route('submit_turn_over_information'), {
            onSuccess: () => {
              if(props.success){
                notifySuccess(props.success);
                show_file_name = false;
              }else if(props.error){
                notifyError(props.error)
              }
              doc_form.reset();
              doc_form.document = null;
            }
        })
        return{ options, form }
    }
}


</script>

<template>
    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Beer</label>
            <input v-model="form.volume_of_beer" type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Wine</label>
            <input v-model="form.volume_of_wine" type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Spirit/Other</label>
            <input v-model="form.volume_of_spirit" type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Exact Turnover Amount</label>
            <input v-model="form.exact_turnover_amount" type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Number of Employees</label>
            <input v-model="form.number_of_employees" type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Annual Turnover</label>
            <select v-model="form.annual_turnover" class="form-control form-control-default">
                <option :value="''" selected disabled>Select...</option>
                <option v-for="option in options" :key="option.id" :value="option.id">{{ option.name}}</option>

            </select>
        </div>
    </div>

    <div class="col-md-6 columns">
        <button @click="submit" type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
</template>

<style scoped>

</style>
