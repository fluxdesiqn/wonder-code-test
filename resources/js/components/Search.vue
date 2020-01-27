<template>
    <div class="show-specialists">
        <v-container fluid>
            <h1>All specialists</h1>
            <v-row>
                <v-col class="d-flex" cols="12" sm="4">
                    <v-select
                        :items="items"
                        label="Standard"
                    ></v-select>
                </v-col>
                <v-col class="d-flex" cols="12" sm="8">
                    <v-text-field type="text" v-model="search" placeholder="Search specialists"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="6" v-for="specialist in filteredSpecialists">
                    <v-card class="p-b-5">
                        <v-card-title>{{ specialist.name }}</v-card-title>
                        <v-card-text>{{ specialist.title }} at {{ specialist.hospital }}</v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            specialists: [],
            search: ""
        }
    },
    methods: {

    },
    created() {
        this.$http.get('/api/specialists/').then(function(data) {
            this.specialists = data.body;
        });
    },
    computed: {
        filteredSpecialists: function() {
            return this.specialists.filter((specialist) => {
                var specialistData = specialist.name + specialist.title + specialist.hospital;
                return specialistData.toLowerCase().match(this.search.toLowerCase())
            });
        }
    }
}
</script>