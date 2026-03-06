(function ($) {
    function parseItems($form) {
        var items = [];
        $form.find('[data-pos-items] .pos-item-row').each(function () {
            var $row = $(this);
            var productId = $row.find('[data-field="product_id"]').val();
            var quantity = parseFloat($row.find('[data-field="quantity"]').val() || 0);
            var unitPrice = parseFloat($row.find('[data-field="unit_price"]').val() || 0);

            if (!productId || quantity <= 0) {
                return;
            }

            items.push({
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice
            });
        });

        return items;
    }

    function toggleTab(tab) {
        $('.pos-tab').removeClass('active');
        $('.pos-tab[data-pos-tab="' + tab + '"]').addClass('active');
        $('.pos-form').removeClass('active');
        $('.pos-form[data-pos-form="' + tab + '"]').addClass('active');
    }

    $(document).on('click', '.pos-tab', function () {
        toggleTab($(this).data('pos-tab'));
    });

    $(document).on('click', '[data-action="add-item"]', function () {
        var $container = $(this).closest('form').find('[data-pos-items]');
        var $first = $container.find('.pos-item-row').first().clone();
        $first.find('input').val('');
        $first.find('select').val('');
        $container.append($first);
    });

    $(document).on('click', '[data-action="remove-item"]', function () {
        var $rows = $(this).closest('[data-pos-items]').find('.pos-item-row');
        if ($rows.length <= 1) {
            $(this).closest('.pos-item-row').find('input,select').val('');
            return;
        }
        $(this).closest('.pos-item-row').remove();
    });

    $(document).on('change', '[data-field="product_id"]', function () {
        var price = $(this).find('option:selected').data('price');
        if (price !== undefined) {
            $(this).closest('.pos-item-row').find('[data-field="unit_price"]').val(price);
        }
    });

    $(document).on('submit', '.pos-form', function (e) {
        e.preventDefault();

        var $form = $(this);
        var endpoint = $form.data('endpoint');
        var payload = {
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.each($form.serializeArray(), function (_, field) {
            payload[field.name] = field.value;
        });

        if ($form.data('pos-form') !== 'cash') {
            payload.items = JSON.stringify(parseItems($form));
        }

        $.ajax({
            url: endpoint,
            method: 'POST',
            data: payload
        }).done(function (response) {
            alert(response.message || 'Da luu thanh cong.');
            $form.find('input[type="text"], input[type="number"]').val('');
        }).fail(function (xhr) {
            var message = 'Khong the luu du lieu.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        });
    });

    toggleTab('sale');
})(jQuery);
