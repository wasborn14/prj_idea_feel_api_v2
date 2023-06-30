<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Feel;
use App\Models\FeelReason;
use App\Models\User;


class FeelController extends Controller
{
    /**
     * Create Feel
     *
     * @return JsonResponse
     * @throws InternalServerErrorException
     */
    public function createFeel(Request $request) {
        $user_id = Auth::id();
        $feel_reason = $request->input('reason');
        $date = new Carbon($request->input('date'));
        $format_date = $date->timezone('Asia/Tokyo')->format('Y-m-d');
        $is_predict = $request->input('is_predict');
        $feel = Feel::where('date', $format_date)->where('is_predict', $is_predict);

        if ($feel->exists()) {
            // 同じ日の記録が既に存在する場合
            try {
                $feel = $feel->first();
                $feel->user_id = $user_id;
                $feel->date = $format_date;
                $feel->value = $request->input('value');
                if ($feel_reason != 0) {
                    $feel->reason_id = $feel_reason;
                } else {
                    $feel->reason_id = null;
                }
                $feel->memo = $request->input('memo');
                $feel->is_predict = $request->input('is_predict');
                $feel->save();
    
                return response()->json([
                    "message" => "updated feel record"
                 ], 201); 

            } catch (Exception $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());
    
                throw new InternalServerErrorException(
                    'Failed To Update Category List',
                    500,
                    'Internal Server Error'
                );
            }
        } else {
            // 同じ日の記録が存在しない場合
            try {
                $new_feel = new Feel;
                $new_feel->user_id = $user_id;
                $new_feel->date = $format_date;
                $new_feel->value = $request->input('value');
                if ($feel_reason != 0) {
                    $new_feel->reason_id = $feel_reason;
                } else {
                    $new_feel->reason_id = null; 
                }
                $new_feel->memo = $request->input('memo');
                $new_feel->is_predict = $request->input('is_predict');
                $new_feel->save();
    
                return response()->json([
                    "message" => "created feel record"
                 ], 201);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());
    
                throw new InternalServerErrorException(
                    'Failed To Update Category List',
                    500,
                    'Internal Server Error'
                );
            }
        }
    }

    /**
     * Get Feel List
     *
     * @return JsonResponse
     * @throws InternalServerErrorException
     */
    public function getFeelList() {
        $user_id = Auth::id(); 

        // feelが存在するリスト
        $feel_exist_list = Feel::where('user_id', $user_id)->orderBy('date', 'desc')->get();

        $feel_list = [];
        foreach ($feel_exist_list as $feel_data) {
            $feel = new \stdClass();
            $feel_date = new Carbon($feel_data->date);
            $feel->date = $feel_date->format('m/d/Y');
            $feel->value = $feel_data->value;
            $feel->is_predict = $feel_data->is_predict;
            $feel->memo = $feel_data->memo;
            if ($feel_data->reason_id) {
                $reason = FeelReason::find($feel_data->reason_id);
                $feel->reason = $reason->title;
            } else {
                $feel->reason = "";
            }
            array_push($feel_list, $feel);
        };

        return response()->json($feel_list, 200);
    }

    /**
     * Get Feel Graph
     *
     * @return JsonResponse
     * @throws InternalServerErrorException
     */
    public function getFeelGraph($start_date, $end_date) {
        $user_id = Auth::id(); 
        $request_start_date = new Carbon($start_date);
        $request_end_date = new Carbon($end_date);
        $start_date = $request_start_date->timezone('Asia/Tokyo')->format('Y-m-d'); 
        $end_date = $request_end_date->timezone('Asia/Tokyo')->format('Y-m-d'); 

        // 日付のリスト
        $date_list = CarbonPeriod::create($start_date, $end_date)->toArray();
        // feelが存在するリスト
        $record_exist_list = Feel::where('user_id', $user_id)->whereBetween('date', [$start_date, $end_date])->where('is_predict', false)->get();
        $predict_exist_list = Feel::where('user_id', $user_id)->whereBetween('date', [$start_date, $end_date])->where('is_predict', true)->get();

        $record_list = $this->createFeelGraphList($date_list, $record_exist_list);
        $predict_list = $this->createFeelGraphList($date_list, $predict_exist_list);

        return response()->json([
            'record_list' => $record_list,
            'predict_list' => $predict_list,
        ], 200);
    }

    /**
     * Create Feel List
     *
     * @return array
     */
    protected function createFeelGraphList($date_list, $list) {
        $new_list = [];

        foreach ($date_list as $date) {
            $is_exist = false;
            $content = new \stdClass();
            $detail = new \stdClass();
            foreach ($list as $item) {
                if ($date == $item->date) {
                    // feelが存在する場合
                    $content->date = $date->format('m/d');

                    $detail->value = $item->value;
                    if ($item->reason_id) {
                        $reason = FeelReason::find($item->reason_id);
                        $detail->reason = $reason->title;
                    } else {
                        $detail->reason = "";
                    }
                    $detail->memo = is_null($item->memo) ? "" : $item->memo;
                    $content->detail = $detail;

                    $is_exist = true;
                    array_push($new_list, $content);
                }
            };
            if (!$is_exist) {
                // feelが存在しない場合
                $content->date = $date->format('m/d');
                $content->detail = $detail;
                array_push($new_list, $content);  
            };
        };

        return $new_list;
    } 
}
