@php
    $data = $this->getData();
    $fastMovers = $data['fastMovers'];
    $slowMovers = $data['slowMovers'];
    $days = $data['days'];
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            .pm-insights-widget {
                color: #e6e6e6;
            }

            .pm-insights-title {
                color: #9be7b8;
                margin-bottom: 1rem;
                font-size: 1.125rem;
                font-weight: 800;
            }

            .pm-grid {
                display: grid;
                grid-gap: 1.5rem;
                grid-template-columns: 1fr;
            }

            @media (min-width: 768px) {
                .pm-grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            .pm-card {
                background: rgba(20, 20, 20, 0.7);
                border: 1px solid rgba(90, 90, 90, 0.35);
                padding: 1rem;
                border-radius: 10px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.45);
            }

            .pm-header {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 0.75rem;
                font-size: 1rem;
                font-weight: 700;
            }

            .pm-header .icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
            }

            .pm-fast-title {
                color: #10b981;
            }

            .pm-slow-title {
                color: #f59e0b;
            }


            .pm-list {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .pm-item {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0.25rem;
                border-bottom: 1px solid rgba(90, 90, 90, 0.25);
                transition: background-color 0.12s ease;
                gap: 0.5rem;
            }

            .pm-item:hover {
                background: rgba(120, 120, 120, 0.06);
            }

            .pm-item .name {
                color: #ffffff;
            }

            .pm-badge {
                display: inline-block;
                min-width: 2.25rem;
                padding: 0.15rem 0.5rem;
                text-align: right;
                font-weight: 600;
                border-radius: 6px;
            }

            .pm-badge.green {
                color: #064e3b;
                background: #bbf7d0;
                font-size: 0.7rem;
            }

            .pm-badge.amber {
                color: #7c2d12;
                background: #fed7aa;
                font-size: 0.6rem;
            }

            .pm-empty {
                padding: 0.5rem;
                color: #9aa0a6;
                font-size: 0.9rem;
            }
        </style>

        <div class="pm-insights-widget">
            <h2 class="pm-insights-title">Inventory Movement Insights</h2>

            <div class="pm-grid" role="list">
                <div class="pm-card" role="region" aria-labelledby="fast-movers-title">
                    <div id="fast-movers-title" class="pm-header pm-fast-title">
                        <span class="icon" aria-hidden="true" style="display:inline-block">
                            <x-heroicon-s-fire style="width:20px;height:20px;display:block" />
                        </span>
                        <span>Top 5 Fast-Moving Products (Last {{ $days }} days)</span>
                    </div>

                    <ul class="pm-list" aria-label="Top 5 fast moving products">
                        @forelse ($fastMovers as $item)
                            <li class="pm-item" role="listitem">
                                <span class="name">{{ $item->product->name ?? 'Unknown Product' }}</span>
                                <span class="pm-badge green" title="Total items moved out">
                                    {{ number_format($item->total_out) }}
                                </span>
                            </li>
                        @empty
                            <p class="pm-empty">No product movement recorded in the last {{ $days }} days.</p>
                        @endforelse
                    </ul>
                </div>

                <div class="pm-card" role="region" aria-labelledby="slow-movers-title">
                    <div id="slow-movers-title" class="pm-header pm-slow-title">
                        <span class="icon" aria-hidden="true" style="display:inline-block">
                            <x-heroicon-s-archive-box style="width:20px;height:20px;display:block" />
                        </span>
                        <span>Top 5 Slow / Non-Moving Products</span>
                    </div>

                    <ul class="pm-list" aria-label="Top 5 slow moving products">
                        @forelse ($slowMovers as $product)
                            <li class="pm-item" role="listitem">
                                <span class="name">{{ $product->name }}</span>
                                <span class="pm-badge amber">
                                    No movement in {{ $days }} days
                                </span>
                            </li>
                        @empty
                            <p class="pm-empty">All products have had recent movement!</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
