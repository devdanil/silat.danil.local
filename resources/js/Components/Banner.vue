<template>
    <div
        class="rounded-lg text-white mb-3"
        :class="{
            'bg-teal-500': style == 'success',
            'bg-red-500': style == 'danger',
        }"
        v-if="show && message"
    >
        <div class="py-2 px-3">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 flex items-center min-w-0">
                    <InformationCircleIcon
                        class="w-5 h-5"
                        v-if="style == 'success'"
                    />
                    <XCircleIcon class="w-5 h-5" v-if="style == 'danger'" />
                    <p class="ml-3 font-medium text-sm truncate">
                        {{ message }}
                    </p>
                </div>

                <div class="shrink-0 sm:ml-3">
                    <button
                        type="button"
                        class="-mr-1 flex p-2 rounded-md focus:outline-none sm:-mr-2 transition"
                        :class="{
                            'hover:bg-teal-600 focus:bg-teal-600':
                                style == 'success',
                            'hover:bg-red-600 focus:bg-red-600':
                                style == 'danger',
                        }"
                        aria-label="Dismiss"
                        @click.prevent="show = false"
                    >
                        <XMarkIcon class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import {
    InformationCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        InformationCircleIcon,
        XCircleIcon,
        XMarkIcon,
    },
    data() {
        return {
            show: true,
        };
    },

    computed: {
        style() {
            return this.$page.props.flash?.bannerStyle || "success";
        },

        message() {
            return this.$page.props.flash?.banner || "";
        },
    },
});
</script>
