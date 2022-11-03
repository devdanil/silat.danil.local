<template>
  <AppLayout>
    <template #breadcrumb>
      <li>
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />

          <Link
            :href="route('users.index')"
            class="text-sm font-medium text-sky-500"
            >Users</Link
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
    <div>
      <Link :href="route('users.index')" class="btn btn-md btn-slate mb-3"
        ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
      >
      <form @submit.prevent="submit">
        <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
          <div class="overflow-auto py-6 text-gray-700 text-sm font-medium">
            <div class="grid sm:grid-cols-3 gap-3 px-6 pb-3">
              <div class="col-span-1">
                <label class="block">
                  NIP<i class="text-red-500 tex-xs">*</i>
                </label>
              </div>
              <div class="col-span-2">
                <input
                  type="text"
                  @input="setNip"
                  v-model="form.nip"
                  class="f-input px-2"
                  required
                />
              </div>
              <div class="col-span-1">
                <label class="block">
                  Nama<i class="text-red-500 tex-xs">*</i>
                </label>
              </div>
              <div class="col-span-2">
                <input
                  maxlength="150"
                  type="text"
                  v-model="form.nama"
                  class="f-input px-2"
                  required
                />
              </div>
              <div class="col-span-1">
                <label class="block">
                  Email<i class="text-red-500 tex-xs">*</i>
                </label>
              </div>
              <div class="col-span-2">
                <input
                  type="email"
                  maxlength="150"
                  v-model="form.email"
                  class="f-input px-2"
                  required
                />
              </div>
              <div class="col-span-1">
                <label class="block">
                  Password<i class="text-red-500 tex-xs">*</i>
                </label>
              </div>
              <div class="col-span-2">
                <input
                  type="password"
                  maxlength="100"
                  v-model="form.password"
                  class="f-input px-2"
                  required
                />
              </div>
              <div class="col-span-1">
                <label class="block">
                  Konfirmasi Password<i class="text-red-500 tex-xs">*</i>
                </label>
              </div>
              <div class="col-span-2">
                <input
                  maxlength="100"
                  type="password"
                  v-model="form.password_confirmation"
                  class="f-input px-2"
                  required
                />
              </div>
              <div class="col-span-1">
                <label class="block">
                  Role<i class="text-red-500 tex-xs">*</i>
                </label>
              </div>
              <div class="col-span-2">
                <select class="f-input px-2" v-model="form.role_id">
                  <option value="" disabled>Pilih</option>
                  <option :value="role.id" v-for="role in roles" :key="role.id">
                    {{ role.name }}
                  </option>
                </select>
              </div>
              <div class="col-span-1">
                <label class="block">
                  Foto <i class="pl-1 tex-xs">(Optional)</i>
                </label>
              </div>
              <div class="col-span-2">
                <div class="mt-1 relative rounded-md shadow-sm border">
                  <input
                    type="file"
                    @input="form.proof = $event.target.files[0]"
                    accept=".jpg,.jpeg,.png"
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
                  />
                </div>
              </div>
            </div>
            <div
              class="grid bg-gray-50 sm:grid-cols-3 gap-3 px-6 pt-3 border-t"
            >
              <div class="col-span-1"></div>
              <div class="col-span-2">
                <button
                  type="submit"
                  title="Simpan"
                  class="btn btn-md btn-sky sm:ml-1"
                >
                  <ArrowDownOnSquareIcon class="h-5 w-5 mr-2" />
                  Simpan
                </button>
                <Link
                  :href="route('users.index')"
                  class="btn btn-md btn-slate sm:ml-1"
                  ><XMarkIcon class="w-5 h-5 mr-2" />Batal</Link
                >
              </div>
            </div>
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
  XMarkIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
  components: {
    AppLayout,
    Link,
    ChevronLeftIcon,
    ChevronRightIcon,
    ArrowDownOnSquareIcon,
    XMarkIcon,
  },

  props: {
    title: String,
    roles: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        nip: null,
        nama: "",
        email: "",
        password: "",
        password_confirmation: "",
        role_id: "",
        foto: null,
      }),
    };
  },

  methods: {
    setNip() {
      this.form.nip = this.form.nip.replace(/\D/g, "");
    },
    submit() {
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
          this.form.post(this.route("users.store"));
        }
      });
    },
  },
});
</script>
