<template>
    <div class="h-0 flex-1 py-10 bg-white px-5 -mt-6 items-center justify-between hidden md:flex  max-w-7xl">
        <!--Left: Page title -->
        <div class="pr-3">
            <h2 class="text-2xl font-semibold text-gray-900">{{ pageTitle }}</h2>
        </div>
        <!-- Right Menu  -->
        <div class="gap-x-2 items-center flex">
            <inertia-link :href="route('app:calendar.index')"
                :class="[route().current('app:calendar.index') ? 'text-gray-900' : 'text-gray-700']"
                class="group felx items-center py-2 text-sm leading-6 hover:text-gray-700 transition ease-in-out duration-150">
                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current mr-3">
                    <path
                        d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                    </path>
                </svg>
            </inertia-link>

            <v-dropdown>
                <template v-slot:button>
                    <button class="flex relative bg-transparent sm:leading-6 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span
                            class="absolute text-xs text-center  font-medium top-0.5 right-0 -mt-0.5 -mr-0.5 w-2.5 h-2.5 rounded-full "
                            :class="[$page.notification.total_count > 0 ? 'bg-red-500' : '']"></span>
                    </button>
                </template>

                <template v-slot:content>
                    <ul class="dropdown-menu flex flex-col w-80 px-1">

                        <li class="dropdown-item inline-flex flex-col py-2.5 px-3"
                            v-if="$page.notification.list.length == 0">
                            <div class="inline-flex items-start gap-2">
                                <div class="user-img flex-shrink-0">
                                    <img src="https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png"
                                        class="rounded-full w-6 h-6 bg-clip-content">
                                </div>
                                <div class="activity-perform text-sm font-normal">
                                    No recent activity
                                </div>
                            </div>
                        </li>

                        <div class="" v-else>
                            <li class="dropdown-item inline-flex flex-col py-2.5 px-3"
                                v-for="noty in $page.notification.list" :key="noty.id" @click="updateRead(noty.id)">

                                <div class="inline-flex items-start gap-2">
                                    <div class="user-img flex-shrink-0">
                                        <img src="https://png.pngtree.com/png-vector/20220709/ourmid/pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png"
                                            class="rounded-full w-6 h-6 bg-clip-content">
                                    </div>
                                    <div class="activity-perform text-sm font-normal">
                                        {{ noty.comment }}
                                    </div>
                                </div>
                                <div class="activity-time text-xs text-gray-500 flex-end font-medium ml-auto mt-2">
                                    {{ noty.datetime }}
                                </div>
                            </li>
                        </div>
                    </ul>
                </template>
            </v-dropdown>

            <v-dropdown>
                <template v-slot:button>
                    <button class="flex relative bg-transparent sm:leading-6 focus:outline-none items-center">
                        <div class="flex items-start mr-2">
                            <div>
                                <img class="inline-block h-9 w-9 rounded-full" :src="$page.user.avatar_url"
                                    :alt="$page.user.name" />
                            </div>

                            <div class="ml-3 align-start text-left">
                                <p class="text-sm leading-5 font-medium text-gray-900">
                                    {{ $page.user.name }}
                                </p>

                                <p
                                    class="text-xs leading-4 font-medium text-gray-400 group-hover:text-gray-300 group-focus:underline transition ease-in-out duration-150">
                                    {{ $page.user.role == 1 ? $trans('labels.user-role-super-admin') : ($page.user.role ==
                                        2) ? $trans('labels.user-role-manager') : $trans('labels.user-role-team-member')
                                    }}

                                </p>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>

                    </button>
                </template>

                <template v-slot:content>
                    <div class="dropdown-menu flex flex-col px-1">

                        <inertia-link :href="route('app:projects.index')"
                            :class="[route().current('app:projects.index') ? 'text-gray-900' : 'text-gray-700']"
                            class="group mb-1 flex items-center px-6 py-2 text-sm leading-6 font-medium focus:outline-none  hover:text-gray-900 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>


                            {{ $trans('labels.profile-settings') }}
                        </inertia-link>

                        <a :href="route('logout')"
                            class="group flex items-center px-6 py-2 text-sm leading-6 font-medium text-gray-900 focus:outline-none  hover:text-gray-700 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>

                            {{ $trans('labels.logout') }}
                        </a>

                    </div>
                </template>
            </v-dropdown>
        </div>
    </div>
</template>


<script setup>
import VDropdown from '@/components/dropdown'

defineProps(['pageTitle'])
</script>
