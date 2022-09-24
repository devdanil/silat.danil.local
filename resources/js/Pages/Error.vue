<template>
    <Head :title="status" />
    <div
        class="flex items-center justify-center w-screen h-screen bg-gradient-to-b from-gray-600 to-gray-700"
    >
        <div class="px-40 py-20 bg-white rounded-md shadow-xl">
            <div class="flex flex-col items-center">
                <h1 class="font-bold text-sky-500 text-9xl">{{ status }}</h1>

                <h6
                    class="mb-2 text-2xl font-bold text-center text-gray-800 md:text-3xl"
                >
                    <span class="text-red-500">Oops!</span> {{ title }}
                </h6>

                <p class="mb-8 text-center text-gray-500 md:text-lg">
                    {{ description }}
                </p>

                <Link :href="route('login')" class="btn btn-md btn-sky"
                    >Go back home</Link
                >
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
export default defineComponent({
    props: {
        status: Number,
    },
    components: {
        Head,
        Link,
    },
    computed: {
        title() {
            return {
                503: "Service Unavailable",
                500: "Server Error",
                404: "Page Not Found",
                403: "Forbidden",
            }[this.status];
        },
        description() {
            return {
                503: "Sorry, we are doing some maintenance. Please check back soon.",
                500: "Whoops, something went wrong on our servers.",
                404: "Sorry, the page you are looking for could not be found.",
                403: "Sorry, you are forbidden from accessing this page.",
            }[this.status];
        },
    },
});
</script>
