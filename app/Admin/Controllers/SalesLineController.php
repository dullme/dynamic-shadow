<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\SalesLine;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class SalesLineController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new SalesLine(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('document_no');
            $grid->column('line_no');
            $grid->column('sell_to_customer_no');
            $grid->column('no');
            $grid->column('location_code');
            $grid->column('description');
            $grid->column('description_2');
            $grid->column('unit_of_measure');
            $grid->column('quantity');
            $grid->column('unit_price');
            $grid->column('amount');
            $grid->column('quantity_shipped');
            $grid->column('quantity_invoiced');
            $grid->column('line_amount');
            $grid->column('variant_code');
            $grid->column('unit_of_measure_code');
            $grid->column('item_category_code');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
        
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
        
            });
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
        return Show::make($id, new SalesLine(), function (Show $show) {
            $show->field('id');
            $show->field('document_no');
            $show->field('line_no');
            $show->field('sell_to_customer_no');
            $show->field('no');
            $show->field('location_code');
            $show->field('description');
            $show->field('description_2');
            $show->field('unit_of_measure');
            $show->field('quantity');
            $show->field('unit_price');
            $show->field('amount');
            $show->field('quantity_shipped');
            $show->field('quantity_invoiced');
            $show->field('line_amount');
            $show->field('variant_code');
            $show->field('unit_of_measure_code');
            $show->field('item_category_code');
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
        return Form::make(new SalesLine(), function (Form $form) {
            $form->display('id');
            $form->text('document_no');
            $form->text('line_no');
            $form->text('sell_to_customer_no');
            $form->text('no');
            $form->text('location_code');
            $form->text('description');
            $form->text('description_2');
            $form->text('unit_of_measure');
            $form->text('quantity');
            $form->text('unit_price');
            $form->text('amount');
            $form->text('quantity_shipped');
            $form->text('quantity_invoiced');
            $form->text('line_amount');
            $form->text('variant_code');
            $form->text('unit_of_measure_code');
            $form->text('item_category_code');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
