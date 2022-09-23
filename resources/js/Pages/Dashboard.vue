<template>
    <AppLayout>
        <template #breadcrumb>
            <li aria-current="page">
                <div class="flex items-center">
                    <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
                    <span class="text-sm font-medium text-gray-500"
                        >Dashboard</span
                    >
                </div>
            </li>
        </template>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 pb-4 pt-1"
        >
            <div
                class="bg-sky-500 shadow rounded-lg p-6 text-white font-semibold"
            >
                <div class="text-xl">Draft Pelatihan</div>
                <div class="flex justify-between align-middle">
                    <BellIcon class="h-10 w-10" />
                    <div class="text-2xl">{{ count_new }}</div>
                </div>
            </div>

            <div
                class="bg-yellow-500 shadow rounded-lg p-6 text-white font-semibold"
            >
                <div class="text-xl">Pelatihan Diproses</div>
                <div class="flex justify-between align-middle">
                    <ClockIcon class="h-10 w-10" />
                    <div class="text-2xl">{{ count_progress }}</div>
                </div>
            </div>
            <div
                class="bg-teal-500 shadow rounded-lg p-6 text-white font-semibold"
            >
                <div class="text-xl">Pelatihan Diterima</div>
                <div class="flex justify-between align-middle">
                    <CheckBadgeIcon class="h-10 w-10" />
                    <div class="text-2xl">{{ count_success }}</div>
                </div>
            </div>
            <div
                class="bg-rose-500 shadow rounded-lg p-6 text-white font-semibold"
            >
                <div class="text-xl">Pelatihan Ditolak</div>
                <div class="flex justify-between align-middle">
                    <InformationCircleIcon class="h-10 w-10" />
                    <div class="text-2xl">{{ count_failed }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 border-t-2 border-sky-500">
            <div
                class="rounded-md flex sm:ml-3 mt-3 sm:mt-0 justify-center mb-3"
            >
                <span
                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-sky-50 text-gray-500 text-sm"
                >
                    Tahun
                </span>
                <select
                    @change.prevent="filters"
                    class="focus:ring-sky-500 focus:border-sky-500 w-full sm:w-auto rounded-none rounded-r-md sm:text-sm border-gray-300"
                    v-model="filter.year"
                >
                    <option v-for="item in years" :value="item" :key="item">
                        {{ item }}
                    </option>
                </select>
            </div>
            <Bar
                :chart-options="chartOptions"
                :chart-data="chartData"
                :chart-id="chartId"
                :dataset-id-key="datasetIdKey"
                :plugins="plugins"
                :css-classes="cssClasses"
                :styles="styles"
                :width="width"
                :height="height"
            />
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
    ChevronRightIcon,
    BellIcon,
    CheckBadgeIcon,
    ClockIcon,
    InformationCircleIcon,
} from "@heroicons/vue/24/solid";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);
export default defineComponent({
    components: {
        AppLayout,
        Link,
        ChevronRightIcon,
        BellIcon,
        Bar,
        CheckBadgeIcon,
        ClockIcon,
        InformationCircleIcon,
    },
    props: {
        pelatihan: Object,
        years: Array,
        filter: Object,
        count_new: Number,
        count_progress: Number,
        count_success: Number,
        count_failed: Number,
        chartId: {
            type: String,
            default: "bar-chart",
        },
        datasetIdKey: {
            type: String,
            default: "label",
        },
        width: {
            type: Number,
            default: 400,
        },
        height: {
            type: Number,
            default: 100,
        },
        cssClasses: {
            default: "",
            type: String,
        },
        styles: {
            type: Object,
            default: () => {},
        },
        plugins: {
            type: Object,
            default: () => {},
        },
    },
    methods: {
        filters() {
            this.$inertia.get(this.route("dashboard"), this.filter, {
                only: ["pelatihan", "filter"],
            });
        },
    },
    data() {
        return {
            chartData: {
                labels: [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember",
                ],
                datasets: [
                    {
                        label: "Draft Pelatihan",
                        data: this.pelatihan.new,
                        backgroundColor: "#0ea5e9",
                    },
                    {
                        label: "Pelatihan Diproses",
                        data: this.pelatihan.progress,
                        backgroundColor: "#eab308",
                    },
                    {
                        label: "Pelatihan Diterima",
                        data: this.pelatihan.success,
                        backgroundColor: "#14b8a6",
                    },
                    {
                        label: "Pelatihan Ditolak",
                        data: this.pelatihan.failed,
                        backgroundColor: "#ef4444",
                    },
                ],
            },
            chartOptions: {
                responsive: true,
            },
        };
    },
});
</script>
