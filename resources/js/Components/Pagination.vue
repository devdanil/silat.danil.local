<template>
    <div class="flex flex-wrap justify-center items-center">
        <template v-for="(link, p) in links" :key="p">
            <div
                v-if="link.url === null"
                class="mr-1 mb-1 px-3 py-2 text-sm leading-4 text-gray-400 shadow rounded-md"
                v-html="link.label"
            />
            <Link
                v-else
                class="mr-1 mb-1 px-3 py-2 text-sm leading-4 shadow rounded-md bg-white hover:text-sky-500"
                :class="{
                    'bg-sky-500 text-white hover:text-white pointer-events-none cursor-default':
                        link.active,
                }"
                :disabled="link.active"
                :href="link.url"
                v-html="link.label"
                preserve-scroll
            />
        </template>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
export default defineComponent({
    props: {
        links: Array,
    },
    components: {
        Link,
    },
});
</script>
