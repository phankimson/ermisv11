<div class="pos-shell" wire:poll.20s="reload">
    <section class="pos-card">
        <div class="pos-card-head">
            <h1 class="pos-title">@lang('pos.brand')</h1>
            <div class="pos-head-actions">
                <label for="reportDate">@lang('pos.labels.report_date')</label>
                <input id="reportDate" type="date" wire:model.live="reportDate" class="pos-input">
            </div>
        </div>

        <div class="pos-grid">
            <article class="pos-panel">
                <h3>@lang('pos.labels.daily_summary')</h3>
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>@lang('pos.labels.type')</th>
                            <th>@lang('pos.labels.doc_count')</th>
                            <th>@lang('pos.labels.total_amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary as $row)
                            <tr>
                                <td>{{ trans('pos.types.' . $row['type']) }}</td>
                                <td>{{ $row['total_docs'] }}</td>
                                <td>{{ number_format((float) $row['total_amount'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">@lang('pos.empty.daily')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>

            <article class="pos-panel">
                <h3>@lang('pos.labels.inventory_latest')</h3>
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>@lang('pos.labels.warehouse')</th>
                            <th>@lang('pos.labels.product')</th>
                            <th>@lang('pos.labels.quantity')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $inv)
                            <tr>
                                <td>{{ data_get($inv, 'warehouse.name', '-') }}</td>
                                <td>{{ data_get($inv, 'product.name', '-') }}</td>
                                <td>{{ number_format((float) data_get($inv, 'quantity', 0), 3, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">@lang('pos.empty.inventory')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>
        </div>
    </section>

    <section class="pos-card">
        <div class="pos-tabs">
            <button class="pos-tab active" data-pos-tab="sale">@lang('pos.tabs.sale')</button>
            <button class="pos-tab" data-pos-tab="return">@lang('pos.tabs.return')</button>
            <button class="pos-tab" data-pos-tab="stock_in">@lang('pos.tabs.stock_in')</button>
            <button class="pos-tab" data-pos-tab="stock_out">@lang('pos.tabs.stock_out')</button>
            <button class="pos-tab" data-pos-tab="stock_transfer">@lang('pos.tabs.stock_transfer')</button>
            <button class="pos-tab" data-pos-tab="cash">@lang('pos.tabs.cash')</button>
        </div>

        <div class="pos-forms">
            <form class="pos-form active" data-pos-form="sale" data-endpoint="{{ route('pos.sales.store') }}">
                @include('pos.partials.transaction-form', ['warehouses' => $warehouses, 'products' => $products, 'hasWarehouseTo' => false])
            </form>

            <form class="pos-form" data-pos-form="return" data-endpoint="{{ route('pos.returns.store') }}">
                @include('pos.partials.transaction-form', ['warehouses' => $warehouses, 'products' => $products, 'hasWarehouseTo' => false])
            </form>

            <form class="pos-form" data-pos-form="stock_in" data-endpoint="{{ route('pos.stock-in.store') }}">
                @include('pos.partials.transaction-form', ['warehouses' => $warehouses, 'products' => $products, 'hasWarehouseTo' => false])
            </form>

            <form class="pos-form" data-pos-form="stock_out" data-endpoint="{{ route('pos.stock-out.store') }}">
                @include('pos.partials.transaction-form', ['warehouses' => $warehouses, 'products' => $products, 'hasWarehouseTo' => false])
            </form>

            <form class="pos-form" data-pos-form="stock_transfer" data-endpoint="{{ route('pos.stock-transfer.store') }}">
                @include('pos.partials.transaction-form', ['warehouses' => $warehouses, 'products' => $products, 'hasWarehouseTo' => true])
            </form>

            <form class="pos-form" data-pos-form="cash" data-endpoint="{{ route('pos.cash-counter.store') }}">
                @csrf
                <div class="pos-form-grid">
                    <div>
                        <label>@lang('pos.labels.transaction_date')</label>
                        <input type="date" name="transaction_date" class="pos-input" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label>@lang('pos.labels.cash_type')</label>
                        <select name="cash_type" class="pos-input">
                            <option value="cash_receipt">@lang('pos.labels.cash_receipt')</option>
                            <option value="cash_payment">@lang('pos.labels.cash_payment')</option>
                        </select>
                    </div>
                    <div>
                        <label>@lang('pos.labels.amount')</label>
                        <input type="number" name="total_amount" class="pos-input" step="0.01" min="0" required>
                    </div>
                    <div class="pos-col-2">
                        <label>@lang('pos.labels.note')</label>
                        <input type="text" name="note" class="pos-input">
                    </div>
                    <div class="pos-col-2">
                        <button type="submit" class="pos-btn">@lang('pos.buttons.save_cash')</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="pos-card">
        <h3>@lang('pos.labels.document_latest')</h3>
        <table class="pos-table">
            <thead>
                <tr>
                    <th>@lang('pos.labels.doc_no')</th>
                    <th>@lang('pos.labels.type')</th>
                    <th>@lang('pos.labels.warehouse')</th>
                    <th>@lang('pos.labels.warehouse_to')</th>
                    <th>@lang('pos.labels.total_amount')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recent as $tx)
                    <tr>
                        <td>{{ $tx['code'] }}</td>
                        <td>{{ trans('pos.types.' . $tx['type']) }}</td>
                        <td>{{ data_get($tx, 'warehouse.name', '-') }}</td>
                        <td>{{ data_get($tx, 'warehouse_to.name', '-') }}</td>
                        <td>{{ number_format((float) $tx['total_amount'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">@lang('pos.empty.documents')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
