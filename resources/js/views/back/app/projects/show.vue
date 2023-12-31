<template>
    <v-app-default-layout class="">
        <template v-slot:header>
            <h1 class="text-2xl font-semibold text-gray-900">{{ project.name }}</h1>

            <div class="ml-auto">
                <v-dropdown>
                    <template v-slot:button>
                        <button
                            class="flex items-center p-1 text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4z">
                                </path>
                            </svg>
                        </button>
                    </template>

                    <template v-slot:content>
                        <div class="w-48 rounded-md bg-white shadow-xs">
                            <div class="py-1">
                                <a @click.prevent="showEditProjectModal()" href="#"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    v-if="$page.can.update_project && $page.user.role !== 3">
                                    {{ $trans('labels.edit') }}
                                </a>

                                <inertia-link :href="route('app:favorite-projects.destroy', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="delete" v-if="project.is_favorite">
                                    {{ $trans('labels.remove-favorite') }}
                                </inertia-link>

                                <inertia-link :data="{ project: project.uuid }" :href="route('app:favorite-projects.store')"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="post" v-else-if="project.status !== 'archived'">
                                    {{ $trans('labels.add-favorite') }}
                                </inertia-link>

                                <inertia-link :href="route('app:projects.complete.destroy', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="delete" v-if="project.is_completed && project.status !== 'archived'">
                                    {{ $trans('labels.mark-as-incomplete') }}
                                </inertia-link>

                                <inertia-link :href="route('app:projects.complete.store', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="post"
                                    v-else-if="!project.is_completed && project.status !== 'archived' && $page.user.role !== 3">
                                    {{ $trans('labels.mark-as-completed') }}
                                </inertia-link>

                                <inertia-link :href="route('app:projects.complete.approve', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5     text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="post"
                                    v-if="project.is_completed && project.is_approved != '1' && $page.user.role !== 3">
                                    {{ $trans('labels.approve') }}
                                </inertia-link>

                                <inertia-link :href="route('app:projects.complete.unapprove', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="post" v-else-if="project.is_completed && project.is_approved == '1'">
                                    {{ $trans('labels.unapprove') }}
                                </inertia-link>

                                <inertia-link :href="route('app:project.watchers.destroy', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="delete" v-if="project.is_watched">
                                    {{ $trans('labels.stop-watching') }}
                                </inertia-link>

                                <inertia-link :href="route('app:project.watchers.store', { project: project.uuid })"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    method="post" v-else>
                                    {{ $trans('labels.watch') }}
                                </inertia-link>

                                <a @click.prevent="showDeleteProjectModal()" href="#"
                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                    v-if="$page.can.delete_project || $page.can.archive_project">
                                    <span v-if="project.status === 'archived'">{{ $trans('labels.delete') }}</span>
                                    <span v-else>{{ $trans('labels.archive') }}</span>
                                </a>

                                <a @click.prevent="showRestoreProjectModal()"
                                    class="flex items-center px-4 py-2 text-sm leading-tight hover:bg-gray-200" href="#"
                                    v-if="($page.can.delete_project || $page.can.archive_project) && project.is_archived">
                                    {{ $trans('labels.restore') }}
                                </a>
                            </div>
                        </div>
                    </template>
                </v-dropdown>
            </div>
        </template>

        <div class="px-6" v-if="$page.flash">
            <v-alert dismissible>{{ $page.flash }}</v-alert>
        </div>

        <div class="px-6" v-if="$page.errors.has('plan.limit')">
            <v-alert variant="warning" dismissible>
                {{ $page.errors.first('plan.limit') }}
            </v-alert>
        </div>

        <template>
            <div class="mt-5 grid grid-cols-2 gap-2 sm:grid-cols-2 lg:grid-cols-5 mb-8 mx-auto container px-4 lg:pl-8">

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-3 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md text-white p-2">
                                <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 1l10 6-10 6L0 7l10-6zm6.67 10L20 13l-10 6-10-6 3.33-2L10 15l6.67-4z">
                                    </path>
                                </svg>
                            </div>

                            <div class="ml-2 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs leading-5 font-medium text-gray-500 truncate">
                                        {{ $trans('headings.total-tasks') }}
                                    </dt>

                                    <dd class="flex items-baseline">
                                        <div class="text-2xl leading-8 font-semibold text-gray-900">
                                            {{ project.stats.total_tasks }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-3 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 text-white rounded-md p-2">
                                <svg class="h-3 w-3 fill-current" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512">
                                    <path fill="currentColor"
                                        d="M48 48a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm448 16H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z">
                                    </path>
                                </svg>
                            </div>

                            <div class="ml-2 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs leading-5 font-medium text-gray-500 truncate">
                                        {{ $trans('headings.open-tasks') }}
                                    </dt>

                                    <dd class="flex items-baseline">
                                        <div class="text-2xl leading-8 font-semibold text-gray-900">
                                            {{ project.stats.open_tasks }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-3 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 text-white rounded-md p-2">
                                <svg class="h-3 w-3 fill-current" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512">
                                    <path fill="currentColor"
                                        d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z">
                                    </path>
                                </svg>
                            </div>

                            <div class="ml-2 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs leading-5 font-medium text-gray-500 truncate">
                                        {{ $trans('headings.completed-tasks') }}
                                    </dt>

                                    <dd class="flex items-baseline">
                                        <div class="text-2xl leading-8 font-semibold text-gray-900">
                                            {{ project.stats.completed_tasks }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-3 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 text-white rounded-md p-2">
                                <svg class="h-3 w-3 text-white" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512">
                                    <path fill="currentColor"
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                            </div>

                            <div class="ml-2 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs leading-5 font-medium text-gray-500 truncate">
                                        {{ $trans('headings.total-subtasks') }}
                                    </dt>

                                    <dd class="flex items-baseline">
                                        <div class="text-2xl leading-8 font-semibold text-gray-900">
                                            {{ project.stats.total_subtasks }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-3 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 text-white rounded-md p-2">
                                <svg class="h-3 w-3 text-white" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512">
                                    <path fill="currentColor"
                                        d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z">
                                    </path>
                                </svg>
                            </div>

                            <div class="ml-2 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs leading-5 font-medium text-gray-500 truncate">
                                        {{ $trans('headings.completed-subtasks') }}
                                    </dt>

                                    <dd class="flex items-baseline">
                                        <div class="text-2xl leading-8 font-semibold text-gray-900">
                                            {{ project.stats.completed_subtasks }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <v-draggable class="flex flex-1 max-h-screen h-full overflow-x-auto px-6 pb-3 mx-2"
                ghost-class="draggable-ghost" group="columns" handle=".column" v-model="project.columns"
                v-bind="{ fallbackTolerance: 7 }" @end="onColumnSortChange">
                <v-card-project-column :key="column.uuid" :column="column" @task-click="showTaskModal"
                    @tasks-sort-change="onTasksSortChange" @column-edit="showEditColumnModal"
                    @column-delete="showDeleteColumnModal" v-for="column in $page.project.columns"  />

                <div class="max-w-xs w-full flex-shrink-0 rounded-lg px-2">
                    <div class="flex flex-col h-full rounded-lg">
                        <a @click.prevent="showAddColumnModal()" href="#" v-if="$page.user.role !== 3"
                            class="flex items-center justify-center text-sm rounded-lg border border-dashed p-6 text-gray-600 hover:text-gray-700 hover:border-gray-400 focus:outline-none">
                            <div class="w-4 h-4 overflow-hidden mr-3">
                                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                    class="w-full h-full fill-current">
                                    <path
                                        d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z">
                                    </path>
                                </svg>
                            </div>

                            {{ $trans('labels.add-column') }}
                        </a>
                    </div>
                </div>
            </v-draggable>
        </template>
    </v-app-default-layout>
</template>

<script>
import VDraggable from 'vuedraggable'
import VAppDefaultLayout from '@/views/back/app/layouts/default'
import VCardProjectColumn from '@/views/back/app/projects/card-project-column'
import VTaskModal from '@/views/back/app/tasks/modal-task'
import VEditColumnModal from '@/views/back/app/projects/modal-column-edit'
import VAddColumnModal from '@/views/back/app/projects/modal-column-add'
import VEditProjectModal from '@/views/back/app/projects/modal-project-edit'
import VDropdown from '@/components/dropdown'
import VAlert from '@/components/alert'

export default {
    metaInfo() {
        return {
            title: this.project.name
        }
    },

    components: {
        VDraggable,
        VAppDefaultLayout,
        VCardProjectColumn,
        VDropdown,
        VAlert
    },

    props: {
        project: {
            type: Object,
            required: true
        }
    },

    methods: {
        showTaskModal(task) {
            this.$modal(VTaskModal, {
                users: this.$page.users,
                projectUuid: this.project.uuid,
                taskUuid: task.uuid,
                userUuid: task.user.uuid,
                user_role: this.$page.user.role,
                content: task.content,
                isCompleted: task.is_completed,
                isApproved: task.is_approved,
                dueDate: task.due_date,
                startDate: task.start_date,
                status: task.status,
                priority: task.priority,
            });

            //  console.log()
        },

        showAddColumnModal() {
            this.$modal(VAddColumnModal);
        },

        showEditColumnModal(column) {
            this.$modal(VEditColumnModal, {
                uuid: column.uuid,
                name: column.name
            });
        },

        showDeleteColumnModal(column) {
            this.$confirm(this.$trans('messages.delete-this-column'), {
                confirmText: this.$trans('labels.delete')
            }).then((modal) => {
                this.$inertia.delete(route('app:project.columns.destroy', {
                    project: this.project.uuid,
                    column: column.uuid
                })).then(() => {
                    modal.hide();
                });
            });
        },

        showEditProjectModal() {
            this.$modal(VEditProjectModal);
        },

        showDeleteProjectModal() {
            let message;
            let confirmButtonText;

            if (this.project.status === 'archived') {
                message = this.$trans('messages.delete-this-project');
                confirmButtonText = 'Delete';
            } else {
                message = this.$trans('messages.archive-this-project');
                confirmButtonText = this.$trans('labels.archive');
            }

            this.$confirm(message, {
                confirmButtonText: confirmButtonText
            }).then((modal) => {
                this.$inertia.delete(route('app:projects.destroy', { project: this.project.uuid }))
                    .then(() => {
                        modal.hide()
                    });
            });
        },

        showRestoreProjectModal() {
            this.$confirm(this.$trans('messages.restore-this-project'), {
                confirmButtonText: this.$trans('labels.restore'),
                confirmButtonColor: 'indigo'
            }).then(modal => {
                this.$inertia.put(route('app:projects.restore', { project: this.project.uuid }))
                    .then(() => modal.hide());
            })
        },

        onColumnSortChange() {
            this.project.columns.forEach((column, index) => {
                this.project.columns[index].index = index;
            });

            this.$inertia.put(route('app:project.columns.sort.update', { project: this.project.uuid, }), {
                columns: this.project.columns.map(column => column.uuid)
            })
        },

        onTasksSortChange(e) {
            let from = e.from.dataset.column;
            let to = e.to.dataset.column;

            this.sortTasks(from);

            if (from !== to) {
                this.sortTasks(to);
            }
        },

        sortTasks(columnUuid) {

            let columnIndex = this.project.columns.findIndex(c => c.uuid === columnUuid);
            let index = this.project.columns[columnIndex].tasks.length - 1;

            this.project.columns[columnIndex].tasks.forEach((task, taskIndex) => {
                this.project.columns[columnIndex].tasks[taskIndex].index = index;
                index--;
            });

            this.$inertia.put(route('app:project.tasks.sort.update', {
                project: this.project.uuid,
                column: columnUuid
            }), {
                tasks: this.project.columns[columnIndex].tasks.map(task => task.uuid).reverse()
            });
        }
    }
}
</script>
