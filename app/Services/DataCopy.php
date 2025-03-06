<?php
namespace App\Services;

use App\Models\TanagyouDetail;
use Illuminate\Support\Facades\DB;

class DataCopy
{
    public static function TanagyouDataCopyProcess()
    {
        $result = false;

        $query  = DB::table('dankas')
                ->join('followers', 'dankas.id', '=', 'followers.danka_id')
                ->select('dankas.id',
                        'followers.name',
                        'followers.namekana',
                        'followers.postcode',
                        'followers.address1',
                        'follwoers.address2',
                        'followers.tel')
                ->where('dankas.tanagyou', '=', 1)
                ->where('followers.chiefmourner_flg', '=', 1);

        $dankas = $query->get();

        foreach ($dankas as $danka) {
            $tanagyoudetail = new TanagyouDetail();

            $tanagyoudetail->danka_id = $danka->id;
            $tanagyoudetail->name = $danka->name;
            $tanagyoudetail->namekana = $danka->namekana;
            $tanagyoudetail->postcode = $danka->postcode;
            $tanagyoudetail->address1 = $danka->address1;
            $tanagyoudetail->address2 = $danka->address2;
            $tanagyoudetail->tel = $danka->tel;
            $tanagyoudetail->save();
        }

    }

}