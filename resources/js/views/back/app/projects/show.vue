<template>
    <v-app-default-layout :pageTitle="$trans('labels.projects')">
        <template #header>
            <!-- <h1 class="text-2xl font-semibold text-gray-900">{{ project.name }}</h1> -->
            <v-nav-header :pageTitle="$trans('labels.projects')"></v-nav-header>
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

            <!-- Breadcrumb -->
            <v-breadcrumb-layout class="my-6 px-9  container mx-auto">
                <v-breadcrumb-item :lastItem="true" :title="project.name" />
            </v-breadcrumb-layout>

            <!-- Project Header -->
            <div class="container mx-auto px-4 sm:px-6 md:px-8 xl:px-8 py-4 mb-10">
                <div class="bg-white px-5 py-8 rounded-md">
                    <div class="blog">

                        <div class="block sm:flex items-center gap-x-3">
                            <h1 class="text-base md:text-2xl text-gray-900 font-medium">{{ project.name }}</h1>
                            <div class="inline-flex">
                                <button class="inline text-center p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>

                                <button class="inline text-center p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                                    </svg>

                                </button>
                            </div>
                        </div>
                        <p class="text-gray-600">
                            {{ project.description }}
                        </p>
                    </div>
                </div>
            </div>


            <v-draggable class="flex flex-1 max-h-screen h-full overflow-x-auto px-6 pb-3 mx-2"
                ghost-class="draggable-ghost" group="columns" handle=".column" v-model="project.columns"
                v-bind="{ fallbackTolerance: 7 }" @end="onColumnSortChange">
                <v-card-project-column :key="column.uuid" :column="column" @task-click="showTaskModal"
                    @tasks-sort-change="onTasksSortChange" @column-edit="showEditColumnModal"
                    @column-delete="showDeleteColumnModal" v-for="column in $page.project.columns" />

                <div class="max-w-xs w-full flex-shrink-0 rounded-lg px-2">
                    <div class="flex flex-col h-full rounded-lg">
                        <a @click.prevent="showAddColumnModal()" href="#"
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
import VNavHeader from '@/views/back/app/layouts/nav-header';
import VBreadcrumbLayout from '@/components/breadcrumb/breadcrumb-layout'
import VBreadcrumbItem from '@/components/breadcrumb/breadcrumb-item'

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
        VAlert,
        VNavHeader,
        VBreadcrumbItem,
        VBreadcrumbLayout
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
                content: task.content,
                isCompleted: task.is_completed,
                dueDate: task.due_date,
                priority: task.priority,
            });
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
