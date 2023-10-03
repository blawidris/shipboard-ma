<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <inertia-link :href="route('app:dashboard')"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    Home
                </inertia-link>
            </li>
            <slot></slot>
        </ol>
    </nav>
</template>

<script>
export default {
    props: {
        text: String
    }
}
</script>

<style lang="scss" scoped></style>
