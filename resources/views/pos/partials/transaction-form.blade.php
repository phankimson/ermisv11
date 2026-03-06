@csrf
<div class="pos-form-grid">
    <div>
        <label>@lang('pos.labels.transaction_date')</label>
        <input type="date" name="transaction_date" class="pos-input" value="{{ now()->toDateString() }}">
    </div>
    <div>
        <label>@lang('pos.labels.warehouse_process')</label>
        <select name="warehouse_id" class="pos-input" required>
            <option value="">@lang('pos.labels.choose_warehouse')</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse['id'] }}">{{ $warehouse['name'] }} ({{ $warehouse['code'] }})</option>
            @endforeach
        </select>
    </div>
    @if($hasWarehouseTo ?? false)
        <div>
            <label>@lang('pos.labels.warehouse_to')</label>
            <select name="warehouse_to_id" class="pos-input" required>
                <option value="">@lang('pos.labels.choose_warehouse_to')</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse['id'] }}">{{ $warehouse['name'] }} ({{ $warehouse['code'] }})</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="pos-col-2">
        <label>@lang('pos.labels.product')</label>
        <div class="pos-items" data-pos-items>
            <div class="pos-item-row">
                <select class="pos-input" data-field="product_id">
                    <option value="">@lang('pos.labels.choose_product')</option>
                    @foreach ($products as $product)
                        <option value="{{ $product['id'] }}" data-price="{{ $product['sale_price'] }}">
                            {{ $product['name'] }} ({{ $product['sku'] }})
                        </option>
                    @endforeach
                </select>
                <input type="number" data-field="quantity" class="pos-input" min="0.001" step="0.001" placeholder="@lang('pos.labels.quantity')">
                <input type="number" data-field="unit_price" class="pos-input" min="0" step="0.01" placeholder="@lang('pos.labels.unit_price')">
                <button type="button" class="pos-btn-light" data-action="remove-item">@lang('pos.buttons.remove_item')</button>
            </div>
        </div>
        <button type="button" class="pos-btn-light" data-action="add-item">@lang('pos.buttons.add_item')</button>
    </div>
    <div class="pos-col-2">
        <label>@lang('pos.labels.note')</label>
        <input type="text" name="note" class="pos-input">
    </div>
    <div class="pos-col-2">
        <button type="submit" class="pos-btn">@lang('pos.buttons.save_doc')</button>
    </div>
</div>
