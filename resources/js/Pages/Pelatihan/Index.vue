<template>
  <AppLayout>
    <template #breadcrumb>
      <li aria-current="page">
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
          <span class="text-sm font-medium text-gray-500">Pelatihan</span>
        </div>
      </li>
    </template>
    <div class="text-right">
      <Link
        v-if="hasRolesID([1])"
        :href="route('pelatihan.create')"
        class="btn btn-md btn-sky mb-3"
        ><PlusIcon class="mr-2 h-5 w-5" />Pelatihan</Link
      >
    </div>
    <div class="bg-white shadow rounded-lg border-t-2 border-sky-500 mb-10">
      <div class="px-4 py-5 border-b">
        <h2 class="text-lg leading-6 font-medium inline-flex items-center">
          <NewspaperIcon class="h-6 w-6 mr-2" />Daftar Pelatihan
        </h2>
      </div>
      <div class="px-4 py-2 bg-gray-50 overflow-auto">
        <div class="mt-1 flex flex-col sm:flex-row">
          <div class="rounded-md flex">
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
              Tampilkan
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
              v-model="filter.limit"
            >
              <option value="10">10</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="500">500</option>
              <option :value="list.total">Semua</option>
            </select>
          </div>

          <div class="rounded-md flex pt-3 sm:pt-0 sm:ml-3">
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
              @change.prevent="filter.year ? filters : ''"
              class="
                focus:ring-sky-500 focus:border-sky-500
                w-full
                sm:w-auto
                rounded-none
                sm:text-sm
                border-gray-300 border-r-transparent
              "
              v-model="filter.key_year"
            >
              <option value="mulai_pendaftaran">Mulai Pendaftaran</option>
              <option value="selesai_pendaftaran">Selesai Pendaftaran</option>
              <option value="mulai_pelatihan">Mulai Pelatihan</option>
              <option value="selesai_pelatihan">Selesai Pelatihan</option>
              <option value="batas_konfirmasi">Batas Konfirmasi</option>
            </select>
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
              <option value="">Semua</option>
              <option v-for="item in years" :value="item" :key="item">
                {{ item }}
              </option>
            </select>
          </div>
          <!--      <div class="rounded-md flex pt-3 sm:pt-0 sm:ml-3">
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
              Bulan
            </span>
            <select
              @change.prevent="filters"
              class="
                focus:ring-sky-500 focus:border-sky-500
                w-full
                sm:w-auto
                rounded-none
                sm:text-sm
                border-gray-300 border-r-transparent
              "
              v-model="filter.key_month"
            >
              <option value="mulai_pendaftaran">Mulai Pendaftaran</option>
              <option value="selesai_pendaftaran">Selesai Pendaftaran</option>
              <option value="mulai_pelatihan">Mulai Pelatihan</option>
              <option value="selesai_pelatihan">Selesai Pelatihan</option>
              <option value="batas_konfirmasi">Batas Konfirmasi</option>
            </select>

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
              v-model="filter.month"
            >
              <option value="">Semua</option>
              <option v-for="item in months" :value="item" :key="item">
                {{ bulan[item - 1] }}
              </option>
            </select>
          </div> -->

          <div class="rounded-md flex pt-3 sm:pt-0 sm:ml-3">
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
              Status
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
              v-model="filter.status"
            >
              <option value="">Semua</option>
              <option
                :value="stts.slug"
                v-for="stts in status"
                :key="stts.slug"
              >
                {{ stts.name }}
              </option>
            </select>
          </div>
          <div class="rounded-md flex sm:ml-auto mt-3 pl-3 sm:mt-0">
            <input
              @keyup.enter="filters"
              type="text"
              placeholder="Cari judul.."
              v-model="filter.search"
              class="
                focus:ring-sky-500 focus:border-sky-500
                w-full
                sm:w-auto
                rounded-none rounded-l-md
                placeholder:text-gray-400
                sm:text-sm
                border-gray-300
              "
            />
            <button
              type="button"
              @click.prevent="filters"
              class="
                bg-sky-500
                rounded-none rounded-r-md
                px-2
                text-white
                hover:bg-sky-600
                focus:outline-none
              "
            >
              <MagnifyingGlassIcon class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
      <div class="overflow-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                rowspan="2"
                class="border align-center text-left th"
              >
                No.
              </th>
              <th
                scope="col"
                rowspan="2"
                class="border align-center text-left th"
              >
                Judul Pelatihan
              </th>
              <th scope="col" colspan="2" class="border text-center th">
                Tanggal Pendaftaran
              </th>
              <th scope="col" colspan="2" class="border text-center th">
                Tanggal Pelatihan
              </th>
              <th
                scope="col"
                rowspan="2"
                class="border align-center text-left th"
              >
                Batas Konfirmasi
              </th>
              <th scope="col" colspan="3" class="border text-center th">
                Jumlah
              </th>
              <th
                scope="col"
                rowspan="2"
                class="border align-center text-left th"
              >
                Status
              </th>
              <th scope="col" rowspan="2" class="border align-center th">
                Aksi
              </th>
            </tr>
            <tr>
              <th scope="col" class="border text-left th">Mulai</th>
              <th scope="col" class="border text-left th">Selesai</th>
              <th scope="col" class="border text-left th">Mulai</th>
              <th scope="col" class="border text-left th">Selesai</th>
              <th scope="col" class="border text-left th">Kuota</th>
              <th scope="col" class="border text-left th">Terdaftar</th>
              <th scope="col" class="border text-left th">Dikonfirmasi</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="list.total == 0">
              <td colspan="12" class="text-center px-3 py-2">Kosong</td>
            </tr>
            <tr v-else v-for="(item, index) in list.data" :key="item.id">
              <td class="td">
                {{ (list.current_page - 1) * list.per_page + index + 1 }}
              </td>
              <td class="td">
                {{ item.katalog.judul }}
              </td>

              <td class="td whitespace-nowrap">
                {{ dateIndo(item.mulai_pendaftaran) }}
              </td>
              <td class="td whitespace-nowrap">
                {{ dateIndo(item.selesai_pendaftaran) }}
              </td>
              <td class="td whitespace-nowrap">
                {{ dateIndo(item.mulai_pelatihan) }}
              </td>
              <td class="td whitespace-nowrap">
                {{ dateIndo(item.selesai_pelatihan) }}
              </td>
              <td class="td whitespace-nowrap">
                {{ dateIndo(item.batas_konfirmasi) }}
              </td>
              <td class="td whitespace-nowrap">
                {{ item.kuota ? item.kuota + " Orang" : "0" }}
              </td>
              <td class="td whitespace-nowrap">
                {{ item.terdaftar ? item.terdaftar + " Orang" : "0" }}
              </td>
              <td class="td whitespace-nowrap">
                {{ item.konfirmasi ? item.konfirmasi + " Orang" : "0" }}
              </td>

              <td class="td whitespace-nowrap">
                <span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                  "
                  :class="{
                    'bg-sky-200 text-sky-900 shadow-sky-500':
                      item.status_id == 1,
                    'bg-yellow-200 text-yellow-900  shadow-yellow-500':
                      item.status_id > 1 && item.status_id < 6,
                    'bg-red-200 text-red-900 shadow-red-500':
                      item.status_id == 7,
                    'bg-teal-200 text-teal-900 shadow-teal-500':
                      item.status_id == 6,
                  }"
                  >{{ item.status.name }}</span
                >
              </td>

              <td valign="top" class="td whitespace-nowrap text-center">
                <Link
                  title="Detail"
                  :href="route('pelatihan.show', item.slug)"
                  class="ml-1 btn btn-sm btn-teal"
                >
                  <EyeIcon class="h-5 w-5" />
                </Link>
                <Link
                  title="Ubah"
                  :href="route('pelatihan.edit', item.slug)"
                  class="ml-1 btn btn-sm btn-yellow"
                  v-if="
                    item.status_id == 1 && hasRolesID([item.status.role_id])
                  "
                >
                  <PencilSquareIcon class="h-5 w-5" />
                </Link>
                <button
                  title="Hapus"
                  type="button"
                  @click="deleteItem(item.slug)"
                  class="ml-1 btn btn-sm btn-red"
                  v-if="
                    item.status_id == 1 && hasRolesID([item.status.role_id])
                  "
                >
                  <TrashIcon class="h-5 w-5" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div
        class="
          border-t
          flex flex-col
          sm:flex-row
          items-center
          justify-between
          p-4
        "
      >
        <div>Menampilkan {{ list.data.length }} dari {{ list.total }} Data</div>
        <Pagination :links="list.links" :only="['filter', 'list']" />
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  MagnifyingGlassIcon,
  NewspaperIcon,
  ChevronRightIcon,
  HomeIcon,
  CheckCircleIcon,
  MinusCircleIcon,
  EyeIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
  components: {
    AppLayout,
    Pagination,
    Link,
    PencilSquareIcon,
    TrashIcon,
    PlusIcon,
    MagnifyingGlassIcon,
    NewspaperIcon,
    ChevronRightIcon,
    HomeIcon,
    CheckCircleIcon,
    MinusCircleIcon,
    EyeIcon,
  },
  props: {
    title: String,
    list: Object,
    filter: Object,
    status: Array,
    years: Array,
    months: Array,
  },
  data() {
    return {
      bulan: [
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
    };
  },
  methods: {
    filters() {
      this.$inertia.get(this.route("pelatihan.index"), this.filter, {
        preserveState: true,
        only: ["filter", "list"],
      });
    },
    deleteItem(item) {
      Swal.fire({
        text: "Apakah anda yakin akan menghapus data ini ?",
        icon: "question",
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batalkan",
        showCancelButton: true,
        confirmButtonColor: "#0ea5e9",
        cancelButtonColor: "#ef4444",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$inertia.delete(this.route("pelatihan.destroy", item), {
            preserveScroll: true,
            only: ["list", "flash"],
          });
        }
      });
    },
    hasRolesID(roles_id) {
      let result = false;
      if (this.$page.props.auth.user) {
        if (!roles_id) {
          result = true;
        } else {
          let checker;
          roles_id.forEach((role_id) => {
            checker = this.$page.props.auth.roles.id.some(
              (item) => item == role_id
            );
            if (checker) {
              result = true;
            }
          });
        }
      }
      return result;
    },
    dateIndo(param) {
      let result = param;
      if (param) {
        param = new Date(param);
        result =
          "" +
          (param.getDate() > 9 ? "" : "0") +
          param.getDate() +
          " " +
          this.bulan[param.getMonth()] +
          " " +
          param.getFullYear();
      } else {
        result = "-";
      }

      return result;
    },
  },
});
</script>
