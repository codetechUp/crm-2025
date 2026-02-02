{!! view_render_event('admin.dashboard.index.sales_performance.before') !!}

<!-- Sales Performance Vue Component -->
<v-dashboard-sales-performance>
    <!-- Shimmer -->
    <x-admin::shimmer.dashboard.index.over-all />
</v-dashboard-sales-performance>

{!! view_render_event('admin.dashboard.index.sales_performance.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-sales-performance-template"
    >
        <!-- Shimmer -->
        <template v-if="isLoading">
            <x-admin::shimmer.dashboard.index.over-all />
        </template>

        <!-- Sales Performance Section -->
        <template v-else>
            <div class="grid gap-4 rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col justify-between gap-1">
                    <p class="text-base font-semibold dark:text-gray-300">
                        Suivi de la performance des commerciaux
                    </p>
                </div>

                <!-- Performance Chart -->
                <div class="flex w-full max-w-full flex-col gap-4" v-if="report.statistics && report.statistics.length">
                    <x-admin::charts.bar
                        ::labels="chartLabels"
                        ::datasets="chartDatasets"
                    />

                    <div class="flex justify-center gap-5">
                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#8979FF]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                Leads totaux
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#63CFE5]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                Leads gagnés
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#FFA8A1]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                Leads perdus
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="h-3.5 w-3.5 rounded-sm bg-[#43AF52]"></span>
                            
                            <p class="text-xs dark:text-gray-300">
                                CA généré
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    class="flex flex-col gap-8 p-4"
                    v-else
                >
                    <div class="grid justify-center justify-items-center gap-3.5 py-2.5">
                        <img
                            src="{{ vite()->asset('images/empty-placeholders/default.svg') }}"
                            class="dark:mix-blend-exclusion dark:invert"
                        >

                        <div class="flex flex-col items-center">
                            <p class="text-base font-semibold text-gray-400">
                                Aucune donnée disponible
                            </p>

                            <p class="text-gray-400">
                                Aucune donnée de performance pour la période sélectionnée
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-sales-performance', {
            template: '#v-dashboard-sales-performance-template',

            data() {
                return {
                    report: {
                        statistics: []
                    },

                    isLoading: true,
                }
            },

            computed: {
                chartLabels() {
                    return this.report.statistics.map(({ sales_person }) => sales_person || 'Non assigné');
                },

                chartDatasets() {
                    return [
                        {
                            label: 'Leads totaux',
                            data: this.report.statistics.map(({ total_leads }) => total_leads || 0),
                            barThickness: 24,
                            backgroundColor: '#8979FF',
                        },
                        {
                            label: 'Leads gagnés',
                            data: this.report.statistics.map(({ won_leads }) => won_leads || 0),
                            barThickness: 24,
                            backgroundColor: '#63CFE5',
                        },
                        {
                            label: 'Leads perdus',
                            data: this.report.statistics.map(({ lost_leads }) => lost_leads || 0),
                            barThickness: 24,
                            backgroundColor: '#FFA8A1',
                        },
                        {
                            label: 'CA généré',
                            data: this.report.statistics.map(({ revenue }) => revenue || 0),
                            barThickness: 24,
                            backgroundColor: '#43AF52',
                        }
                    ];
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

                    filters.type = 'sales-performance';

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
                }
            }
        });
    </script>
@endPushOnce
