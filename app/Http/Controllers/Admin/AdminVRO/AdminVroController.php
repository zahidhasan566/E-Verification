<?php

namespace App\Http\Controllers\Admin\AdminVRO;

use App\Http\Controllers\Controller;
use App\Models\AssignedVro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminVroController extends Controller
{
    public function index(Request $request){
        $roleId = Auth::user()->RoleId;
        $take = $request->take;
        $search = $request->search;

        $assignVro = AssignedVro::select(
            'AssignedVroID',
            'CustomerCode',
            'CustomerName',
            'Business',
            'LimitAppliedFor',
            'AssignedVROStaffId',
            'AssignedVROName',
            'EntryBy',
        )
            ->where(function ($q) use ($search) {
                $q->where('CustomerName', 'like', '%' . $search . '%');
                $q->Orwhere('AssignedVROName', 'like', '%' . $search . '%');
            })
            ->orderBy('AssignedVroID','desc');

        if ($request->type === 'export') {
            return response()->json([
                'data' => $assignVro->get(),
            ]);
        } else {
            return $assignVro->paginate($take);
        }

    }
    public function getDemoExcelFile()
    {
        $excel_url = url('/') . '/assets/file/Assign_Vro_Sample.xls';
        return $excel_url;
    }
    public function storeVro(Request $request){
        $adjustmentImportData = $request->ExcelData;

        try{
            foreach ($adjustmentImportData as $singleData) {
                //Check Existing VRO

                $assignedVROStaffID =(string )$singleData['AssignedVROStaffID'];
                $checkVro = AssignedVro::select('AssignedVROStaffId')->where('Business',$singleData['BusinessCode'])->where('CustomerCode',$singleData['CustomerCode'] )->where('AssignedVROStaffId',$assignedVROStaffID )->first();
                if(!empty($checkVro->AssignedVROStaffId)){
                    AssignedVro::where('AssignedVROStaffId', $assignedVROStaffID)->delete();
                }

                $assignedVro = new AssignedVro();
                $assignedVro->CustomerCode = $singleData['CustomerCode'];
                $assignedVro->CustomerName = $singleData['CustomerName'];
                $assignedVro->Business = $singleData['BusinessCode'];
                $assignedVro->LimitAppliedFor = $singleData['LimitAppliedFor'];
                $assignedVro->AssignedVROStaffId = $singleData['AssignedVROStaffID'];
                $assignedVro->AssignedVROName = $singleData['AssignedVROName'];
                $assignedVro->EntryBy = Auth::user()->UserID;
                $assignedVro->EntryDate = Carbon::now();
                $assignedVro->IpAddress = $request->ip();
                $assignedVro->save();



            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data imported successfully!'
            ],200);

        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!' . $exception->getMessage()
            ], 500);
        }

    }

    public function deleteVRO(Request $request){
        $assignedVro = AssignedVro::find($request->AssignedVroID);
        $assignedVro->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully!'
        ],200);
    }
}
