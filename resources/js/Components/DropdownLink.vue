<script setup>
import { Link } from "@inertiajs/inertia-vue3";
const props = defineProps(["active"]);
</script>

<template>
    <Link
        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:text-white hover:bg-[#2F3B59] focus:outline-none focus:text-white focus:bg-[#2F3B59] transition duration-150 ease-in-out"
        :class="{ 'text-white bg-[#2F3B59]': props.active }"
    >
        <slot />
    </Link>
</template>
