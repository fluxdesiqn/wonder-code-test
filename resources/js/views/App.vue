<template>
    <v-app class="grey lighten-4">
        <v-content>
            <search></search>
        </v-content>
    </v-app>
</template>

<script>
import Search from '@/js/components/Search';

export default {
    name: 'App',
    components: { Search},
    data () {
        return {
            //
        }
    }
};
</script>