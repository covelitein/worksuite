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
        <x-cards.data :title="__('Budget Utilization Details')" class="mt-4">
            <x-slot name="action">
                <div class="dropdown">
                    <button class="btn f-14 px-0 py-0 text-dark-grey dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                        aria-labelledby="dropdownMenuLink" tabindex="0">
                        <a class="dropdown-item delete-table-row" href="javascript:;"
                            data-budget-id="{{ $budget->id }}">
                            @lang('app.delete')
                        </a>
                    </div>
                </div>
            </x-slot>

            <x-cards.data-row :label="__('Project')" :value="$budget->project_name . ' (ID: ' . $budget->project_id . ')'" />

            <x-cards.data-row :label="__('Category')" :value="$budget->category ?? '--'" />

            <x-cards.data-row :label="__('Approved Budget (USD)')"
                :value="format_currency($budget->budget_approved_usd)" />

            <x-cards.data-row :label="__('Planned Cost (USD)')" :value="format_currency($budget->planned_cost_usd)" />

            <x-cards.data-row :label="__('Actual Cost (USD)')" :value="format_currency($budget->actual_cost_usd)" />

            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                <p class="mb-0 text-lightest f-14 w-30">@lang('Variance (USD)')</p>
                <p class="mb-0 text-dark-grey f-14">
                    @php
                        $varianceClass = $budget->variance_usd < 0 ? 'text-danger' : 'text-success';
                    @endphp
                    <span class="{{ $varianceClass }}">
                        {{ format_currency($budget->variance_usd) }}
                    </span>
                </p>
            </div>

            <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                <p class="mb-0 text-lightest f-14 w-30">@lang('Remaining Budget (USD)')</p>
                <p class="mb-0 text-dark-grey f-14">
                    @php
                        $remainingClass = $budget->remaining_budget_usd < ($budget->budget_approved_usd * 0.1)
                            ? 'text-danger'
                            : ($budget->remaining_budget_usd < ($budget->budget_approved_usd * 0.3)
                                ? 'text-warning'
                                : 'text-success');
                    @endphp
                    <span class="{{ $remainingClass }}">
                        {{ format_currency($budget->remaining_budget_usd) }}
                    </span>
                </p>
            </div>

            <x-cards.data-row :label="__('Comments')" :value="$budget->comments ?? '--'" html="true" />

            <x-cards.data-row :label="__('Last Updated')" :value="$budget->updated_at->format(company()->date_format . ' H:i')" />

        </x-cards.data>
    </div>
</div>

<script>
    $('body').on('click', '.delete-table-row', function () {
        var id = $(this).data('budget-id');
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
                var url = "{{ route('budgetutilsmodule.destroy', ':id') }}";
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
                            window.location.href = "{{ route('budgetutilsmodule.index') }}";
                        }
                    }
                });
            }
        });
    });
</script>