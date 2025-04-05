<?php

namespace App\DataTables;

use App\Models\BudgetUtils;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class BudgetUtilsDataTable extends BaseDataTable
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
                        <a href="' . route('budgetutilsmodule.show', [$row->id]) . '" class="dropdown-item openRightModal">
                            <i class="fa fa-eye mr-2"></i>' . __('app.view') . '
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

        $datatables->editColumn('budget_approved_usd', function ($row) {
            return format_currency($row->budget_approved_usd);
        });

        $datatables->editColumn('planned_cost_usd', function ($row) {
            return format_currency($row->planned_cost_usd);
        });

        $datatables->editColumn('actual_cost_usd', function ($row) {
            return format_currency($row->actual_cost_usd);
        });

        $datatables->editColumn('variance_usd', function ($row) {
            return format_currency($row->variance_usd);
        });

        $datatables->editColumn('remaining_budget_usd', function ($row) {
            return format_currency($row->remaining_budget_usd);
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
        $request = $this->request();

        $model = BudgetUtils::query()->select(
            'budget_utils.id',
            'budget_utils.project_id',
            'budget_utils.project_name',
            'budget_utils.budget_approved_usd',
            'budget_utils.category',
            'budget_utils.planned_cost_usd',
            'budget_utils.actual_cost_usd',
            'budget_utils.variance_usd',
            'budget_utils.remaining_budget_usd',
            'budget_utils.comments'
        );

        if ($request->category && $request->category != 'all') {
            $model->where('budget_utils.category', $request->category);
        }

        if ($request->searchText != '') {
            $model->where(function ($query) {
                $query->where('budget_utils.project_name', 'like', '%' . request('searchText') . '%')
                    ->orWhere('budget_utils.category', 'like', '%' . request('searchText') . '%');
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
        return $this->setBuilder('budgetutils-table')
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["budgetutils-table"].buttons().container()
                    .appendTo( "#table-actions")
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
                ->title(__('modules.budget_utils.project_id'))
                ->visible(showId()),
            Column::make('project_name')
                ->title(__('modules.budget_utils.projectName')),
            Column::make('category')
                ->title(__('app.category')),
            Column::make('budget_approved_usd')
                ->title(__('modules.budget_utils.budgetApproved')),
            Column::make('planned_cost_usd')
                ->title(__('modules.budget_utils.plannedCost')),
            Column::make('actual_cost_usd')
                ->title(__('modules.budget_utils.actualCost')),
            Column::make('variance_usd')
                ->title(__('modules.budget_utils.variance')),
            Column::make('remaining_budget_usd')
                ->title(__('modules.budget_utils.remainingBudget')),
            Column::make('comments')
                ->title(__('modules.budget_utils.comments')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];
    }
}