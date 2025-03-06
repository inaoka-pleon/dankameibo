<?php

namespace App\Rules;

use App\Services\KaikiData;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;

class KaikiDateCheck implements DataAwareRule, ValidationRule
{
    /**
     * バリデーション下の全データ
     *
     * @var array<string, mixed>
     */
    protected array $data = [];
    protected string $kbn;

    public function __construct($kbn)
    {
        $this->kbn  = $kbn;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->data = request()->all();
        dd($this->data);
        if ($this->kbn === 'from') {
            $from_ymd   = null;
            if (!empty($this->data['from_year_kbn']) &&
                !empty($this->data['from_month']) &&
                !empty($this->data['from_day'])) {
                $from_ymd   = KaikiData::GetYmd($this->data['from_year_kbn'], $this->data['from_month'], $this->data['from_day']);
            }
            if (is_null($from_ymd)) {
                $fail('対象期間（自）の日付が正しくありません。');
            }
        } elseif ($this->kbn === 'to') {
            $to_ymd     = null;
            if (!empty($this->data['to_year_kbn']) &&
                !empty($this->data['to_month']) &&
                !empty($this->data['to_day'])) {
                $to_ymd     = KaikiData::GetYmd($this->data['to_year_kbn'], $this->data['to_month'], $this->data['to_day']);
            }
            if (is_null($to_ymd)) {
                $fail('対象期間（至）の日付が正しくありません。');
            }
        } elseif ($this->kbn === 'houyou_date') {
            $houyou_date    = null;
            if (!empty($this->data['houyou_month']) &&
                !empty($this->data['houyou_day'])) {
                $houyou_date    = KaikiData::GetYmd(Config::get('const.YearKbn.ThisYear'), $this->data['houyou_month'], $this->data['houyou_day']);
            }
            if (is_null($houyou_date)) {
                $fail('法要日の日付が正しくありません。');
            }
        } elseif ($this->kbn === 'large_and_small') {
            // 日付の大小チェック
            if (!empty($this->data['from_year_kbn']) &&
                !empty($this->data['from_month']) &&
                !empty($this->data['from_day']) &&
                !empty($this->data['to_year_kbn']) &&
                !empty($this->data['to_month']) &&
                !empty($this->data['to_day'])) {
                $from_ymd   = KaikiData::GetYmd($this->data['from_year_kbn'], $this->data['from_month'], $this->data['from_day']);
                $to_ymd     = KaikiData::GetYmd($this->data['to_year_kbn'], $this->data['to_month'], $this->data['to_day']);
                if (!is_null($from_ymd) && !is_null($to_ymd)) {
                    if ($from_ymd->gt($to_ymd)) {
                        $fail('対象期間は対象期間（自）≦対象期間（至）で設定して下さい。');
                    }
                }
            }
        }
    }

    /**
     * バリデーション下のデータをセット
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }
}