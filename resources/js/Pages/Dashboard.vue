<template>
  <AppLayout>
    <template #breadcrumb>
      <li aria-current="page">
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
          <span class="text-sm font-medium text-gray-500">Dashboard</span>
        </div>
      </li>
    </template>
    <Link
      as="a"
      :href="route('pelatihan.index')"
      v-if="$page.props.task > 0"
      class="p-3 mb-3 rounded-lg text-white bg-red-500 shadow flex items-center"
    >
      <InformationCircleIcon class="h-5 w-5 mr-2" />
      Anda memiliki {{ $page.props.task }} tugas yang belum diselesaikan.
    </Link>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3 pb-4 pt-1">
      <div
        class="
          bg-sky-500
          shadow
          rounded-lg
          p-6
          text-white
          font-semibold
          col-span-1
          flex flex-col
          justify-between
        "
      >
        <div class="text-xl">Draft Pelatihan</div>
        <div class="flex justify-between items-end">
          <BellIcon class="h-10 w-10" />
          <div class="text-2xl">{{ count.new }}</div>
        </div>
      </div>

      <div
        class="
          bg-yellow-500
          shadow
          rounded-lg
          p-6
          text-white
          font-semibold
          col-span-1
          flex flex-col
          justify-between
        "
      >
        <div class="text-xl">Pelatihan Diproses</div>
        <div class="flex justify-between items-end">
          <WrenchScrewdriverIcon class="h-10 w-10" />
          <div class="text-2xl">{{ count.progress }}</div>
        </div>
      </div>
      <div
        class="
          bg-violet-500
          shadow
          rounded-lg
          p-6
          text-white
          font-semibold
          col-span-1
          flex flex-col
          justify-between
        "
      >
        <div class="text-xl">Pelatihan Sedang Berjalan</div>
        <div class="flex justify-between items-end">
          <ClockIcon class="h-10 w-10" />
          <div class="text-2xl">{{ count.running }}</div>
        </div>
      </div>
      <div
        class="
          bg-teal-500
          shadow
          rounded-lg
          p-6
          text-white
          font-semibold
          col-span-1
          flex flex-col
          justify-between
        "
      >
        <div class="text-xl">Pelatihan Siap Berjalan</div>
        <div class="flex justify-between items-end">
          <CheckBadgeIcon class="h-10 w-10" />
          <div class="text-2xl">{{ count.finish }}</div>
        </div>
      </div>

      <div
        class="
          bg-rose-500
          shadow
          rounded-lg
          p-6
          text-white
          font-semibold
          col-span-1
          sm:col-span-2
          xl:col-span-1
          flex flex-col
          justify-between
        "
      >
        <div class="text-xl">Pelatihan Dibatalkan</div>
        <div class="flex justify-between items-end">
          <InformationCircleIcon class="h-10 w-10" />
          <div class="text-2xl">{{ count.canceled }}</div>
        </div>
      </div>
    </div>
    <div class="bg-white shadow rounded-lg p-6 border-t-2 border-sky-500">
      <div class="rounded-md flex sm:ml-3 mt-3 sm:mt-0 justify-center mb-3">
        <span
          class="
            inline-flex
            items-center
            px-3
            rounded-l-md
            border border-r-0 border-gray-300
            bg-sky-50
            text-gray-500 text-sm
          "
        >
          Tahun
        </span>
        <select
          @change.prevent="filters"
          class="
            focus:ring-sky-500 focus:border-sky-500
            w-full
            sm:w-auto
            rounded-none rounded-r-md
            sm:text-sm
            border-gray-300
          "
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
  WrenchScrewdriverIcon,
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
    WrenchScrewdriverIcon,
  },
  props: {
    pelatihan: Object,
    years: Array,
    filter: Object,
    count: Object,
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
            label: "Pelatihan Sedang Berjalan",
            data: this.pelatihan.running,
            backgroundColor: "#8b5cf6",
          },
          {
            label: "Pelatihan Siap Berjalan",
            data: this.pelatihan.finish,
            backgroundColor: "#14b8a6",
          },
          {
            label: "Pelatihan Dibatalkan",
            data: this.pelatihan.canceled,
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
