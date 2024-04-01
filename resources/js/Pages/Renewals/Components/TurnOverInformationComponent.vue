<script>
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    name: 'TurnOverInformationComponent',

    props: {

    }

    setup() {
        const options = [
            {id: 1, name: 'Between R5 Million and R15 Million'},
            {id: 2, name: 'Between R15 Million and R250 Million'},
            {id: 3, name: 'Less than R5 Million'},
            {id: 4, name: 'Between R250 Million and R1 Billion'},
            {id: 5, name: 'More than R1 Billion'},
            {id: 6, name: 'Dormant'}
        ],
        const form = useForm({
            
        })

        return { form,options }
    }
}
</script>

<template>
    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Beer</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Wine</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Spirit/Other</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Exact Turnover Amount</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Number of Employees</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Annual Turnover</label>
            <select class="form-select">
                <option :value="''" selected disabled>Select...</option>
                <option v-for="option in options" :key="option.id" :value="option.id">{{ option.name}}</option>

            </select>
        </div>
    </div>

    <div class="col-md-6 columns">
        <button type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
</template>

<style scoped>

</style>
