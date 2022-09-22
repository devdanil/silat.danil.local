<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    components: {
        Link,
    },
    props: ["href", "active", "sidebar"],

    computed: {
        classes() {
            let dataClass = this.sidebar ? "" : "justify-center ";
            dataClass += this.active
                ? "px-3 py-2 bg-gray-700 text-white shadow inline-flex rounded-md transition"
                : "px-3 py-2 hover:text-sky-400 inline-flex rounded-md transition";
            return dataClass;
        },
    },
});
</script>
