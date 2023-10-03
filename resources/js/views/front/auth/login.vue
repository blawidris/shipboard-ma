<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img class="mx-auto h-12 w-auto select-none" :src="$page.logo_light" alt="logo" />
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">{{
                $trans('headings.signin-to-your-account') }}</h2>

            <p class="mt-2 text-center text-sm leading-5 text-gray-600 max-w">
                {{ $trans('labels.or') }}
                <inertia-link :href="route('register')"
                    class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    {{ $trans('labels.create-new-account') }}
                </inertia-link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow rounded-lg sm:px-10">
                <form action="#" method="POST" @submit.prevent="submit()">
                    <div class="form-group mb-6">
                        <span class="form-label">{{ $trans('labels.email') }}</span>
                        <input type="email" class="form-input" v-model="form.email">
                        <p class="invalid-feedback" v-if="$page.errors.has('email')">{{ $page.errors.first('email') }}</p>
                    </div>

                    <div class="form-group mb-6">
                        <span class="form-label">{{ $trans('labels.password') }}</span>
                        <div class="form-group relative flex">
                            <input v-bind:type="[showPassword ? 'text' : 'password']" class="form-input"
                                v-model="form.password">
                            <span class="flex items-center flex-end -ml-8" @click.prevent="togglePassword()">
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>

                                <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                            </span>
                        </div>
                        <p class="invalid-feedback" v-if="$page.errors.has('password')">{{ $page.errors.first('password') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" type="checkbox"
                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                v-model="form.remember" />
                            <label for="remember" class="ml-2 block text-sm leading-5 text-gray-900">
                                {{ $trans('labels.remember-me') }}
                            </label>
                        </div>

                        <div class="text-sm leading-5">
                            <inertia-link :href="route('password.reminder')"
                                class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                                {{ $trans('labels.forgot-your-password') }}
                            </inertia-link>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-block btn-indigo" :disabled="form.processing">
                            {{ $trans('labels.sign-in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Form from '@/utils/form'
import { VueToast as Toast } from 'vue-toast-notification';

// Vue.use(VueToast)

export default {
    metaInfo() {
        return {
            title: this.$trans('headings.sign-in')
        }
    },

    data() {
        return {
            showPassword: false,
            email: '',
            form: new Form({
                email: '',
                password: '',
                remember: false
            }),
        }
    },

    methods: {
        submit() {
            this.form.processing = true;

            this.$inertia.post(route('login'), this.form.data())
                .then((response) => {


                    console.log(response)

                    this.alertMessage(response.message, response.type);
                    this.form.processing = false
                });
        },

        togglePassword() {
            this.showPassword = !this.showPassword
        },

        alertMessage(text, type) {
            Toast.open({
                message: text,
                type: type,
                duration: 3000,
                position: 'top-right'
            })
        }
    }
}
</script>
