<?php

namespace App\DataTables;

use App\Models\TaskAssignment;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TasksAssignmentDataTable extends BaseDataTable
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
                        <a href="' . route('tasksassignmentmodule.show', [$row->id]) . '" class="dropdown-item openRightModal">
                            <i class="fa fa-eye mr-2"></i>' . __('app.view') . '
                        </a>
                        <a href="' . route('tasksassignmentmodule.edit', [$row->id]) . '" class="dropdown-item">
                            <i class="fa fa-edit mr-2"></i>' . __('app.edit') . '
                        </a>
                    </div>
                </div>
            </div>';

            return $action;
        });

        // Format date
        $datatables->editColumn('date', function ($row) {
            return $row->date ? $row->date->format('d M Y') : '-';
        });

        // Format priority with badge
        $datatables->editColumn('priority', function ($row) {
            $priority = $row->priority;
            $badgeColor = [
                'High' => 'danger',
                'Medium' => 'warning',
                'Low' => 'primary'
            ][$priority] ?? 'secondary';

            return '<span class="badge badge-' . $badgeColor . '">' . $priority . '</span>';
        });

        // Format status with badge
        $datatables->editColumn('status', function ($row) {
            $status = $row->status;
            $badgeColor = [
                'Not Yet Started' => 'secondary',
                'In Progress' => 'info',
                'Completed' => 'success'
            ][$status] ?? 'secondary';

            return '<span class="badge badge-' . $badgeColor . '">' . $status . '</span>';
        });

        $datatables->rawColumns(['action', 'priority', 'status']);

        return $datatables;
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $model = TaskAssignment::query()->select([
            'id',
            'date',
            'task',
            'assign_to',
            'product',
            'priority',
            'status',
            'eta'
        ]);

        // Apply filters from request
        if ($this->request()->has('product') && $this->request()->product != 'all') {
            $model->where('product', $this->request()->product);
        }

        if ($this->request()->has('status') && $this->request()->status != 'all') {
            $model->where('status', $this->request()->status);
        }

        if ($this->request()->has('priority') && $this->request()->priority != 'all') {
            $model->where('priority', $this->request()->priority);
        }

        if ($this->request()->searchText != '') {
            $model->where(function ($query) {
                $query->where('task', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('assign_to', 'like', '%' . $this->request()->searchText . '%')
                    ->orWhere('product', 'like', '%' . $this->request()->searchText . '%');
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
        return $this->setBuilder('tasks-assignment-table')
            ->parameters([
                'initComplete' => 'function () {
                        window.LaravelDataTables["tasks-assignment-table"].buttons().container()
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
            Column::make('date')
                ->title(__('Date'))
                ->width('10%'),
            Column::make('task')
                ->title(__('Task'))
                ->width('25%'),
            Column::make('assign_to')
                ->title(__('Assigned To'))
                ->width('12%'),
            Column::make('product')
                ->title(__('Product'))
                ->width('12%'),
            Column::make('priority')
                ->title(__('Priority'))
                ->width('10%'),
            Column::make('status')
                ->title(__('Status'))
                ->width('12%'),
            Column::make('eta')
                ->title(__('ETA'))
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