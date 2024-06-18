<script>
import { useForm } from '@inertiajs/inertia-vue3';
import useToaster from '../../../store/useToaster';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { ref } from 'vue';

export default {

    props:{
    renewal: Object,
    success: String,
    error: String
    },

    setup(props) {
       
        const { notifySuccess, notifyError } = useToaster();

        const form = useForm({

            volume_of_beer: props.renewal.volume_beer,
            volume_of_wine: props.renewal.volume_wine,
            volume_of_spirit: props.renewal.volume_spirits,
            exact_turnover_amount: props.renewal.exact_turnover,
            number_of_employees:  props.renewal.number_of_employees,
            annual_turnover:  props.renewal.annual_turnover
        })

        const options = [
            {id: 1, name: 'Less than R5 million'},
            {id: 2, name: 'Between R5 million and R15 million'},
            {id: 3, name: 'Between R15 million and R250 million'},
            {id: 4, name: 'Between R250 Million and R1 Billion'},
            {id: 5, name: 'More than R1 Billion'},
            {id: 6, name: 'Dormant'}
        ];
        const formatToCurrency = (event) => {
            
	var dollars = document.getElementById('exact_turnover_amount').value;

	// kick out on arrow keys
	if ([37, 38, 39, 40].indexOf(event.which) > -1) {
		return;
	}
	
	// aaalife.utils.formatDollars
    form.exact_turnover_amount = formatDollars(dollars);
	dollars = formatDollars(dollars);
    
	
	if (event.type === 'blur') {
		dollars = dollars.split('.')[0];
	}
    dollars = dollars;
    let cool = document.getElementById('exact_turnover_amount').value = Number(dollars);
    console.log('cool',typeof cool)
}

// Following function should go in utils and 
// be used as aaalife.utils.formatDollars

/**
 * formatDollars - formats a number by adding $ and separators
 * @param {number} dollars - dollar value to format
 * @param {=string} separator - (optional) defaults to comma
 * @return {string} - formatted dollar string
 */
function formatDollars(dollars, separator) {
    console.log('dollars',dollars)
	var parts = dollars.toString().split('.');
	var separator = separator || ',';
	
	parts[0] = parts[0]
				.toString() // make it a string
				.replace(/\D*/g, '') // replace non-numbers
				.replace(/\B(?=(\d{3})+(?!\d))/g, separator); // insert commas
	
	dollars = 'ZAR ' + parts.join('.');
    // document.getElementById('exact_turnover_amount').value = dollars;
	console.log('Fuckit',form.exact_turnover_amount)
   
    document.getElementById('exact_turnover_amount').value = dollars;
   
	return dollars;
}

        const submit = () => {
            form.post(route('submit_turn_over_information', props.renewal.id), {
            onSuccess: () => {
              if(props.success){
                notifySuccess(props.success);
              }else if(props.error){
                notifyError(props.error)
              }
            }
        })
        }
        return{ options, form, submit,toast,formatToCurrency}
    }
}


</script>

<template>
    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Beer</label>
            <input v-model="form.volume_of_beer" type="number" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Wine</label>
            <input v-model="form.volume_of_wine" type="number" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Spirit/Other</label>
            <input v-model="form.volume_of_spirit" type="number" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Exact Turnover Amount</label>
            <input  @blur="formatToCurrency"  id="exact_turnover_amount" type="number" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Number of Employees</label>
            <input v-model="form.number_of_employees" type="number" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Annual Turnover</label>
            <select v-model="form.annual_turnover" class="form-control form-control-default">
                <option :value="''" selected disabled>Select...</option>
                <option v-for="option in options" :key="option.id" :value="option.id">{{ option.name }}</option>

            </select>
        </div>
    </div>

    <div class="col-md-12 columns">
        <button @click="submit()" :disabled="form.processing" type="button" class="btn btn-sm btn-info float-end">
            <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Save</button>
    </div>
</template>

<style scoped>

</style>
