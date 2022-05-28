<?php

namespace App\Admin\Gird\Tools;

use App\Enums\SalesOrderShippedStatus;
use App\Models\SalesLine;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SyncVoltageCNSalesOrderLine extends AbstractTool
{

    /**
     * 按钮样式定义，默认 btn btn-white waves-effect
     *
     * @var string
     */
    protected $style = 'btn btn-white waves-effect';
    private $cn_sales_line_table = 'dbo.Voltage CN$Sales Line$437dbf0e-84ff-417a-965d-ed2bb9650972';
//    private $cn_sales_header_custom_table = 'dbo.Voltage CN$Sales Header$6663e64b-185e-4fad-9329-2df133559a7c';
    private $document_type = 1;//SQ:0;SO:1


    /**
     * 按钮文本
     *
     * @return string|void
     */
    public function title()
    {
        return 'Sync Sales Orders From Dynamic';
    }

    /**
     *  确认弹窗，如果不需要则返回空即可
     *
     * @return array|string|void
     */
    public function confirm()
    {
        // 只显示标题
//        return '您确定要发送新的提醒消息吗？';

        // 显示标题和内容
        return ['Are you sure you want to Sync sales lines from Dynamic?', ''];
    }

    /**
     * 处理请求
     * 如果你的类中包含了此方法，则点击按钮后会自动向后端发起ajax请求，并且会通过此方法处理请求逻辑
     *
     * @param Request $request
     */
    public function handle(Request $request)
    {

        $this->createAllSalesLine();

        return $this->response()->success('Sync succeeded')->refresh();
    }

    /**
     * 设置请求参数
     *
     * @return array|void
     */
    public function parameters()
    {
        return [

        ];
    }

    public function createSalesLineBySalesOrderNo($salesOrderNo)
    {
        $cn_sales_lines = DB::connection('azure')->table($this->cn_sales_line_table)->where('Document No_', $salesOrderNo)->get();
        $cn_sales_lines->map(function ($sales_line) {
            return $this->createSalesLine($sales_line);
        });

        $this->updateSalesOrder($cn_sales_lines, $salesOrderNo);
    }

    public function createAllSalesLine()
    {
        $cn_sales_lines = DB::connection('azure')->table($this->cn_sales_line_table)->where('Document Type', $this->document_type)->get();
        $cn_sales_lines->map(function ($sales_line) {

            return $this->createSalesLine($sales_line);
        });

        $cn_sales_lines->groupBy('Document No_')->map(function ($cn_sales_lines, $salesOrderNo){
            $this->updateSalesOrder($cn_sales_lines, $salesOrderNo);
        });
    }

    private function createSalesLine($sales_line)
    {
        SalesLine::updateOrInsert(
            ['line_no' => $sales_line['Line No_']],
            [
                'document_no'          => $sales_line['Document No_'],
                'sell_to_customer_no'  => $sales_line['Sell-to Customer No_'],
                'type'                 => $sales_line['Type'],
                'no'                   => $sales_line['No_'],
                'location_code'        => $sales_line['Location Code'],
                'description'          => $sales_line['Description'],
                'description_2'        => $sales_line['Description 2'],
                'unit_of_measure'      => $sales_line['Unit of Measure'],
                'quantity'             => $sales_line['Quantity'],
                'unit_price'           => $sales_line['Unit Price'],
                'amount'               => $sales_line['Amount'],
                'quantity_shipped'     => $sales_line['Quantity Shipped'],
                'quantity_invoiced'    => $sales_line['Quantity Invoiced'],
                'line_amount'          => $sales_line['Line Amount'],
                'variant_code'         => $sales_line['Variant Code'],
                'unit_of_measure_code' => $sales_line['Unit of Measure Code'],
                'item_category_code'   => $sales_line['Item Category Code'],
                'created_at'           => Carbon::now(),
                'updated_at'           => Carbon::now(),
            ]
        );

        return $sales_line['Line No_'];
    }

    public function updateSalesOrder($cn_sales_lines, $salesOrderNo)
    {
        if($cn_sales_lines->sum('Quantity Shipped') == 0){
            $shipped = SalesOrderShippedStatus::Waiting;//未开始
        }elseif($cn_sales_lines->sum('Quantity Shipped') < $cn_sales_lines->sum('Quantity')){
            $shipped = SalesOrderShippedStatus::Processing;//进行中
        }else{
            $shipped = SalesOrderShippedStatus::Completed;//已完成
        }

        if($cn_sales_lines->sum('Quantity Invoiced') == 0){
            $invoiced = '1';//未开始
        }elseif($cn_sales_lines->sum('Quantity Invoiced') < $cn_sales_lines->sum('Quantity')){
            $invoiced = '2';//进行中
        }else{
            $invoiced = '3';//已完成
        }

        SalesOrder::where('no', $salesOrderNo)->update([
            'shipped_status' => $shipped,
            'invoiced_status' =>$invoiced,
        ]);
    }
}
