{!! view_render_event('admin.dashboard.index.treasury_tracking.before') !!}

<!-- Treasury Tracking Vue Component -->
<v-dashboard-treasury-tracking>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.over-all />
</v-dashboard-treasury-tracking>

{!! view_render_event('admin.dashboard.index.treasury_tracking.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-treasury-tracking-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.over-all />
        </template>

        <!-- Treasury Tracking Section -->
        <template v-else>
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-4">
                    <p class="text-base font-semibold text-gray-800 dark:text-gray-300">
                        Suivi de la trésorerie en temps réel
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Today Expenses -->
                    <div class="flex flex-col gap-2 rounded-lg border border-blue-200 bg-gradient-to-br from-blue-50 to-cyan-50 px-4 py-4 dark:border-blue-800 dark:from-blue-900/20 dark:to-cyan-900/20">
                        <div class="flex items-center gap-2">
                            <span class="icon-calendar text-lg text-blue-600 dark:text-blue-400"></span>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Aujourd'hui
                            </p>
                        </div>
                        <p class="text-xl font-bold text-blue-700 dark:text-blue-300">
                            @{{ report.statistics.today.formatted }}
                        </p>
                    </div>

                    <!-- Month Expenses -->
                    <div class="flex flex-col gap-2 rounded-lg border border-purple-200 bg-gradient-to-br from-purple-50 to-pink-50 px-4 py-4 dark:border-purple-800 dark:from-purple-900/20 dark:to-pink-900/20">
                        <div class="flex items-center gap-2">
                            <span class="icon-calendar text-lg text-purple-600 dark:text-purple-400"></span>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Ce mois
                            </p>
                        </div>
                        <p class="text-xl font-bold text-purple-700 dark:text-purple-300">
                            @{{ report.statistics.month.formatted }}
                        </p>
                    </div>

                    <!-- Period Expenses -->
                    <div class="flex flex-col gap-2 rounded-lg border border-orange-200 bg-gradient-to-br from-orange-50 to-amber-50 px-4 py-4 dark:border-orange-800 dark:from-orange-900/20 dark:to-amber-900/20">
                        <div class="flex items-center gap-2">
                            <span class="icon-stats-up text-lg text-orange-600 dark:text-orange-400"></span>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Période sélectionnée
                            </p>
                        </div>
                        <p class="text-xl font-bold text-orange-700 dark:text-orange-300">
                            @{{ report.statistics.period.formatted }}
                        </p>
                    </div>

                    <!-- Average Per Day -->
                    <div class="flex flex-col gap-2 rounded-lg border border-green-200 bg-gradient-to-br from-green-50 to-emerald-50 px-4 py-4 dark:border-green-800 dark:from-green-900/20 dark:to-emerald-900/20">
                        <div class="flex items-center gap-2">
                            <span class="icon-stats-up text-lg text-green-600 dark:text-green-400"></span>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Moyenne/jour
                            </p>
                        </div>
                        <p class="text-xl font-bold text-green-700 dark:text-green-300">
                            @{{ report.statistics.average_per_day.formatted }}
                        </p>
                    </div>
                </div>

                <!-- Date Range Info -->
                <div class="mt-4 rounded-lg bg-gray-50 px-3 py-2 dark:bg-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Période:</span> @{{ report.date_range }}
                    </p>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-treasury-tracking', {
            template: '#v-dashboard-treasury-tracking-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                // Refresh every 5 minutes for real-time tracking
                setInterval(() => {
                    this.getStats({});
                }, 300000); // 5 minutes

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'treasury-tracking';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;
                        });
                },
            }
        });
    </script>
@endPushOnce
