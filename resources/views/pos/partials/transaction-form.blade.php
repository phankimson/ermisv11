@csrf
<div class="pos-form-grid">
    <div>
        <label>Ngay hach toan</label>
        <input type="date" name="transaction_date" class="pos-input" value="{{ now()->toDateString() }}">
    </div>
    <div>
        <label>Kho xu ly</label>
        <select name="warehouse_id" class="pos-input" required>
            <option value="">Chon kho</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse['id'] }}">{{ $warehouse['name'] }} ({{ $warehouse['code'] }})</option>
            @endforeach
        </select>
    </div>
    @if($hasWarehouseTo ?? false)
        <div>
            <label>Kho nhan</label>
            <select name="warehouse_to_id" class="pos-input" required>
                <option value="">Chon kho nhan</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse['id'] }}">{{ $warehouse['name'] }} ({{ $warehouse['code'] }})</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="pos-col-2">
        <label>San pham</label>
        <div class="pos-items" data-pos-items>
            <div class="pos-item-row">
                <select class="pos-input" data-field="product_id">
                    <option value="">Chon san pham</option>
                    @foreach ($products as $product)
                        <option value="{{ $product['id'] }}" data-price="{{ $product['sale_price'] }}">
                            {{ $product['name'] }} ({{ $product['sku'] }})
                        </option>
                    @endforeach
                </select>
                <input type="number" data-field="quantity" class="pos-input" min="0.001" step="0.001" placeholder="So luong">
                <input type="number" data-field="unit_price" class="pos-input" min="0" step="0.01" placeholder="Don gia">
                <button type="button" class="pos-btn-light" data-action="remove-item">Xoa</button>
            </div>
        </div>
        <button type="button" class="pos-btn-light" data-action="add-item">Them dong</button>
    </div>
    <div class="pos-col-2">
        <label>Ghi chu</label>
        <input type="text" name="note" class="pos-input">
    </div>
    <div class="pos-col-2">
        <button type="submit" class="pos-btn">Luu chung tu</button>
    </div>
</div>

