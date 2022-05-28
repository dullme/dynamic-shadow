<?php

namespace App\Admin\Gird\Tools;

use App\Models\SalesOrder;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SyncVoltageCNSalesOrder extends AbstractTool
{

    /**
     * 按钮样式定义，默认 btn btn-white waves-effect
     *
     * @var string
     */
    protected $style = 'btn btn-white waves-effect';
    private $cn_sales_header_table = 'dbo.Voltage CN$Sales Header$437dbf0e-84ff-417a-965d-ed2bb9650972';
    private $cn_sales_header_custom_table = 'dbo.Voltage CN$Sales Header$6663e64b-185e-4fad-9329-2df133559a7c';
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
        return ['Are you sure you want to Sync sales orders from Dynamic?', ''];
    }

    /**
     * 处理请求
     * 如果你的类中包含了此方法，则点击按钮后会自动向后端发起ajax请求，并且会通过此方法处理请求逻辑
     *
     * @param Request $request
     */
    public function handle(Request $request)
    {

        $cn_sales_orders = DB::connection('azure')->table($this->cn_sales_header_table)->where('Document Type', $this->document_type)->get();
        $cn_sales_orders_custom = DB::connection('azure')->table($this->cn_sales_header_custom_table)->where('Document Type', $this->document_type)->get();
        $cn_sales_orders->map(function ($sales_order) use ($cn_sales_orders_custom) {
            return $this->createSalesOrder(array_merge($sales_order, $cn_sales_orders_custom->where('No_', $sales_order['No_'])->first()));
        });

        (new SyncVoltageCNSalesOrderLine())->createAllSalesLine();

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

    private function createSalesOrder($sales_order)
    {
        $order_date = \Carbon\Carbon::parse($sales_order['Order Date']);
        $document_date = \Carbon\Carbon::parse($sales_order['Document Date']);
        SalesOrder::updateOrInsert(
            ['no' => $sales_order['No_']],
            [
                'project_no'            => $sales_order['Project No_'],
                'project_name'          => $sales_order['Project Name'],
                'sell_to_customer_no'   => $sales_order['Sell-to Customer No_'],
                'sell_to_customer_name' => $sales_order['Sell-to Customer Name'],
                'sell_to_address'       => $sales_order['Sell-to Address'],
                'bill_to_customer_no'   => $sales_order['Bill-to Customer No_'],
                'bill_to_name'          => $sales_order['Bill-to Name'],
                'bill_to_address'       => $sales_order['Bill-to Address'],
                'bill_to_city'          => $sales_order['Bill-to City'],
                'bill_to_contact'       => $sales_order['Bill-to Contact'],
                'ship_to_name'          => $sales_order['Ship-to Name'],
                'ship_to_address'       => $sales_order['Ship-to Address'],
                'ship_to_city'          => $sales_order['Ship-to City'],
                'ship_to_contact'       => $sales_order['Ship-to Contact'],
                'order_date'            => $order_date < '1970-01-01' ? null : $order_date,
                'document_date'         => $document_date < '1970-01-01' ? null : $document_date,
                'external_document_no'  => $sales_order['External Document No_'],
                'status'                => $sales_order['Status'],
                'currency_code'         => $sales_order['Currency Code'],
                'currency_factor'       => $sales_order['Currency Factor'],
                'created_at'            => $order_date < '1970-01-01' ? null : $order_date,
                'updated_at'            => $order_date < '1970-01-01' ? null : $order_date,
            ]
        );

        return $sales_order['No_'];
    }
}
