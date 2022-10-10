<template>
  <AppLayout>
    <template #breadcrumb>
      <li>
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
          <Link
            :href="route('katalog.index')"
            class="text-sm font-medium text-sky-500"
            >Katalog</Link
          >
        </div>
      </li>
      <li aria-current="page">
        <div class="flex items-center">
          <ChevronRightIcon class="w-6 h-6 text-gray-400 mx-1" />
          <span class="text-sm font-medium text-gray-500">Detail</span>
        </div>
      </li>
    </template>

    <div class="mb-3">
      <div class="sm:flex justify-between items-center mb-3">
        <Link :href="route('katalog.index')" class="btn btn-md btn-slate"
          ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
        >
      </div>

      <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
        <div class="p-3 border-b">
          <h2 class="text-lg leading-6 font-medium inline-flex items-center">
            <NewspaperIcon class="h-6 w-6 mr-2" />Katalog Pelatihan
          </h2>
        </div>
        <div class="overflow-auto pb-3">
          <table class="min-w-full">
            <tr class="bg-gray-50">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Jenis Pelatihan
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top text-justify">
                Pelatihan
                {{ ucfirst(katalog.jenis_pelatihan) }}
              </td>
            </tr>
            <tr v-if="katalog.jenis_pelatihan == 'fungsional'">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Keterangan Jabatan
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top text-justify">
                <span
                  v-for="(item, index) in JSON.parse(katalog.ket_jabatan)"
                  :key="index"
                  >{{
                    item +
                    (index + 1 < JSON.parse(katalog.ket_jabatan).length
                      ? ", "
                      : ".")
                  }}</span
                >
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Judul Pelatihan
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top text-justify">
                {{ katalog.judul }}
              </td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Deskripsi
              </td>
              <td class="td">:</td>
              <td
                class="pl-3 pr-6 py-2 align-top text-justify"
                v-html="katalog.deskripsi"
              ></td>
            </tr>
            <tr class="bg-gray-50">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Silabus
              </td>
              <td class="td">:</td>
              <td
                class="pl-3 pr-6 py-2 align-top text-justify"
                v-html="katalog.silabus"
              ></td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Persyaratan Peserta
              </td>
              <td class="td">:</td>
              <td
                class="pl-3 pr-6 py-2 align-top text-justify"
                v-html="katalog.persyaratan"
              ></td>
            </tr>
            <tr class="bg-gray-50">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap w-10">
                Jenis JF yang dapat mengikuti
              </td>
              <td class="td w-3">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                <div v-for="(item, index) in katalog.jabatan" :key="item.id">
                  {{ index + 1 }}.
                  {{ item.jabatan.jabatan }}
                </div>
              </td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap w-10">
                Instansi Peserta Pelatihan
              </td>
              <td class="td w-3">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                {{ instansi[katalog.instansi] }}
              </td>
            </tr>

            <tr class="bg-gray-50" v-if="katalogs.length > 0">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Syarat Perlatihan yang pernah diikuti
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top text-justify">
                <div v-for="(item, index) in katalogs" :key="item.id">
                  {{ index + 1 }}.
                  {{ item.judul }}
                </div>
              </td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap w-10">
                Status
              </td>
              <td class="td w-3">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                <span
                  v-if="katalog.is_publish"
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
                  >Dipublish</span
                >
                <span
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
                  >Tidak Dipublish</span
                >
              </td>
            </tr>

            <tr class="bg-gray-50" v-if="katalog.bahan.length > 0">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Bahan Pelatihan
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                <ul role="list">
                  <li
                    v-for="(file, index) in katalog.bahan"
                    :key="index"
                    class="flex items-center"
                  >
                    <PaperClipIcon class="h-5 w-5 mr-1 text-gray-400" />
                    <a
                      target="_blank"
                      :href="file.file"
                      class="
                        ml-2
                        flex-1
                        w-0
                        truncate
                        text-sky-400
                        hover:text-sky-600
                      "
                    >
                      {{ file.name }}
                    </a>
                  </li>
                </ul>
              </td>
            </tr>
          </table>
        </div>
      </div>
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
  PaperClipIcon,
  NewspaperIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
  components: {
    AppLayout,
    Link,
    ChevronLeftIcon,
    ChevronRightIcon,
    PaperClipIcon,
    NewspaperIcon,
  },
  props: {
    title: String,
    katalog: Object,
    katalogs: Object,
  },
  data() {
    return {
      instansi: {
        pusat: "Pusat",
        uml: "Daerah",
        pusat_uml: " Pusat dan/atau Daerah",
      },
    };
  },
  methods: {
    ucfirst(string) {
      return string ? string.charAt(0).toUpperCase() + string.slice(1) : string;
    },
  },
});
</script>
