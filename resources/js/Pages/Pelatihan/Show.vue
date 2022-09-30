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
          <span class="text-sm font-medium text-gray-500">Detail</span>
        </div>
      </li>
    </template>
    <div
      class="
        p-3
        mb-3
        rounded-lg
        text-sky-900
        bg-sky-200
        shadow
        flex
        items-center
      "
      v-if="
        pelatihan.status.notification && hasRolesID([pelatihan.status.role_id])
      "
    >
      <InformationCircleIcon class="h-5 w-5 mr-2" />
      {{ pelatihan.status.notification }}
    </div>
    <div class="mb-3">
      <div class="sm:flex justify-between items-center mb-3">
        <Link :href="route('pelatihan.index')" class="btn btn-md btn-slate"
          ><ChevronLeftIcon class="w-5 h-5 mr-2" />Kembali</Link
        >
        <button
          type="button"
          @click.prevent="submit(pelatihan.status.next_id)"
          class="btn btn-md btn-teal"
          v-if="
            (pelatihan.status_id == 1 ||
              ((pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
                bobots.length > 0)) &&
            hasRolesID([pelatihan.status.role_id])
          "
        >
          <PaperAirplaneIcon class="h-5 w-5 mr-2" /> Submit Pelatihan
        </button>
        <div
          v-if="
            pelatihan.status_id == 4 &&
            // new Date(today).getTime() > pelatihan.selesai_pendaftaran &&
            hasRolesID([pelatihan.status.role_id])
          "
        >
          <button
            type="button"
            @click.prevent="batasKonfirmasi"
            class="btn btn-md btn-sky"
          >
            <CheckIcon class="h-5 w-5 mr-2" /> Lanjutkan Pelatihan
          </button>
          <button
            type="button"
            @click.prevent="tanggalPendaftaran"
            class="btn btn-md btn-yellow ml-1"
          >
            <PencilSquareIcon class="h-5 w-5 mr-2" /> Tanggal Pendaftaran
          </button>
        </div>
        <div
          v-if="
            pelatihan.status_id == 5 &&
            hasRolesID([pelatihan.status.role_id]) &&
            new Date(today).getTime() >
              new Date(pelatihan.batas_konfirmasi).getTime()
          "
        >
          <button
            type="button"
            @click.prevent="submit(pelatihan.status.next_id)"
            class="btn btn-md btn-sky"
          >
            <CheckIcon class="h-5 w-5 mr-2" /> Lanjutkan Pelatihan
          </button>
          <button
            v-if="katalog.kuota > confirmed.approved"
            type="button"
            @click.prevent="batasKonfirmasi()"
            class="btn btn-md btn-yellow ml-1"
          >
            <PencilSquareIcon class="h-5 w-5 mr-2" /> Batas Konfirmasi
          </button>
        </div>
      </div>
      <div class="bg-white shadow rounded-lg border-t-2 border-sky-500">
        <div class="p-3 border-b">
          <h2 class="text-lg leading-6 font-medium inline-flex items-center">
            <NewspaperIcon class="h-6 w-6 mr-2" />Katalog Penilaian
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
            <tr>
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
            <tr
              class="bg-gray-50"
              v-if="katalog.jenis_pelatihan == 'fungsional'"
            >
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Angka Kredit Minimal
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top text-justify">
                {{ katalog.angka_kredit }}
              </td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Kuota Peserta
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                {{ pelatihan.kuota }} Orang
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Tanggal Pendaftaran
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                {{
                  dateIndo(pelatihan.mulai_pendaftaran) +
                  " s/d " +
                  dateIndo(pelatihan.selesai_pendaftaran)
                }}
              </td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Tanggal Pelatihan
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                {{
                  dateIndo(pelatihan.mulai_pelatihan) +
                  " s/d " +
                  dateIndo(pelatihan.selesai_pelatihan)
                }}
              </td>
            </tr>
            <tr
              class="bg-gray-50"
              v-if="pelatihan.status_id > 7 && pelatihan.batas_konfirmasi"
            >
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Batas Konfirmasi
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                {{ dateIndo(pelatihan.batas_konfirmasi) }}
              </td>
            </tr>
            <tr>
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">Status</td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
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
                      pelatihan.status_id == 1,
                    'bg-yellow-200 text-yellow-900  shadow-yellow-500':
                      pelatihan.status_id > 1 && pelatihan.status_id < 6,
                    'bg-red-200 text-red-900 shadow-red-500':
                      pelatihan.status_id == 7,
                    'bg-teal-200 text-teal-900 shadow-teal-500':
                      pelatihan.status_id == 6,
                  }"
                  >{{ pelatihan.status.name }}</span
                >
              </td>
            </tr>
            <tr
              class="bg-gray-50"
              v-if="pelatihan.status_id == 10 || pelatihan.status_id == 12"
            >
              <td class="pl-6 pr-3 align-top py-2 whitespace-nowrap">
                Alasan Penolakan
              </td>
              <td class="td">:</td>
              <td class="pl-3 pr-6 py-2 align-top">
                {{ pelatihan.keterangan }}
              </td>
            </tr>
            <tr v-if="katalog.bahan.length > 0">
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
      <div class="mt-3 sm:text-right">
        <button
          v-if="
            (pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
            hasRolesID([pelatihan.status.role_id])
          "
          type="button"
          class="btn btn-md btn-sky"
          @click="
            (showModal = !showModal),
              (bobot.id = null),
              (bobot.key = ''),
              (bobot.bobot = null)
          "
        >
          <PlusIcon class="h-5 w-5 mr-2" />Bobot
        </button>
      </div>
      <div
        class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
        v-if="pelatihan.status_id > 1"
      >
        <div class="p-3 border-b">
          <h2 class="text-lg leading-6 font-medium inline-flex items-center">
            <AcademicCapIcon class="h-6 w-6 mr-2" />Bobot Penilaian
          </h2>
        </div>
        <div class="overflow-auto pb-3">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="
                    text-left
                    pl-6
                    pr-3
                    text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  No.
                </th>
                <th scope="col" class="text-left th">Kriteria</th>
                <th scope="col" class="text-right th">Bobot</th>
                <th
                  scope="col"
                  class="
                    text-right
                    pl-3
                    pr-6
                    text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  Jumlah Peserta
                </th>
                <th
                  scope="col"
                  class="
                    pl-3
                    pr-6
                    text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                    text-center
                  "
                  v-if="
                    (pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
                    hasRolesID([pelatihan.status.role_id])
                  "
                >
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="bobots.length == 0">
                <td colspan="5" class="text-center">Kosong</td>
              </tr>
              <tr v-for="(item, index) in bobots" :key="index">
                <td class="pl-6 pr-3 py-2 align-top w-1 whitespace-nowrap">
                  {{ index + 1 }}.
                </td>
                <td class="td">
                  {{ item.variable.value }}
                </td>
                <td class="td text-right">{{ item.bobot }}</td>
                <td class="pl-3 pr-6 py-2 align-top text-right">
                  {{ jumlah_peserta[item.key] }}
                </td>
                <td
                  class="pl-3 pr-6 py-2 align-top text-center"
                  v-if="
                    (pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
                    hasRolesID([pelatihan.status.role_id])
                  "
                >
                  <button
                    type="button"
                    title="Ubah"
                    @click="
                      (showModal = true),
                        (bobot.id = item.id),
                        (bobot.key = item.key),
                        (bobot.bobot = item.bobot)
                    "
                    class="ml-1 btn btn-sm btn-yellow"
                    v-if="
                      (pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
                      hasRolesID([pelatihan.status.role_id])
                    "
                  >
                    <PencilSquareIcon class="h-5 w-5" />
                  </button>
                  <button
                    title="Hapus"
                    type="button"
                    @click="deleteBobot(item.id)"
                    class="ml-1 btn btn-sm btn-red"
                    v-if="
                      (pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
                      hasRolesID([pelatihan.status.role_id])
                    "
                  >
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <form
        @submit.prevent="saveBobot"
        method="post"
        v-if="
          (pelatihan.status_id == 2 || pelatihan.status_id == 3) &&
          hasRolesID([pelatihan.status.role_id])
        "
      >
        <Modal :show="showModal">
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
              <PencilSquareIcon class="w-6 h-6 mr-2" /> Form Bobot
            </div>
          </div>
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="grid grid-cols-3 gap-3">
              <div class="col-span-1">Kriteria</div>
              <div class="col-span-2">
                <select
                  v-model="bobot.key"
                  class="f-input px-2 focus:border-transparent"
                  required
                >
                  <option value="">Pilih</option>
                  <option
                    v-for="(item, index) in kriteria"
                    :key="index"
                    :value="item.key"
                  >
                    {{ item.value }}
                  </option>
                </select>
              </div>
              <div class="col-span-1">Bobot</div>
              <div class="col-span-2">
                <div class="border flex items-center rounded border-gray-300">
                  <input
                    v-model="bobot.bobot"
                    type="number"
                    min="0"
                    max="100"
                    class="
                      focus:ring-sky-300
                      py-1.5
                      focus:border-sky-400
                      w-full
                      border-0
                      rounded-l
                      sm:text-sm
                      border-gray-300
                      px-2
                    "
                    required
                  />
                  <span
                    class="
                      position-absolute
                      border-l
                      right-0
                      px-2
                      py-1.5
                      rounded-r
                      sm:text-sm
                      border-gray-300
                    "
                    >%</span
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 text-right">
            <button type="submit" title="Simpan" class="btn btn-md btn-sky">
              <ArrowDownOnSquareIcon class="h-5 w-5 mr-1" />
              Simpan
            </button>
            <button
              @click="showModal = false"
              type="button"
              class="btn btn-sm btn-red ml-1"
            >
              <XMarkIcon class="w-5 h-5 mr-1" /> Batal
            </button>
          </div>
        </Modal>
      </form>
      <div
        class="bg-white shadow rounded-lg mt-3 border-t-2 border-sky-500"
        v-if="pelatihan.status_id > 1 && bobots.length > 0"
      >
        <div class="p-3 border-b">
          <h2 class="text-lg leading-6 font-medium inline-flex items-center">
            <UsersIcon class="h-6 w-6 mr-2" />Daftar Peserta
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
                <option :value="peserta.total">Semua</option>
              </select>
            </div>
            <div
              class="rounded-md flex pt-3 sm:pt-0 sm:ml-3"
              v-if="pelatihan.status_id > 4"
            >
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
                v-model="filter.confirmed"
              >
                <option value="">Semua</option>
                <option value="approved">Disetujui</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="rejected">Ditolak</option>
                <option value="waiting">Belum Dikonfirmasi</option>
              </select>
            </div>
            <div class="rounded-md flex sm:ml-auto mt-3 sm:mt-0">
              <select
                class="
                  focus:ring-sky-500 focus:border-sky-500
                  w-full
                  sm:w-50
                  rounded-none rounded-l-md
                  sm:text-sm
                  bg-sky-50
                  text-gray-500 text-sm
                  border-gray-300
                "
                v-model="filter.key"
              >
                <option value="nip">NIP</option>
                <option value="nama">Nama</option>
                <option value="jabatan">Jabatan</option>
                <option value="jumlah_bobot">Jumlah Bobot</option>
              </select>
              <input
                @keyup.enter="filters"
                type="text"
                v-model="filter.search"
                class="
                  focus:ring-sky-500 focus:border-sky-500
                  w-full
                  sm:w-auto
                  rounded-none
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
                  class="
                    text-left
                    pl-6
                    pr-3
                    text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  No.
                </th>
                <th scope="col" class="text-left th">Nip</th>
                <th scope="col" class="text-left th">Nama</th>
                <th scope="col" class="text-left th">Jabatan</th>

                <th
                  scope="col"
                  class="text-center th"
                  v-if="pelatihan.status_id > 4"
                >
                  Status
                </th>
                <th
                  v-if="pelatihan.status_id > 1 && bobots.length > 0"
                  scope="col"
                  class="
                    pl-3
                    pr-6
                    text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                    text-right
                  "
                >
                  Jumlah Bobot
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td
                  v-if="peserta.total == 0"
                  class="td text-center"
                  colspan="7"
                >
                  Kosong
                </td>
              </tr>
              <tr
                v-for="(item, index) in peserta.data"
                :key="index"
                :class="{
                  'text-teal-500':
                    (peserta.current_page - 1) * peserta.per_page + index <
                      pelatihan.kuota &&
                    !filter.search &&
                    !filter.confirmed &&
                    pelatihan.status_id == 5,
                  'text-red-500':
                    (peserta.current_page - 1) * peserta.per_page + index >=
                      pelatihan.kuota &&
                    !filter.search &&
                    !filter.confirmed &&
                    pelatihan.status_id == 5,
                }"
              >
                <td class="pl-6 pr-3 py-2 align-top w-1 whitespace-nowrap">
                  {{
                    (peserta.current_page - 1) * peserta.per_page + index + 1
                  }}.
                </td>
                <td class="td">
                  {{ item.nip }}
                </td>
                <td class="td">{{ item.nama_lengkap }}</td>
                <td class="td">{{ item.jabatan.jabatan }}</td>

                <td
                  class="td text-center whitespace-nowrap"
                  v-if="pelatihan.status_id > 4"
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
                      bg-teal-200
                      text-teal-900
                      shadow-teal-500
                    "
                    v-if="
                      item.pendaftaran[0] &&
                      item.pendaftaran[0].approved_at != null
                    "
                  >
                    Disetujui </span
                  ><span
                    class="
                      px-2
                      py-0.5
                      font-semibold
                      text-xs
                      tracking-wider
                      rounded-full
                      shadow
                      bg-sky-200
                      text-sky-900
                      shadow-sky-500
                    "
                    v-else-if="
                      item.pendaftaran[0] &&
                      item.pendaftaran[0].confirmed_at != null &&
                      item.pendaftaran[0].approved_at == null
                    "
                  >
                    Dikonfirmasi
                  </span>
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
                    v-else-if="
                      item.pendaftaran[0] &&
                      item.pendaftaran[0].rejected_at != null
                    "
                  >
                    Ditolak </span
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
                    v-else
                  >
                    Belum Dikonfirmasi
                  </span>
                </td>
                <td
                  class="pl-3 pr-6 py-2 align-top text-right"
                  v-if="pelatihan.status_id > 1 && bobots.length > 0"
                >
                  {{
                    item.pendaftaran.length > 0
                      ? item.pendaftaran[0].jumlah_bobot
                      : "-"
                  }}
                </td>
              </tr>
            </tbody>
            <tfoot
              class="bg-gray-50 divide-y divide-gray-200"
              v-if="pelatihan.status_id > 4"
            >
              <tr>
                <td class="pl-6 pr-3 py-2 align-top" colspan="5">
                  Jumlah Pendaftaran yang Disetujui
                </td>

                <td class="pl-3 pr-6 py-2 align-top text-right">
                  {{ confirmed.approved }}
                </td>
              </tr>
              <tr>
                <td class="pl-6 pr-3 py-2 align-top" colspan="5">
                  Jumlah Pendaftaran yang Dikonfirmasi
                </td>

                <td class="pl-3 pr-6 py-2 align-top text-right">
                  {{ confirmed.confirmed }}
                </td>
              </tr>
              <tr>
                <td class="pl-6 pr-3 py-2 align-top" colspan="5">
                  Jumlah Pendaftaran yang Ditolak
                </td>

                <td class="pl-3 pr-6 py-2 align-top text-right">
                  {{ confirmed.rejected }}
                </td>
              </tr>
              <tr>
                <td class="pl-6 pr-3 py-2 align-top" colspan="5">
                  Jumlah Pendaftaran yang Belum Dikonfirmasi
                </td>
                <td class="pl-3 pr-6 py-2 align-top text-right">
                  {{ confirmed.waiting }}
                </td>
              </tr>
            </tfoot>
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
          <div>
            Menampilkan {{ peserta.data.length }} dari {{ peserta.total }} Data
          </div>
          <Pagination :links="peserta.links" :only="['peserta', 'filter']" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/Admin.vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  ArrowDownOnSquareIcon,
  PlusIcon,
  XMarkIcon,
  PaperClipIcon,
  InformationCircleIcon,
  PencilSquareIcon,
  AcademicCapIcon,
  NewspaperIcon,
  CheckIcon,
  MagnifyingGlassIcon,
  TrashIcon,
  PaperAirplaneIcon,
  UsersIcon,
} from "@heroicons/vue/24/solid";

export default defineComponent({
  components: {
    AppLayout,
    Link,
    Pagination,
    Modal,
    ChevronLeftIcon,
    ChevronRightIcon,
    ArrowDownOnSquareIcon,
    PlusIcon,
    XMarkIcon,
    PaperClipIcon,
    InformationCircleIcon,
    PencilSquareIcon,
    AcademicCapIcon,
    NewspaperIcon,
    CheckIcon,
    MagnifyingGlassIcon,
    TrashIcon,
    PaperAirplaneIcon,
    UsersIcon,
  },
  props: {
    title: String,
    katalog: Object,
    pelatihan: Object,
    filter: Object,
    peserta: Object,
    confirmed: Object,
    kriteria: Array,
    bobots: Array,
    jumlah_peserta: Object,
  },

  data() {
    let curr_date = new Date();
    return {
      showModal: false,
      curr_date: curr_date,
      bobot: this.$inertia.form({
        id: null,
        key: "",
        bobot: null,
        pelatihan_id: this.pelatihan.id,
      }),
      instansi: {
        pusat: "Pusat",
        uml: "Daerah",
        pusat_uml: " Pusat dan/atau Daerah",
      },
      today:
        curr_date.getFullYear() +
        "/" +
        (curr_date.getMonth() + 1) +
        "/" +
        curr_date.getDate(),
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
    dateIndo(param) {
      param = new Date(param);
      let result =
        "" +
        (param.getDate() > 9 ? "" : "0") +
        param.getDate() +
        " " +
        this.bulan[param.getMonth()] +
        " " +
        param.getFullYear();
      return result;
    },
    ucfirst(string) {
      return !string
        ? string
        : string.charAt(0).toUpperCase() + string.slice(1);
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
    filters() {
      this.$inertia.get(
        this.route("pelatihan.show", this.pelatihan.slug),
        this.filter,
        {
          preserveState: true,
          preserveScroll: true,
          only: ["filter", "peserta"],
        }
      );
    },
    submit(status) {
      Swal.fire({
        text: "Apakah anda yakin akan mengirim data pelatihan ini?",
        icon: "question",
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batalkan",
        showCancelButton: true,
        confirmButtonColor: "#0ea5e9",
        cancelButtonColor: "#ef4444",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$inertia.post(
            this.route("pelatihan.process", this.pelatihan.slug),
            {
              status_id: status,
            },
            {
              only: [
                "pelatihan",
                "jumlah_peserta",
                "peserta",
                "flash",
                "errors",
              ],
            }
          );
        }
      });
    },
    saveBobot() {
      Swal.fire({
        text: "Apakah anda yakin akan menyimpan data ini?",
        icon: "question",
        confirmButtonText: "Ya, Lanjutkan",
        cancelButtonText: "Batalkan",
        showCancelButton: true,
        confirmButtonColor: "#0ea5e9",
        cancelButtonColor: "#ef4444",
      }).then((result) => {
        if (result.isConfirmed) {
          this.bobot.post(this.route("bobot.store"), {
            only: ["bobots", "jumlah_peserta", "peserta", "flash", "errors"],
            onFinish: () => {
              this.showModal = false;
            },
            preserveScroll: true,
          });
        }
      });
    },
    deleteBobot(item) {
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
          this.$inertia.delete(this.route("bobot.destroy", item), {
            preserveScroll: true,
            only: ["bobots", "peserta", "flash"],
          });
        }
      });
    },
    reject(status) {
      Swal.fire({
        title: "Alasan Penolakan",
        input: "textarea",
        inputAttributes: {
          autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Reject",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        preConfirm: function (value) {
          if (!value) {
            Swal.showValidationMessage(
              `Silahkan mengisi alasan penolakan terlebih dahulu`
            );
          } else {
            return value;
          }
        },
        allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            text: "Apakah anda yakin akan menolak data pelatihan ini ?",
            icon: "question",
            confirmButtonText: "Ya, Lanjutkan",
            cancelButtonText: "Batalkan",
            showCancelButton: true,
            confirmButtonColor: "#0ea5e9",
            cancelButtonColor: "#ef4444",
          }).then((result1) => {
            if (result1.isConfirmed) {
              this.$inertia.post(
                this.route("pelatihan.process", this.pelatihan.slug),
                {
                  status_id: status,
                  keterangan: result.value,
                },
                {
                  only: ["flash", "pelatihan"],
                }
              );
            }
          });
        }
      });
    },
    tanggalPendaftaran() {
      Swal.fire({
        title: "Tanggal Pendaftaran Pelatihan",
        html: '<input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-2" id="mulai-pendaftaran"><div class="py-2">Sampai Dengan</div><input type="date" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-2" id="selesai-pendaftaran">',
        stopKeydownPropagation: false,
        showCancelButton: true,
        confirmButtonText: "Submit",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#0ea5e9",
        cancelButtonColor: "#ef4444",
        preConfirm: function () {
          let mulai_pendaftaran =
            document.getElementById("mulai-pendaftaran").value;
          let selesai_pendaftaran = document.getElementById(
            "selesai-pendaftaran"
          ).value;

          if (
            new Date(mulai_pendaftaran).getTime() >
            new Date(selesai_pendaftaran).getTime()
          ) {
            Swal.showValidationMessage(
              "Tanggal mulai pelatihan tidak boleh lebih besar dari tanggal selesai pelatihan"
            );
          } else {
            return {
              mulai_pendaftaran: mulai_pendaftaran,
              selesai_pendaftaran: selesai_pendaftaran,
            };
          }
        },
        allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            text: "Apakah anda yakin mengubah tanggal pelatihan ini ?",
            icon: "question",
            confirmButtonText: "Ya, Lanjutkan",
            cancelButtonText: "Batalkan",
            showCancelButton: true,
            confirmButtonColor: "#0ea5e9",
            cancelButtonColor: "#ef4444",
          }).then((result1) => {
            if (result1.isConfirmed) {
              this.$inertia.post(
                this.route("pelatihan.process", this.pelatihan.slug),
                {
                  status_id: this.pelatihan.status.prev_id,
                  mulai_pendaftaran: result.value.mulai_pendaftaran,
                  selesai_pendaftaran: result.value.selesai_pendaftaran,
                },
                {
                  only: ["flash", "pelatihan"],
                }
              );
            }
          });
        }
      });
    },
    batasKonfirmasi() {
      let today = this.today;
      Swal.fire({
        title: "Batas Waktu Konfirmasi",
        html:
          '<input type="date" value="' +
          this.pelatihan.batas_konfirmasi +
          '" class="focus:ring-sky-300 focus:border-sky-400 w-full rounded-lg text-xl border-gray-300 px-6 py-3" id="batas-konfirmasi">',
        stopKeydownPropagation: false,
        showCancelButton: true,
        confirmButtonText: "Lanjutkan",
        cancelButtonText: "Tutup",
        confirmButtonColor: "#0ea5e9",
        cancelButtonColor: "#ef4444",
        preConfirm: function () {
          let batas = document.getElementById("batas-konfirmasi").value;

          if (!batas) {
            Swal.showValidationMessage(
              "Silahkan mengisi batas konfirmasi terlebih dahulu"
            );
          } else if (new Date(batas).getTime() < new Date(today).getTime()) {
            Swal.showValidationMessage("Batas konfirmasi sudah berlalu");
          } else {
            return batas;
          }
        },
        allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            text: "Apakah anda yakin akan menyimpan pelatihan ini ?",
            icon: "question",
            confirmButtonText: "Ya, Lanjutkan",
            cancelButtonText: "Batalkan",
            showCancelButton: true,
            confirmButtonColor: "#0ea5e9",
            cancelButtonColor: "#ef4444",
          }).then((result1) => {
            if (result1.isConfirmed) {
              this.$inertia.post(
                this.route("pelatihan.process", this.pelatihan.slug),
                {
                  status_id: this.pelatihan.status.next_id,
                  batas_konfirmasi: result.value,
                },
                {
                  only: ["flash", "pelatihan", "peserta", "confirmed"],
                }
              );
            }
          });
        }
      });
    },
  },
});
</script>
