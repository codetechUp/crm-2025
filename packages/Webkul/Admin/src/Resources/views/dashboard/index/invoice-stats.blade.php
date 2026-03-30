{!! view_render_event('admin.dashboard.index.invoice_stats.before') !!}

<!-- Invoice Stats Vue Component -->
<v-dashboard-invoice-stats>
    <!-- Shimmer -->
    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex gap-4 max-md:flex-wrap">
            <div class="flex gap-2 max-md:flex-wrap md:flex-col">
                <div class="light-shimmer-bg dark:shimmer h-[90px] w-[140px] rounded-lg"></div>
                <div class="light-shimmer-bg dark:shimmer h-[90px] w-[140px] rounded-lg"></div>
            </div>
            <div class="flex w-full flex-col gap-4">
                <div class="light-shimmer-bg dark:shimmer h-[200px] w-full rounded-lg"></div>
            </div>
        </div>
    </div>
</v-dashboard-invoice-stats>

{!! view_render_event('admin.dashboard.index.invoice_stats.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-invoice-stats-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex gap-4 max-md:flex-wrap">
                    <div class="flex gap-2 max-md:flex-wrap md:flex-col">
                        <div class="light-shimmer-bg dark:shimmer h-[90px] w-[140px] rounded-lg"></div>
                        <div class="light-shimmer-bg dark:shimmer h-[90px] w-[140px] rounded-lg"></div>
                    </div>
                    <div class="flex w-full flex-col gap-4">
                        <div class="light-shimmer-bg dark:shimmer h-[200px] w-full rounded-lg"></div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Invoice Stats Section -->
        <template v-else>
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <!-- Title -->
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-base font-semibold dark:text-white">
                        Factures
                    </p>
                </div>

                <div class="flex gap-4 max-md:flex-wrap">
                    <!-- Stats Cards -->
                    <div class="flex gap-2 max-md:flex-wrap md:flex-col">
                        <!-- Total Invoices -->
                        <div class="flex flex-col gap-2 rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-800 max-sm:w-full">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                Nombre de factures
                            </p>

                            <div class="flex gap-2">
                                <p class="text-xl font-bold dark:text-gray-300">
                                    @{{ report.statistics.total_invoices.current }}
                                </p>

                                <div class="flex items-center gap-0.5">
                                    <span
                                        class="text-base !font-semibold"
                                        :class="[report.statistics.total_invoices.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                                    ></span>

                                    <p
                                        class="text-xs font-semibold"
                                        :class="[report.statistics.total_invoices.progress < 0 ? 'text-red-500' : 'text-green-500']"
                                    >
                                        @{{ Math.abs(report.statistics.total_invoices.progress.toFixed(2)) }}%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="flex flex-col gap-2 rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-800 max-sm:w-full">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                Montant total
                            </p>

                            <div class="flex gap-2">
                                <p class="text-xl font-bold text-blue-600">
                                    @{{ report.statistics.total_amount.formatted_total }}
                                </p>

                                <div class="flex items-center gap-0.5">
                                    <span
                                        class="text-base !font-semibold"
                                        :class="[report.statistics.total_amount.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                                    ></span>

                                    <p
                                        class="text-xs font-semibold"
                                        :class="[report.statistics.total_amount.progress < 0 ? 'text-red-500' : 'text-green-500']"
                                    >
                                        @{{ Math.abs(report.statistics.total_amount.progress.toFixed(2)) }}%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Acomptes -->
                        <div class="flex flex-col gap-2 rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-800 max-sm:w-full">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                Acomptes reçus
                            </p>

                            <div class="flex gap-2">
                                <p class="text-xl font-bold text-green-600">
                                    @{{ report.statistics.total_acomptes.formatted_total }}
                                </p>

                                <div class="flex items-center gap-0.5">
                                    <span
                                        class="text-base !font-semibold"
                                        :class="[report.statistics.total_acomptes.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                                    ></span>

                                    <p
                                        class="text-xs font-semibold"
                                        :class="[report.statistics.total_acomptes.progress < 0 ? 'text-red-500' : 'text-green-500']"
                                    >
                                        @{{ Math.abs(report.statistics.total_acomptes.progress.toFixed(2)) }}%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Restant à payer -->
                        <div class="flex flex-col gap-2 rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-800 max-sm:w-full">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                Restant à payer
                            </p>

                            <div class="flex gap-2">
                                <p class="text-xl font-bold text-orange-500">
                                    @{{ report.statistics.total_restant.formatted_total }}
                                </p>

                                <div class="flex items-center gap-0.5">
                                    <span
                                        class="text-base !font-semibold"
                                        :class="[report.statistics.total_restant.progress < 0 ? 'icon-stats-down text-red-500 dark:!text-red-500' : 'icon-stats-up text-green-500 dark:!text-green-500']"
                                    ></span>

                                    <p
                                        class="text-xs font-semibold"
                                        :class="[report.statistics.total_restant.progress < 0 ? 'text-red-500' : 'text-green-500']"
                                    >
                                        @{{ Math.abs(report.statistics.total_restant.progress.toFixed(2)) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="flex w-full max-w-full flex-col gap-4">
                        <canvas
                            :id="$.uid + '_invoice_chart'"
                            class="w-full max-w-full items-end"
                        ></canvas>

                        <div class="flex justify-center gap-5">
                            <div class="flex items-center gap-2">
                                <span class="h-3.5 w-3.5 rounded-sm bg-green-500 opacity-80"></span>

                                <p class="text-xs dark:text-gray-300">
                                    Acomptes reçus
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="h-3.5 w-3.5 rounded-sm bg-orange-500 opacity-80"></span>

                                <p class="text-xs dark:text-gray-300">
                                    Restant à payer
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-invoice-stats', {
            template: '#v-dashboard-invoice-stats-template',

            data() {
                return {
                    report: [],

                    isLoading: true,

                    chart: undefined,
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

                    filters.type = 'invoice-stats';

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", {
                            params: filters
                        })
                        .then(response => {
                            this.report = response.data;

                            this.isLoading = false;

                            setTimeout(() => {
                                this.prepare();
                            }, 0);
                        })
                        .catch(error => {});
                },

                prepare() {
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(document.getElementById(this.$.uid + '_invoice_chart'), {
                        type: 'bar',

                        data: {
                            labels: ['Acomptes reçus', 'Restant à payer'],

                            datasets: [{
                                axis: 'y',
                                data: [
                                    this.report.statistics.total_acomptes.current,
                                    this.report.statistics.total_restant.current
                                ],

                                backgroundColor: [
                                    'rgba(34, 197, 94, 0.8)',
                                    'rgba(249, 115, 22, 0.8)',
                                ],

                                barPercentage: 0.8,
                                categoryPercentage: 0.7,
                            }],
                        },

                        options: {
                            aspectRatio: 3,

                            indexAxis: 'y',

                            plugins: {
                                legend: {
                                    display: false,
                                },
                            },

                            scales: {
                                x: {
                                    beginAtZero: true,

                                    border: {
                                        dash: [8, 4],
                                    }
                                },

                                y: {
                                    beginAtZero: true,

                                    ticks: {
                                        display: false,
                                    },

                                    border: {
                                        dash: [8, 4],
                                    }
                                }
                            },

                            maintainAspectRatio: true,

                            responsive: true,

                            layout: {
                                padding: {
                                    left: 0,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce
