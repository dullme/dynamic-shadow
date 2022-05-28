<?php

namespace App\Admin\Gird\Tools;

use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SyncVoltageUSJob extends AbstractTool
{

    /**
     * 按钮样式定义，默认 btn btn-white waves-effect
     *
     * @var string
     */
    protected $style = 'btn btn-white waves-effect';
    private $us_project_table = 'dbo.Voltage US$Job$437dbf0e-84ff-417a-965d-ed2bb9650972';
    private $us_project_custom_table = 'dbo.Voltage US$Job$6663e64b-185e-4fad-9329-2df133559a7c';
    private $cn_project_table = 'dbo.Voltage CN$Job$437dbf0e-84ff-417a-965d-ed2bb9650972';
    private $cn_project_custom_table = 'dbo.Voltage CN$Job$6663e64b-185e-4fad-9329-2df133559a7c';


    /**
     * 按钮文本
     *
     * @return string|void
     */
    public function title()
    {
        return 'Sync Projects From Dynamic';
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
        return ['Are you sure you want to Sync projects from Dynamic?', ''];
    }

    /**
     * 处理请求
     * 如果你的类中包含了此方法，则点击按钮后会自动向后端发起ajax请求，并且会通过此方法处理请求逻辑
     *
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $us_projects = DB::connection('azure')->table($this->us_project_table)->get();
        $us_project_custom = DB::connection('azure')->table($this->us_project_custom_table)->get();

        $us_project_nos = $us_projects->map(function ($project) use($us_project_custom){
            return $this->createProject(array_merge($project, $us_project_custom->where('No_', $project['No_'])->first()));
        });

        $cn_projects = DB::connection('azure')->table($this->cn_project_table)->whereNotIn('No_', $us_project_nos)->get();
        $cn_project_customs = DB::connection('azure')->table($this->cn_project_custom_table)->get();
        $cn_projects->map(function ($project) use ($us_project_nos, $cn_project_customs) {
            return $this->createProject(array_merge($project, $cn_project_customs->where('No_', $project['No_'])->first()));
        });

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

    private function createProject($project)
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
                'project_manager'             => $project['Project Manager'],
                'project_size'                => $project['Project Size'],
                'owner'                       => $project['Owner'],
                'salesperson_code'            => $project['Salesperson Code'],
                'epc'                         => $project['EPC'],
                'created_at'                  => $created_at < '1970-01-01' ? null : $created_at,
                'updated_at'                  => $updated_at < '1970-01-01' ? null : $updated_at,
            ]
        );

        return $project['No_'];
    }
}
