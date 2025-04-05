@php
    if (!function_exists('format_currency')) {
        function format_currency($amount)
        {
            return number_format($amount, 2);
        }
    }
@endphp
<div class="row">
    <div class="col-sm-12">
        <x-cards.data :title="__('Payment Receipt Details')" class="mt-4">
            <x-slot name="action">
                <div class="dropdown">
                    <button class="btn f-14 px-0 py-0 text-dark-grey dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                        aria-labelledby="dropdownMenuLink" tabindex="0">
                        <a class="dropdown-item openRightModal"
                            href="{{ route('paymentreceiptmodule.edit', [$receipt->id]) }}">
                            @lang('app.edit')
                        </a>
                        <a class="dropdown-item delete-table-row" href="javascript:;"
                            data-receipt-id="{{ $receipt->id }}">
                            @lang('app.delete')
                        </a>
                    </div>
                </div>
            </x-slot>


            <x-cards.data-row :label="__('Receipt Number')" :value="$receipt->receipt_num" />

            <x-cards.data-row :label="__('Project')" :value="$receipt->project_name . ' (ID: ' . $receipt->project_id . ')'" />

            <x-cards.data-row :label="__('Amount Paid')" :value="format_currency($receipt->amount_paid)" />

            <x-cards.data-row :label="__('Payment Method')" :value="$receipt->payment_method" />

            <x-cards.data-row :label="__('Payment Date')" :value="$receipt->payment_date ? $receipt->payment_date->format(company()->date_format) : '--'" />

            <x-cards.data-row :label="__('Paid By')" :value="$receipt->paid_by" />

            <x-cards.data-row :label="__('Received By')" :value="$receipt->received_by" />

            <x-cards.data-row :label="__('Created At')" :value="$receipt->created_at->format(company()->date_format . ' H:i')" />

            <x-cards.data-row :label="__('Updated At')" :value="$receipt->updated_at->format(company()->date_format . ' H:i')" />

        </x-cards.data>
    </div>
</div>

<script>
    $('body').on('click', '.delete-table-row', function () {
        var id = $(this).data('receipt-id');
        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('app.cancel')",
            customClass: {
                confirmButton: 'btn btn-primary mr-3',
                cancelButton: 'btn btn-secondary'
            },
            showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('paymentreceiptmodule.destroy', ':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': token,
                        '_method': 'DELETE'
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            window.location.href = "{{ route('paymentreceiptmodule.index') }}";
                        }
                    }
                });
            }
        });
    });
</script>