<template>
    <li v-if="lastItem" aria-current="page">
        <div class="flex items-center">
            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
            </svg>
            <span class="ml-1 text-sm font-medium text-gray-900 md:ml-2 dark:text-gray-400">{{ title }}</span>
        </div>
    </li>

    <li v-else>
        <div class="flex items-center">
            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 9 4-4-4-4" />
            </svg>
            <a href="#" class="ml-1 text-sm font-medium text-gray-500 hover:text-gray-900 md:ml-2">
                {{ title }}
            </a>
        </div>
    </li>
</template>

<script>
export default {
    props: {
        title: String,
        lastItem: Boolean
    }
}
</script>

<style lang="scss" scoped></style>
