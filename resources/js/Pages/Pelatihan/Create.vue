<template>
  <AppLayout>
    <template #breadcrumb>
      <li>
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />

          <Link
            :href="route('pelatihan.index')"
            class="text-sm font-medium text-sky-500"
            >Pelatihan</Link
          >
        </div>
      </li>
      <li aria-current="page">
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
          <span class="text-sm font-medium text-gray-500">Tambah</span>
        </div>
      </li>
    </template>
    <div class="mb-10">
      <Link :href="route('pelatihan.index')" class="btn btn-md btn-slate mb-3"
        ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
      >
      <form @submit.prevent="submit">
        <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
          <div class="p-6 space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Katalog Pelatihan<i class="text-red-500 tex-xs">*</i>
              </label>
              <select v-model="form.katalog_id" class="f-input px-2" required>
                <option value="">Pilih</option>
                <option
                  v-for="(item, index) in katalog"
                  :key="index"
                  :value="item.id"
                >
                  {{ item.judul }}
                </option>
              </select>
            </div>

            <div class="space-y-3 sm:space-y-0 sm:grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Tanggal Mulai Pendaftaran<i class="text-red-500 tex-xs">*</i>
                </label>
                <input
                  type="date"
                  v-model="form.mulai_pendaftaran"
                  class="f-input px-2"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Tanggal Selesai Pendaftaran<i class="text-red-500 tex-xs"
                    >*</i
                  >
                </label>
                <input
                  type="date"
                  v-model="form.selesai_pendaftaran"
                  class="f-input px-2"
                  required
                />
              </div>
            </div>
            <div class="space-y-3 sm:space-y-0 sm:grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Tanggal Mulai Pelatihan<i class="text-red-500 tex-xs">*</i>
                </label>
                <input
                  type="date"
                  v-model="form.mulai_pelatihan"
                  class="f-input px-2"
                  required
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Tanggal Selesai Pelatihan<i class="text-red-500 tex-xs">*</i>
                </label>
                <input
                  type="date"
                  v-model="form.selesai_pelatihan"
                  class="f-input px-2"
                  required
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Kuota Peserta<i class="text-red-500 tex-xs">*</i>
              </label>
              <input
                type="number"
                v-model="form.kuota"
                class="f-input px-2"
                required
              />
            </div>
          </div>

          <div
            class="
              p-6
              flex flex-col
              sm:block
              border-t
              bg-gray-50
              rounded-b-lg
              space-y-1
            "
          >
            <button
              @click="form.status_id = 2"
              type="submit"
              title="Simpan dan Submit"
              class="btn btn-md btn-teal"
            >
              <PaperAirplaneIcon class="h-5 w-5 mr-2" />
              Simpan & Submit</button
            ><button
              @click="form.status_id = 1"
              type="submit"
              title="Simpan"
              class="btn btn-md btn-sky sm:ml-1"
            >
              <ArrowDownOnSquareIcon class="h-5 w-5 mr-2" />
              Simpan
            </button>
            <Link
              :href="route('pelatihan.index')"
              class="btn btn-md btn-slate sm:ml-1"
              ><XMarkIcon class="w-5 h-5 mr-2" />Batal</Link
            >
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/Admin.vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  ArrowDownOnSquareIcon,
  PlusIcon,
  MinusIcon,
  XMarkIcon,
  PaperAirplaneIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
  components: {
    AppLayout,
    Link,
    ChevronLeftIcon,
    ChevronRightIcon,
    PaperAirplaneIcon,
    ArrowDownOnSquareIcon,
    PlusIcon,
    MinusIcon,
    XMarkIcon,
  },

  props: {
    title: String,
    katalog: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        katalog_id: "",
        kuota: "",
        mulai_pelatihan: null,
        selesai_pelatihan: null,
        mulai_pendaftaran: null,
        selesai_pendaftaran: null,
        status_id: 1,
      }),
    };
  },

  methods: {
    submit() {
      let d1 = new Date(this.form.mulai_pendaftaran);
      let d2 = new Date(this.form.selesai_pendaftaran);
      let d3 = new Date(this.form.mulai_pelatihan);
      let d4 = new Date(this.form.selesai_pelatihan);
      if (d1.getTime() > d2.getTime()) {
        Swal.fire({
          text: "Tanggal selesai pendaftaran harus sama atau lebih besar dari tanggal mulai pendaftaran",
          icon: "warning",
          confirmButtonColor: "#0ea5e9",
        });
      } else if (d3.getTime() > d4.getTime()) {
        Swal.fire({
          text: "Tanggal selesai pelatihan harus sama atau lebih besar dari tanggal mulai pelatihan",
          icon: "warning",
          confirmButtonColor: "#0ea5e9",
        });
      } else {
        Swal.fire({
          text: "Apakah anda yakin akan menyimpan data ini ?",
          icon: "question",
          confirmButtonText: "Ya, Lanjutkan",
          cancelButtonText: "Batalkan",
          confirmButtonColor: "#0ea5e9",
          cancelButtonColor: "#ef4444",
          showCancelButton: true,
        }).then((result) => {
          if (result.isConfirmed) {
            this.form.post(this.route("pelatihan.store"));
          }
        });
      }
    },
  },
});
</script>
