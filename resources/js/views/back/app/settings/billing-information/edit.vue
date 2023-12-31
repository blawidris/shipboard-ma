<template>
    <v-app-default-layout>
        <template v-slot:header>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $trans('headings.billing-information') }}</h1>
            <!-- notification -->
            <div class="ml-auto relative mr-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <span
                    class="rounded rounded-pill absolute text-xs text-center bg-gray-300 px-1 py-1.2 font-medium top-0.5 right-0 -mt-3">
                    0
                </span>
            </div>
        </template>

        <template>
            <div class="container mx-auto px-4 sm:px-6 md:px-8 py-4">
                <div class="md:grid md:gap-6 md:grid-cols-8">
                    <div class="mb-6 sm:col-span-2">
                        <v-nav-menu />
                    </div>

                    <div class="md:col-span-6">
                        <v-alert v-if="$page.flash" dismissible>
                            {{ $page.flash }}
                        </v-alert>

                        <v-alert variant="failure" v-if="$page.errors.has('demo.restrict')" dismissible>
                            {{ $page.errors.first('demo.restrict') }}
                        </v-alert>

                        <div class="flex flex-col bg-white rounded-lg shadow mb-6">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    {{ $trans('headings.billing-information') }}
                                </h3>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <div class="mb-0 grid gap-6 sm:grid-cols-2 sm:mb-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ $trans('labels.name') }}</label>
                                        <input class="form-input" v-model="form.name">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">{{ $trans('labels.phone') }}</label>
                                        <input class="form-input" v-model="form.phone">
                                    </div>
                                </div>

                                <div class="form-group mb-6">
                                    <label class="form-label">{{ $trans('labels.address-line1') }}</label>
                                    <input class="form-input" v-model="form.address_line1">
                                </div>

                                <div class="form-group mb-6">
                                    <label class="form-label">{{ $trans('labels.address-line2') }}</label>
                                    <input class="form-input" v-model="form.address_line2">
                                </div>

                                <div class="mb-0 grid gap-6 sm:grid-cols-2 sm:mb-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ $trans('labels.city') }}</label>
                                        <input class="form-input" v-model="form.city">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">{{ $trans('labels.country') }}</label>
                                        <select class="form-select" v-model="form.country">
                                            <option :value="null" selected disabled>{{ $trans('labels.select-country') }}
                                            </option>
                                            <option v-for="country in $page.countries" :value="country.alpha2">
                                                {{ country.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0 grid gap-6 sm:grid-cols-2 sm:mb-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ $trans('labels.state') }}</label>
                                        <input class="form-input" v-model="form.state">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">{{ $trans('labels.postal-code') }}</label>
                                        <input class="form-input" v-model="form.postal_code">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 text-right rounded-b-lg px-4 py-5 sm:p-6">
                                <button @click="submit()" type="button" class="btn btn-indigo" :disabled="form.processing">
                                    {{ $trans('labels.save-changes') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-app-default-layout>
</template>

<script>
import VAppDefaultLayout from '@/views/back/app/layouts/default'
import VNavMenu from '@/views/back/app/settings/nav-menu'
import VAlert from '@/components/alert'
import Form from '@/utils/form'

export default {
    metaInfo() {
        return {
            title: this.$trans('headings.billing-information')
        }
    },

    components: {
        VAppDefaultLayout,
        VNavMenu,
        VAlert,
    },

    data() {
        return {
            form: new Form({
                name: this.$page.tenant.name,
                phone: this.$page.tenant.phone,
                address_line1: this.$page.tenant.address_line1,
                address_line2: this.$page.tenant.address_line2,
                city: this.$page.tenant.city,
                country: this.$page.tenant.country,
                state: this.$page.tenant.state,
                postal_code: this.$page.tenant.postal_code,
            })
        }
    },

    methods: {
        submit() {
            this.form.processing = true;

            this.$inertia.put(route('app:billing-information.update'), this.form.data())
                .then(() => this.form.processing = false);
        }
    }
}
</script>
