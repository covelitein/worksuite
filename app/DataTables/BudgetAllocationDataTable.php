<?php

namespace App\DataTables;

use App\Models\BudgetAllocationApproval;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BudgetAllocationDataTable extends BaseDataTable
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
                        <a href="' . route('budgetallocationaprovalmodule.show', [$row->id]) . '" class="dropdown-item openRightModal">
                            <i class="fa fa-eye mr-2"></i>' . __('app.view') . '
                        </a>
                        <a href="' . route('budgetallocationaprovalmodule.edit', [$row->id]) . '" class="dropdown-item">
                            <i class="fa fa-edit mr-2"></i>' . __('app.edit') . '
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
        $datatables->editColumn('budget_requested', function ($row) {
            return format_currency($row->budget_requested);
        });

        $datatables->editColumn('budget_approved', function ($row) {
            return $row->budget_approved ? format_currency($row->budget_approved) : '-';
        });

        // Format approval status with badge
        $datatables->editColumn('approval_status', function ($row) {
            $status = $row->approval_status;
            $badgeColor = [
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'danger'
            ][$status] ?? 'secondary';

            return '<span class="badge badge-' . $badgeColor . '">' . ucfirst($status) . '</span>';
        });

        // Format date
        $datatables->editColumn('approval_date', function ($row) {
            return $row->approval_date ? $row->approval_date->format('d M Y') : '-';
        });

        $datatables->rawColumns(['action', 'approval_status']);

        return $datatables;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = BudgetAllocationApproval::query()->select([
            'id',
            'project_id',
            'project_name',
            'department',
            'budget_requested',
            'budget_approved',
            'approval_status',
            'approval_date',
            'comments'
        ]);

        // Apply filters from request
        if ($this->request()->has('department') && $this->request()->department != 'all') {
            $model->where('department', $this->request()->department);
        }

        if ($this->request()->has('status') && $this->request()->status != 'all') {
            $model->where('approval_status', $this->request()->status);
        }

        if ($this->request()->searchText != '') {
            $model->where(function ($query) {
                $query->where('project_name', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('department', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('comments', 'like', '%' . $this->request()->searchText . '%');
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
            Column::make('project_id')
                ->title('Project Id')
                ->visible(showId()),
            Column::make('project_name')
                ->title(__('Project Name'))
                ->width('15%'),
            Column::make('department')
                ->title(__('Department'))
                ->width('15%'),
            Column::make('budget_requested')
                ->title(__('Requested Budget'))
                ->width('12%'),
            Column::make('budget_approved')
                ->title(__('Approved Budget'))
                ->width('12%'),
            Column::make('approval_status')
                ->title(__('Status'))
                ->width('10%'),
            Column::make('approval_date')
                ->title(__('Approval Date'))
                ->width('12%'),
            Column::make('comments')
                ->title(__('Comments'))
                ->width('20%'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width('10%')
                ->addClass('text-center')
        ];
    }
}