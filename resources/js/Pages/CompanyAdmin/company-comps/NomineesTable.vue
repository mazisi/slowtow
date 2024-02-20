<template>
    <div class="table-responsive mb-2">
        <table class="table align-items-center mb-0">
            <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Full Name And Surname
                </th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Date OF Birth
                </th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    ID Number
                </th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="nomination.people" v-for="person in nomination.people" :key="person.id">
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-left">
                            <router-link :to="`/view-person/${person.slug}`">
                                <h6 class="mb-0 text-sm">{{ person.full_name }}</h6>
                            </router-link>
                        </div>
                    </div>
                </td>
                <td class="text-center">{{ person.date_of_birth }}</td>
                <td class="text-center">{{ person.id_or_passport }}</td>
                <td class="text-center">
                    <i @click="removeSelectedNominee(person.id)" style="cursor: pointer;" class="material-icons-round text-danger fs-10">highlight_off</i>
                    <router-link :to="`/view-person/${person.slug}`">
                        <i @click="removeSelectedNominee(person.id)" style="cursor: pointer;" class="material-icons-round text-secondary fs-10">visibility</i>
                    </router-link>
                </td>
            </tr>
            <tr v-else>
                <td></td>
                <td><p class="text-danger text-center">No nominees found.</p></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        nomination: Object, // Assuming 'nomination' is a prop passed to the component
    },
    methods: {
        removeSelectedNominee(personId) {
            // Implement the logic for removing a nominee
            // You can emit an event or call a method from the parent component
            // to handle the removal of the selected nominee
        },
    },
};
</script>

<style scoped>
/* Add your component-specific styles here */
</style>

