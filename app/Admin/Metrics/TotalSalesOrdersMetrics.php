<?php

namespace App\Admin\Metrics;

use App\Admin\Repositories\Project;
use Carbon\Carbon;
use Dcat\Admin\Widgets\Metrics\Card;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class TotalSalesOrdersMetrics extends Card
{

    /**
     * 卡片底部内容.
     *
     * @var string|Renderable|\Closure
     */
    protected $footer;

    /**
     * 初始化卡片.
     */
    protected function init()
    {
        parent::init();

        $this->title('Total Sales Orders');
        $this->dropdown([
            '7'   => 'Last 7 Days',
            '28'  => 'Last 28 Days',
            '30'  => 'Last Month',
            '365' => 'Last Year',
        ]);
    }

    /**
     * 处理请求.
     *
     * @param Request $request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $total = \App\Models\SalesOrder::count();

        $last_year = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()])->count();
        $last_years = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subYears(2)->startOfYear(), Carbon::now()->subYears(2)->endOfYear()])->count();
        $last_year_rate =  round($last_years == 0 ? 100 : ($last_year - $last_years) / $last_years, 2);


        $last_month = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
        $last_months = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->count();
        $last_month_rate =  round($last_months == 0 ? 100 : ($last_month - $last_months) / $last_months, 2);

        $last_28_day = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subDays(28), Carbon::now()])->count();
        $last_28_days = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subDays(28 * 2), Carbon::now()->subDays(28)])->count();
        $last_28_days_rate =  round($last_28_days == 0 ? 100 : ($last_28_day - $last_28_days) / $last_28_days, 2);

        $last_7_day = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->count();
        $last_7_days = \App\Models\SalesOrder::whereBetween('created_at', [Carbon::now()->subDays(7*2), Carbon::now()->subDays(7)])->count();
        $last_7_day_rate = round($last_7_days == 0 ? 100 : ($last_7_day - $last_7_days) / $last_7_days, 2);

        switch ($request->get('option')) {
            case '365':
                $this->content($last_year . '/' . $total);
                $last_year_rate > 0 ? $this->up($last_year_rate) : $this->down($last_year_rate);
                break;
            case '30':
                $this->content($last_month . '/' . $total);
                $last_month_rate > 0 ? $this->up($last_month_rate) : $this->down($last_month_rate);
                break;
            case '28':
                $this->content($last_28_day . '/' . $total);
                $last_28_days_rate > 0 ? $this->up($last_28_days_rate) : $this->down($last_28_days_rate);
                break;
            case '7':
            default:
                $this->content($last_7_day . '/' . $total);
                $last_7_day_rate > 0 ? $this->up($last_7_day_rate) : $this->down($last_7_day_rate);
        }
    }

    /**
     * @param int $percent
     *
     * @return $this
     */
    public function up($percent)
    {
        return $this->footer(
            "<i class=\"feather icon-trending-up text-success\"></i> {$percent}% Increase"
        );
    }

    /**
     * @param int $percent
     *
     * @return $this
     */
    public function down($percent)
    {
        return $this->footer(
            "<i class=\"feather icon-trending-down text-danger\"></i> {$percent}% Decrease"
        );
    }

    /**
     * 设置卡片底部内容.
     *
     * @param string|Renderable|\Closure $footer
     *
     * @return $this
     */
    public function footer($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * 渲染卡片内容.
     *
     * @return string
     */
    public function renderContent()
    {
        $content = parent::renderContent();

        return <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
</div>
<div class="ml-1 mt-1 font-weight-bold text-80">
    {$this->renderFooter()}
</div>
HTML;
    }

    /**
     * 渲染卡片底部内容.
     *
     * @return string
     */
    public function renderFooter()
    {
        return $this->toString($this->footer);
    }
}
