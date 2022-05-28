<?php

namespace App\Admin\Controllers;

use App\Admin\Gird\Tools\SyncVoltageCNSalesOrder;
use App\Admin\Gird\Tools\SyncVoltageCNSalesOrderLine;
use App\Admin\Repositories\SalesOrder;
use App\Enums\SalesOrderShippedStatus;
use App\Enums\SalesOrderStatus;
use App\Models\SalesLine;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class SalesOrderController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SalesOrder(), function (Grid $grid) {
            $grid->number();
            $grid->column('no')->display(function ($no){
                $url = url('/admin/sales_orders/'. $this->id);
                return "<a href='{$url}'>{$no}</a>";
            })->sortable();
            $grid->column('project_no')->sortable();
            $grid->column('project_name')->sortable();
            $grid->column('sell_to_customer_no')->sortable();
            $grid->column('sell_to_customer_name')->sortable();
            $grid->column('external_document_no')->sortable();
            $grid->column('status')->using(SalesOrderStatus::getKeys())->sortable();
            $grid->column('order_date')->display(function ($order_date) {
                return substr($order_date, 0, 10);
            })->sortable();
            $grid->column('document_date')->display(function ($document_date) {
                return substr($document_date, 0, 10);
            })->sortable();
            $grid->column('shipped_status')->using(SalesOrderShippedStatus::getKeys())
                ->dot([
                    SalesOrderShippedStatus::Unknown => 'default',
                    SalesOrderShippedStatus::Waiting => 'warning',
                    SalesOrderShippedStatus::Processing => 'primary',
                    SalesOrderShippedStatus::Completed => 'success',
                ])->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('no');
                $filter->like('project_no');
                $filter->like('project_name');
                $filter->like('external_document_no');
                $filter->equal('status')->select(SalesOrderStatus::getKeys());

            });

            $grid->tools(new SyncVoltageCNSalesOrder());

            $grid->model()->orderBy('no', 'desc');

            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();
            $grid->paginate(50);
        });
    }

    public function show($id, Content $content)
    {
        $salesOrder = \App\Models\SalesOrder::findOrfail($id);

        (new SyncVoltageCNSalesOrderLine())->createSalesLineBySalesOrderNo($salesOrder->no);

        $salesLines = SalesLine::where('document_no', $salesOrder->no)->orderBy('line_no', 'ASC')->get();

        return $content->header('SalesOrder')
            ->description('Show')
            ->body(view('admin.sales-order', compact('salesOrder', 'salesLines')));
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
        return Show::make($id, new SalesOrder(), function (Show $show) {
            $show->row(function (Show\Row $show) {
                $show->width(3)->id;
                $show->width(3)->no;
                $show->width(4)->field('bill_to_customer_no');
            });

            $show->field('bill_to_customer_no');
            $show->field('bill_to_name');
            $show->field('bill_to_address');
            $show->field('bill_to_city');
            $show->field('ship_to_contact');
            $show->field('ship_to_name');
            $show->field('ship_to_address');
            $show->field('ship_to_city');
            $show->field('order_date');
            $show->field('document_date');
            $show->field('external_document_no');
            $show->field('status');
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
        return Form::make(new SalesOrder(), function (Form $form) {
            $form->display('id');
            $form->text('no');
            $form->text('sell_to_customer_no');
            $form->text('bill_to_customer_no');
            $form->text('bill_to_name');
            $form->text('bill_to_address');
            $form->text('bill_to_city');
            $form->text('ship_to_contact');
            $form->text('ship_to_name');
            $form->text('ship_to_address');
            $form->text('ship_to_city');
            $form->text('order_date');
            $form->text('document_date');
            $form->text('external_document_no');
            $form->text('status');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
