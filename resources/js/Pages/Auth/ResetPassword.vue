<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-gray-600 to-gray-700 px-2"
    >
        <div class="w-full sm:w-96 mt-6">
            <Head :title="title" />
            <div
                v-if="
                    $page.props.errors.email ||
                    $page.props.errors.password ||
                    $page.props.errors.password_confirmation
                "
                class="px-6 py-4 bg-red-500 rounded-lg mb-3 text-center text-white"
            >
                <span v-if="$page.props.errors.email">{{
                    $page.props.errors.email
                }}</span>
                <span v-else-if="$page.props.errors.password">{{
                    $page.props.errors.password
                }}</span>
                <span v-else>{{
                    $page.props.errors.password_confirmation
                }}</span>
            </div>
            <div class="px-6 py-4 bg-white shadow-md rounded-lg relative">
                <div class="flex flex-col items-center justify-center mt-1">
                    <img src="/img/icon.jpg" class="block h-12 w-auto" />
                    <div
                        class="font-extrabold text-2xl text-gray-600 text-center mt-2"
                    >
                        <div>Sistem Pelatihan <br />Kemetrologian</div>
                    </div>
                </div>

                <hr class="my-3" />

                <form @submit.prevent="submit">
                    <div>
                        <label class="text-gray-600">Email</label>
                        <input
                            type="email"
                            v-model="form.email"
                            class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300 read-only:bg-gray-100 read-only:focus:ring-transparent read-only:focus:border-gray-300 cursor-not-allowed"
                            readonly
                        />
                    </div>
                    <div class="mt-3">
                        <label class="text-gray-600">Password</label>
                        <div class="relative">
                            <button
                                type="button"
                                @mouseup="showPass1 = false"
                                @mousedown="showPass1 = true"
                                class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none"
                            >
                                <EyeIcon class="h-5 w-5" v-if="!showPass1" />
                                <EyeSlashIcon class="h-5 w-5" v-else />
                            </button>
                            <input
                                autofocus
                                maxlength="35"
                                :type="showPass1 ? 'text' : 'password'"
                                v-model="form.password"
                                class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                                required
                            />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="text-gray-600">Confirm Password</label>
                        <div class="relative">
                            <button
                                type="button"
                                @mouseup="showPass2 = false"
                                @mousedown="showPass2 = true"
                                class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none"
                            >
                                <EyeIcon class="h-5 w-5" v-if="!showPass2" />
                                <EyeSlashIcon class="h-5 w-5" v-else />
                            </button>
                            <input
                                maxlength="35"
                                :type="showPass2 ? 'text' : 'password'"
                                v-model="form.password_confirmation"
                                class="focus:ring-sky-300 focus:border-sky-400 w-full rounded sm:text-sm border-gray-300"
                                required
                            />
                        </div>
                    </div>
                    <hr class="my-3" />
                    <div class="flex items-center justify-end mt-2">
                        <button
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            class="btn btn-lg btn-red btn-block w-full"
                            :disabled="form.processing"
                        >
                            Reset Password
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
import {
    ArrowPathIcon,
    ArrowLeftIcon,
    EyeIcon,
    EyeSlashIcon,
} from "@heroicons/vue/24/solid";
export default defineComponent({
    components: {
        Link,
        Head,
        ArrowPathIcon,
        ArrowLeftIcon,
        EyeIcon,
        EyeSlashIcon,
    },
    data() {
        return {
            showPass1: false,
            showPass2: false,
            form: this.$inertia.form({
                token: this.token,
                email: this.email,
                password: "",
                password_confirmation: "",
            }),
        };
    },
    methods: {
        submit() {
            this.form.post(this.route("password.update"), {
                onFinish: () => {
                    this.form.reset("email");
                    this.form.reset("captcha");
                },
            });
        },
    },
    props: {
        token: String,
        email: String,
        title: String,
    },
});
</script>
