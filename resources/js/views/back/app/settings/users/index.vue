<template>
    <v-app-default-layout>
        <template v-slot:header>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $trans('headings.team-members') }}</h1>

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
                <div class="sm:grid sm:grid-cols-8 sm:gap-6">
                    <div class="sm:col-span-2 mb-6">
                        <v-nav-menu />
                    </div>

                    <div class="sm:col-span-6">
                        <v-alert variant="failure" v-if="$page.errors.has('demo.restrict')" dismissible>
                            {{ $page.errors.first('demo.restrict') }}
                        </v-alert>

                        <v-alert variant="warning" v-if="$page.errors.has('plan.limit')" dismissible>
                            {{ $page.errors.first('plan.limit') }}
                        </v-alert>

                        <div class="flex justify-end mb-6">
                            <button @click="showCreateUserModal()" type="button" class="btn btn-indigo">
                                {{ $trans('labels.add-team-member') }}
                            </button>
                        </div>

                        <div class="align-middle inline-block min-w-full shadow overflow-hidden rounded-lg">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $trans('labels.name') }}
                                        </th>

                                        <th
                                            class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $trans('labels.role') }}
                                        </th>

                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition duration-150 ease-in-out"
                                        v-for="user in $page.users">
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="avatar avatar-md" :src="user.avatar_url" :alt="user.name" />
                                                </div>

                                                <div class="ml-4">
                                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ user.name }}
                                                    </div>
                                                    <div class="text-sm leading-5 text-gray-500">{{ user.email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900" v-if="user.role === 2">{{
                                                $trans('labels.admin') }}</div>
                                            <div class="text-sm leading-5 text-gray-900" v-else>{{ $trans('labels.user') }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <a @click.prevent="showEditUserModal(user)" href="#"
                                                class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline">
                                                {{ $trans('labels.edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
import VCreateUserModal from '@/views/back/app/settings/users/modal-user-create'
import VEditUserModal from '@/views/back/app/settings/users/modal-user-edit'
import VAlert from '@/components/alert'

export default {
    metaInfo() {
        return {
            title: this.$trans('headings.team-members')
        }
    },

    components: {
        VAppDefaultLayout,
        VNavMenu,
        VAlert
    },

    methods: {
        showCreateUserModal() {
            this.$modal(VCreateUserModal);
        },

        showEditUserModal(user) {
            this.$modal(VEditUserModal, {
                uuid: user.uuid,
                name: user.name,
                email: user.email,
                role: user.role
            })
        }
    }
}
</script>
