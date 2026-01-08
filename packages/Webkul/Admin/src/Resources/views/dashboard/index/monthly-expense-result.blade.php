{!! view_render_event('admin.dashboard.index.monthly_expense_result.before') !!}

<!-- Monthly Expense Result Vue Component -->
<v-dashboard-monthly-expense-result>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.over-all />
</v-dashboard-monthly-expense-result>

{!! view_render_event('admin.dashboard.index.monthly_expense_result.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-monthly-expense-result-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.over-all />
        </template>

        <!-- Monthly Expense Result Section -->
        <template v-else>
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="mb-4">
                    <p class="text-base font-semibold text-gray-800 dark:text-gray-300">
                        Résultat mensuel des dépenses
                    </p>
                </div>

                <div class="flex flex-col gap-4">
                    <!-- Total Monthly Expense Card -->
                    <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-gradient-to-br from-red-50 to-orange-50 px-4 py-5 dark:border-gray-700 dark:from-red-900/20 dark:to-orange-900/20">
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                            Total dépenses ce mois
                        </p>

                        <div class="flex items-center justify-between gap-2">
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                @{{ report.statistics.formatted_total }}
                            </p>

                            <div class="flex items-center gap-0.5">
                                <span
                                    class="text-base !font-semibold"
                                    :class="[report.statistics.progress < 0 ? 'icon-stats-down text-green-500 dark:!text-green-400' : 'icon-stats-up text-red-500 dark:!text-red-400']"
                                ></span>

                                <p
                                    class="text-xs font-semibold"
                                    :class="[report.statistics.progress < 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400']"
                                >
                                    @{{ Math.abs(report.statistics.progress.toFixed(2)) }}%
                                </p>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <span v-if="report.statistics.progress < 0" class="text-green-600 dark:text-green-400">
                                ↓ Baisse par rapport au mois précédent
                            </span>
                            <span v-else class="text-red-600 dark:text-red-400">
                                ↑ Hausse par rapport au mois précédent
                            </span>
                        </p>
                    </div>

                    <!-- Comparison Cards -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1 rounded-lg border border-gray-200 bg-white px-3 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                Mois précédent
                            </p>
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                                @{{ formatPrice(report.statistics.previous) }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 rounded-lg border border-gray-200 bg-white px-3 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                Mois actuel
                            </p>
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                                @{{ formatPrice(report.statistics.current) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-monthly-expense-result', {
            template: '#v-dashboard-monthly-expense-result-template',

            data() {
                return {
                    report: [],

                    isLoading: true,
                }
            },

            mounted() {
                this.getStats({});

                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;

                    var filters = Object.assign({}, filters);

                    filters.type = 'monthly-expense-result';

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

                formatPrice(amount) {
                    if (typeof amount !== 'number') {
                        return '0';
                    }
                    
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount).replace('XOF', 'CFA');
                },
            }
        });
    </script>
@endPushOnce
