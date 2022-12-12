<template>
  <AppLayout>
    <template #breadcrumb>
      <li aria-current="page">
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
          <span class="text-sm font-medium text-gray-500">Hasil Pelatihan</span>
        </div>
      </li>
    </template>
    <div class="flex items-center justify-between space-x-1 sm:justify-end">
      <a
        v-if="hasRolesID([1])"
        :href="route('hasil.create')"
        class="btn btn-md btn-teal mb-3"
        title="Download template hasil pelatihan"
        :only="['filter']"
        ><ArrowDownTrayIcon class="mr-2 h-5 w-5" />Template Hasil Ujian</a
      >
      <button
        v-if="hasRolesID([1])"
        @click.prevent="showImportModal = true"
        title="Import data hasil pelatihan"
        class="btn btn-md btn-sky mb-3"
      >
        <ArrowUpTrayIcon class="mr-2 h-5 w-5" />Import Hasil Pelatihan
      </button>
    </div>

    <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
      <div class="px-4 py-5 border-b">
        <h2 class="text-lg leading-6 font-medium inline-flex items-center">
          <NewspaperIcon class="h-6 w-6 mr-2" />Daftar Hasil Pelatihan
        </h2>
      </div>
      <div class="px-4 py-2 border-b bg-gray-50">
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
          <div class="rounded-md flex sm:ml-auto mt-3 sm:mt-0">
            <input
              @keyup.enter="filters"
              type="text"
              v-model="filter.search"
              class="
                focus:ring-sky-500 focus:border-sky-500
                w-full
                sm:w-auto
                rounded-none rounded-l-md
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
              <th scope="col" class="text-left th">No.</th>
              <th scope="col" class="text-left th">NIP</th>
              <th scope="col" class="text-left th">Nama</th>
              <th scope="col" class="text-left th">No. Sertifikat</th>
              <th scope="col" class="text-left th">Tanggal Sertifikat</th>
              <th scope="col" class="text-left th">Lama Mengikuti</th>
              <th scope="col" class="text-left th">Total Jam Pelajaran</th>
              <th scope="col" class="text-left th">Nilai</th>
              <th scope="col" class="text-left th">Status</th>
              <th scope="col" class="text-left th">Predikat</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="list.total == 0">
              <td colspan="10" class="text-center px-3 py-2">Kosong</td>
            </tr>
            <tr v-else v-for="(item, index) in list.data" :key="item.id">
              <td class="td">
                {{ (list.current_page - 1) * list.per_page + index + 1 }}
              </td>
              <td class="td">
                {{ item.peserta.nip }}
              </td>
              <td class="td">
                {{ item.peserta.nama_lengkap }}
              </td>
              <td class="td">
                {{ item.no_sertifikat }}
              </td>
              <td class="td">
                {{ dateIndo(item.tgl_sertifikat) }}
              </td>
              <td class="td">
                {{ item.total_hari + " Hari" }}
              </td>
              <td class="td">
                {{ item.total_jam + " Jam" }}
              </td>
              <td class="td">
                {{ item.nilai }}
              </td>

              <td class="td">
                <span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-teal-200
                    text-teal-900
                    shadow-teal-500
                  "
                  v-if="item.status == 'Lulus'"
                  >{{ item.status }}</span
                ><span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-red-200
                    text-red-900
                    shadow-red-500
                  "
                  v-else
                  >{{ item.status }}</span
                >
              </td>
              <td class="td">
                <span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-teal-200
                    text-teal-900
                    shadow-teal-500
                  "
                  v-if="item.predikat == 'Sangat Baik'"
                  >{{ item.predikat }}</span
                ><span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-blue-200
                    text-blue-900
                    shadow-blue-500
                  "
                  v-else-if="item.predikat == 'Baik'"
                  >{{ item.predikat }}</span
                ><span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-violet-200
                    text-violet-900
                    shadow-violet-500
                  "
                  v-else-if="item.predikat == 'Cukup'"
                  >{{ item.predikat }}</span
                ><span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-yellow-200
                    text-yellow-900
                    shadow-yellow-500
                  "
                  v-else-if="item.predikat == 'Kurang'"
                  >{{ item.predikat }}</span
                ><span
                  class="
                    px-2
                    py-0.5
                    font-semibold
                    text-xs
                    tracking-wider
                    rounded-full
                    shadow
                    bg-red-200
                    text-red-900
                    shadow-red-500
                  "
                  v-else
                  >{{ item.predikat }}</span
                >
              </td>
              <td valign="top" class="td whitespace-nowrap text-center"></td>
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
        <Pagination :links="list.links" />
      </div>
    </div>
    <form
      method="post"
      @submit.prevent="importPelatihan"
      v-if="hasRolesID([1])"
    >
      <Modal :show="showImportModal">
        <div class="bg-gray-50 px-4 py-3 sm:px-6">
          <div
            class="
              text-xl
              font-semibold
              tracking-wider
              flex
              items-center
              text-gray-500
            "
          >
            <PencilSquareIcon class="w-6 h-6 mr-2" /> Import Hasil Pelatihan
          </div>
        </div>
        <div class="bg-white p-4 sm:px-6">
          <div class="mt-1 relative rounded-md shadow-sm border">
            <input
              type="file"
              @input="form.file = $event.target.files[0]"
              accept=".xls,.xlsx"
              class="
                w-full
                focus:outline-none
                border-1 border-gray-300
                focus:ring-2 focus:ring-sky-300
                rounded
                sm:text-sm
                file:border-0
                pr-14
                file:mr-5
                file:py-1.5
                file:px-2
                file:rounded-l
                file:text-md
                file:font-semibold
                file:bg-gray-300
                file:text-gray-600
                hover:file:cursor-pointer
                hover:file:bg-gray-400
                hover:file:text-gray-700
              "
              required
            />
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
          <button type="submit" title="Simpan" class="btn btn-md btn-sky">
            <ArrowUpTrayIcon class="h-5 w-5 mr-1" />
            Import
          </button>
          <button
            @click="showImportModal = false"
            type="button"
            class="btn btn-sm btn-gray ml-1"
          >
            <XMarkIcon class="w-5 h-5 mr-1" /> Tutup
          </button>
        </div>
      </Modal>
    </form>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
  PencilSquareIcon,
  MagnifyingGlassIcon,
  NewspaperIcon,
  ChevronRightIcon,
  ArrowUpTrayIcon,
  XMarkIcon,
  ArrowDownTrayIcon,
} from "@heroicons/vue/24/solid";
import Modal from "@/Components/Modal.vue";
export default defineComponent({
  components: {
    AppLayout,
    Pagination,
    Link,
    PencilSquareIcon,
    MagnifyingGlassIcon,
    NewspaperIcon,
    ChevronRightIcon,
    Modal,
    ArrowUpTrayIcon,
    XMarkIcon,
    ArrowDownTrayIcon,
  },
  props: {
    title: String,
    list: Object,
    filter: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        file: null,
      }),
      showImportModal: false,
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
      this.$inertia.get(this.route("hasil.index"), this.filter, {
        preserveState: true,
        only: ["filter", "list"],
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
    importPelatihan() {
      Swal.fire({
        text: "Apakah anda yakin akan mengimport data ini ?",
        icon: "question",
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batalkan",
        showCancelButton: true,
        confirmButtonColor: "#0ea5e9",
        cancelButtonColor: "#ef4444",
      }).then((result) => {
        if (result.isConfirmed) {
          this.form.post(this.route("hasil.store"), {
            preserveScroll: true,
            only: ["list", "flash", "errors"],
            onFinish: () => {
              this.showImportModal = false;
              this.form.file = null;
            },
          });
        }
      });
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
