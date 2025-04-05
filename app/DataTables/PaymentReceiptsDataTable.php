<?php

namespace App\DataTables;

use App\Models\PaymentReceipt;
use Yajra\DataTables\Html\Column;

class PaymentReceiptsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $datatables = datatables()->eloquent($query);
        $datatables->addIndexColumn();

        $datatables->addColumn('action', function ($row) {
            $action = '<div class="task_view">
                <div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">
                        <a href="' . route('paymentreceiptmodule.show', $row->id) . '" class="dropdown-item openRightModal">
                            <i class="fa fa-eye mr-2"></i>' . __('View') . '
                        </a>
                        <a href="' . route('paymentreceiptmodule.edit', $row->id) . '" class="dropdown-item">
                            <i class="fa fa-edit mr-2"></i>' . __('Edit') . '
                        </a>
                    </div>
                </div>
            </div>';

            return $action;
        });

        if (!function_exists('format_currency')) {
            function format_currency($amount, $currencySymbol = '$')
            {
                return $currencySymbol . number_format($amount, 2);
            }
        }

        // Format currency values
        $datatables->editColumn('amount_paid', function ($row) {
            return format_currency($row->amount_paid);
        });

        // Format payment date
        $datatables->editColumn('payment_date', function ($row) {
            return $row->payment_date ? $row->payment_date->format('d M Y') : '-';
        });

        // Format timestamps
        $datatables->editColumn('created_at', function ($row) {
            return $row->created_at->format('d M Y H:i');
        });

        $datatables->editColumn('updated_at', function ($row) {
            return $row->updated_at->format('d M Y H:i');
        });

        $datatables->rawColumns(['action']);

        return $datatables;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = PaymentReceipt::query()->select([
            'id',
            'receipt_num',
            'project_id',
            'project_name',
            'amount_paid',
            'payment_method',
            'payment_date',
            'paid_by',
            'received_by',
            'created_at',
            'updated_at'
        ]);

        // Apply filters from request
        if ($this->request()->has('project_id') && $this->request()->project_id != 'all') {
            $model->where('project_id', $this->request()->project_id);
        }

        if ($this->request()->has('payment_method') && $this->request()->payment_method != 'all') {
            $model->where('payment_method', $this->request()->payment_method);
        }

        if ($this->request()->has('date_range')) {
            $dates = explode(' - ', $this->request()->date_range);
            if (count($dates) == 2) {
                $model->whereBetween('payment_date', [trim($dates[0]), trim($dates[1])]);
            }
        }

        if ($this->request()->searchText != '') {
            $model->where(function ($query) {
                $query->where('receipt_num', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('project_name', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('paid_by', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('received_by', 'like', '%' . $this->request()->searchText . '%');
            });
        }

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->setBuilder('budget-allocations-table')
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["budget-allocations-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'drawCallback' => 'function() {
                    $(".dataTables_length select").select2({
                        width: "auto"
                    });
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('receipt_num')
                ->title(__('Receipt #'))
                ->width('10%'),
            Column::make('project_name')
                ->title(__('Project'))
                ->width('15%'),
            Column::make('amount_paid')
                ->title(__('Amount'))
                ->width('10%'),
            Column::make('payment_method')
                ->title(__('Method'))
                ->width('10%'),
            Column::make('payment_date')
                ->title(__('Payment Date'))
                ->width('12%'),
            Column::make('paid_by')
                ->title(__('Paid By'))
                ->width('12%'),
            Column::make('received_by')
                ->title(__('Received By'))
                ->width('12%'),
            Column::make('created_at')
                ->title(__('Created'))
                ->width('10%'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width('9%')
                ->addClass('text-center')
        ];
    }

}