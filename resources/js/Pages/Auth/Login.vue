<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-gray-600 to-gray-700 px-2"
    >
        <div class="w-full sm:w-96 mt-6">
            <Head :title="title" />
            <div
                v-if="$page.props.errors.nip || $page.props.errors.captcha"
                class="px-6 py-4 bg-red-500 rounded-lg mb-3 text-center text-white"
            >
                <span v-if="$page.props.errors.nip">
                    {{ $page.props.errors.nip }}</span
                >
                <span v-else>{{ $page.props.errors.captcha }}</span>
            </div>
            <div
                v-if="status"
                class="px-6 py-4 bg-sky-500 rounded-lg mb-3 text-center text-white"
            >
                {{ status }}
            </div>
            <div class="px-6 py-4 bg-white shadow-md rounded-lg">
                <div class="flex flex-col items-center justify-center mt-1">
                    <img
                        :src="$page.props.base_url + '/img/icon.jpg'"
                        class="block h-12 w-auto"
                    />
                    <div
                        class="font-extrabold text-2xl text-gray-600 text-center mt-2"
                    >
                        <div>Sistem Pelatihan <br />Kemetrologian</div>
                    </div>
                </div>

                <hr class="my-3" />

                <form @submit.prevent="submit">
                    <div>
                        <label class="text-gray-600">NIP</label>
                        <input
                            type="text"
                            @input="setNip"
                            v-model="form.nip"
                            maxlength="20"
                            class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mt-3">
                        <div class="flex items-center justify-between">
                            <label class="text-gray-600">Password</label>
                            <Link
                                :href="route('password.request')"
                                class="text-sky-500 focus:text-sky-600 focus:outline-none"
                                >Lupa Password ?</Link
                            >
                        </div>
                        <div class="relative">
                            <button
                                type="button"
                                @mouseup="showPass = false"
                                @mousedown="showPass = true"
                                class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none"
                            >
                                <EyeIcon class="h-5 w-5" v-if="!showPass" />
                                <EyeSlashIcon class="h-5 w-5" v-else />
                            </button>
                            <input
                                maxlength="35"
                                :type="showPass ? 'text' : 'password'"
                                v-model="form.password"
                                class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                                required
                            />
                        </div>
                    </div>
                    <div class="rounded-lg flex mt-3">
                        <img :src="captcha" alt="Captcha" class="rounded-l" />
                        <Link
                            :href="route('login')"
                            :only="['captcha']"
                            as="button"
                            class="btn-yellow px-3 py-2 text-white justify-center rounded-r focus:outline-none"
                            preserve-state
                            preserve-scroll
                        >
                            <ArrowPathIcon class="h-5 w-5" />
                        </Link>
                    </div>
                    <div class="mt-2">
                        <label class="text-gray-600">Captcha</label>
                        <input
                            type="number"
                            v-model="form.captcha"
                            class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                            required
                        />
                    </div>
                    <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Remember me</span
                            >
                        </label>
                    </div>
                    <hr class="my-3" />
                    <div class="flex items-center justify-end mt-2">
                        <button
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            class="btn btn-lg btn-sky w-full btn-block"
                            :disabled="form.processing"
                        >
                            <span>Log In</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Checkbox from "@/Components/Checkbox.vue";
import { ArrowPathIcon, EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        Link,
        Head,
        ArrowPathIcon,
        Checkbox,
        EyeIcon,
        EyeSlashIcon,
    },
    data() {
        return {
            showPass: false,
            form: this.$inertia.form({
                nip: "",
                password: "",
                captcha: "",
                remember: null,
                rel: this.rel,
            }),
        };
    },
    methods: {
        setNip() {
            this.form.nip = this.form.nip.replace(/\D/g, "") ?? "";
        },
        submit() {
            this.form.post(this.route("login"), {
                onFinish: () => {
                    this.form.reset("password");
                    this.form.reset("captcha");
                    this.form.reset("remember");
                },
            });
        },
    },
    props: {
        rel: String,
        captcha: String,
        status: String,
        title: String,
    },
});
</script>
