<div class="pos-shell" wire:poll.20s="reload">
    <section class="pos-card">
        <div class="pos-card-head">
            <h1 class="pos-title">POS Ermis</h1>
            <div class="pos-head-actions">
                <label for="reportDate">Ngay bao cao</label>
                <input id="reportDate" type="date" wire:model.live="reportDate" class="pos-input">
            </div>
        </div>

        <div class="pos-grid">
            <article class="pos-panel">
                <h3>Thong ke theo ngay</h3>
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Loai</th>
                            <th>So phieu</th>
                            <th>Tong tien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary as $row)
                            <tr>
                                <td>{{ $row['type'] }}</td>
                                <td>{{ $row['total_docs'] }}</td>
                                <td>{{ number_format((float) $row['total_amount'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Khong co du lieu trong ngay.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>

            <article class="pos-panel">
                <h3>Ton kho gan nhat</h3>
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Kho</th>
                            <th>San pham</th>
                            <th>So luong</th>
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
                                <td colspan="3">Chua co ton kho.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>
        </div>
    </section>

    <section class="pos-card">
        <div class="pos-tabs">
            <button class="pos-tab active" data-pos-tab="sale">Ban hang</button>
            <button class="pos-tab" data-pos-tab="return">Tra hang</button>
            <button class="pos-tab" data-pos-tab="stock_in">Nhap kho</button>
            <button class="pos-tab" data-pos-tab="stock_out">Xuat kho</button>
            <button class="pos-tab" data-pos-tab="stock_transfer">Chuyen kho</button>
            <button class="pos-tab" data-pos-tab="cash">Thu chi tai quay</button>
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
                        <label>Ngay hach toan</label>
                        <input type="date" name="transaction_date" class="pos-input" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label>Loai</label>
                        <select name="cash_type" class="pos-input">
                            <option value="cash_receipt">Thu tien</option>
                            <option value="cash_payment">Chi tien</option>
                        </select>
                    </div>
                    <div>
                        <label>So tien</label>
                        <input type="number" name="total_amount" class="pos-input" step="0.01" min="0" required>
                    </div>
                    <div class="pos-col-2">
                        <label>Ghi chu</label>
                        <input type="text" name="note" class="pos-input">
                    </div>
                    <div class="pos-col-2">
                        <button type="submit" class="pos-btn">Luu thu/chi</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="pos-card">
        <h3>Chung tu gan nhat</h3>
        <table class="pos-table">
            <thead>
                <tr>
                    <th>So phieu</th>
                    <th>Loai</th>
                    <th>Kho</th>
                    <th>Kho nhan</th>
                    <th>Tong tien</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recent as $tx)
                    <tr>
                        <td>{{ $tx['code'] }}</td>
                        <td>{{ $tx['type'] }}</td>
                        <td>{{ data_get($tx, 'warehouse.name', '-') }}</td>
                        <td>{{ data_get($tx, 'warehouse_to.name', '-') }}</td>
                        <td>{{ number_format((float) $tx['total_amount'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Chua co chung tu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>

