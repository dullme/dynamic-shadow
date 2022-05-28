<?php

namespace App\Admin\Controllers;

use App\Admin\Gird\Tools\SyncVoltageUSJob;
use Str;
use App\Admin\Metrics\Examples\NewUsers;
use App\Admin\Metrics\ProjectStatusMetrics;
use App\Admin\Metrics\TotalProjects;
use App\Admin\Repositories\Project;
use App\Enums\ProjectStatus;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;

class ProjectController extends AdminController
{

    public function index(Content $content)
    {
        return $content
            ->header('Projects')
            ->description('Projects information')
            ->body(function (Row $row) {
                $row->column(4, new TotalProjects());
                $row->column(4, new NewUsers());
                $row->column(4, new ProjectStatusMetrics());
            })
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Project(), function (Grid $grid) {
            $grid->number();
            $grid->column('no')->sortable();
            $grid->column('description')->sortable();
            $grid->column('bill_to_customer_no')->sortable();
            $grid->column('bill_to_name')->sortable();
            $grid->column('bill_to_address')->display(function($bill_to_address) {
                $str = Str::limit($bill_to_address, 30, '...');
                return "<span title='{$bill_to_address}'>{$str}</span>";
            })->sortable();
            $grid->column('status')->using(ProjectStatus::getKeys())->sortable();
            $grid->column('salesperson_code')->sortable();
            $grid->column('created_at')->display(function ($created_at) {
                return substr($created_at, 0, 10);
            })->sortable();
            $grid->column('updated_at')->display(function ($updated_at) {
                return substr($updated_at, 0, 10);
            })->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('no');
                $filter->equal('status')->select(ProjectStatus::getKeys());
                $filter->like('description');
                $filter->like('bill_to_customer_no');
                $filter->like('bill_to_name');
                $filter->like('bill_to_address');

            });

            $grid->model()->orderBy('created_at', 'desc');

            $grid->tools(new SyncVoltageUSJob());

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();
            $grid->paginate(50);
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Project(), function (Show $show) {
            $show->field('id');
            $show->field('no');
            $show->field('description');
            $show->field('description_2');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Project(), function (Form $form) {
            $form->display('id');
            $form->text('no');
            $form->text('description');
            $form->text('description_2');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
