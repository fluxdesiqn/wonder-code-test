<template>
    <div id="show-specialists">
        <h1>All specialists</h1>
        <div v-for="specialist in specialists" class="show-specialist">
            <h2>{{ specialist.name }}</h2>
            <span class="h3">{{ specialist.title }}</span>
            <span class="h3">{{ specialist.hospital }}</span>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            specialists: []
        }
    },
    methods: {

    },
    created() {
        this.$http.get('/api/specialists/').then(function(data) {
            this.specialists = data.body.slice(0,10);
        });
    }
}
</script>