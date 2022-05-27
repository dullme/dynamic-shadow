<?php

namespace App\Admin\Controllers;

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

    private $us_project_table = 'dbo.Voltage US$Job$437dbf0e-84ff-417a-965d-ed2bb9650972';
    private $cn_project_table = 'dbo.Voltage CN$Job$437dbf0e-84ff-417a-965d-ed2bb9650972';

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

    public function dynamicSQL()
    {
        $us_projects = DB::connection('azure')->table($this->us_project_table)->get();

        $us_project_nos = $us_projects->map(function ($project) {
            return $this->createProject($project);
        });

        $cn_projects = DB::connection('azure')->table($this->cn_project_table)->whereNotIn('No_', $us_project_nos)->get();
        $cn_project_nos = $cn_projects->map(function ($project) use ($us_project_nos) {
            return $this->createProject($project);
        });


        dd($cn_project_nos);
    }

    /**
     * 创建项目
     * @param $project
     * @return mixed
     */
    public function createProject($project)
    {
        $created_at = \Carbon\Carbon::parse($project['Creation Date']);
        $updated_at = \Carbon\Carbon::parse($project['Last Date Modified']);
        \App\Models\Project::updateOrInsert(
            ['no' => $project['No_']],
            [
                'search_description'          => $project['Search Description'],
                'description'                 => $project['Description'],
                'description_2'               => $project['Description 2'],
                'bill_to_customer_no'         => $project['Bill-to Customer No_'],
                'bill_to_name'                => $project['Bill-to Name'],
                'bill_to_address'             => $project['Bill-to Address'],
                'bill_to_address_2'           => $project['Bill-to Address 2'],
                'bill_to_city'                => $project['Bill-to City'],
                'bill_to_post_code'           => $project['Bill-to Post Code'],
                'bill_to_country_region_code' => $project['Bill-to Country_Region Code'],
                'bill_to_contact_no'          => $project['Bill-to Contact No_'],
                'status'                      => $project['Status'],
                'created_at'                  => $created_at < '1970-01-01' ? null : $created_at,
                'updated_at'                  => $updated_at < '1970-01-01' ? null : $updated_at,
            ]
        );

        return $project['No_'];
    }
}
