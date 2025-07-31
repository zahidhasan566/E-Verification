<?php

namespace App\Http\Controllers\Admin\Reports;
use App\Models\ShopInformation;
use App\Traits\CommonTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use CommonTrait;

    public function getAllShopInformation(Request $request){
        $CurrentPage = $request->pagination['current_page'];
        $PerPage = 20;
        $Export = $request->Export;
        $CustomerCode = $request->CustomerCode;
        $dateFrom = $request->DateFrom;
        $dateTo = $request->DateTo;
        $userID = Auth::user()->UserId;

        if ($Export == 'Y'){
            $CurrentPage = '%';
        }
        $sql = "exec usp_reportAllShopInformation '$dateFrom', '$dateTo','$userID','$PerPage','$CurrentPage'";
        return $this->getReportData($sql, $PerPage, $CurrentPage, $Export);
    }
    public function getSingleShopInformation($shopId){
        $shopInfo  =  ShopInformation::with('competitorShopBusinesses','businessWithACI')->select(
            'ShopInformation.ShopID',
            'ShopInformation.AssignVROStaffId',
            'Users.Name as AssignVroName',
            'ShopInformation.Business',
            'ShopInformation.ContactPersonDesignation',
            'ShopInformation.ContactPersonName',
            'ShopInformation.ProprietorName',
            'ShopInformation.TypeOfEntity',
            'ShopInformation.CustomerName',
            'ShopInformation.CustomerCode',
            'ShopInformation.SBU',
            'ShopInformation.CustomerProprietorName',
            'ShopInformation.CustomerMobileNo',
            'ShopInformation.CustomerAddress',
            'ShopInformation.OwnerShip',
            'ShopInformation.Condition',
            'ShopInformation.DeedAgreement',
            'ShopInformation.ShopPhoto',
            'ShopInformation.CustomerReputation',
            'ShopInformation.Latitude',
            'ShopInformation.Longitude',
            'ShopInformation.ShopAddress',
            'ShopInformation.PaymentBehaviour',
            'ShopInformation.ModeOfPayment',
            'ShopInformation.YearlyPurchasePotential',
            'ShopInformation.PaymentTermsInDays',
            'ShopInformation.CustomerProposedCreditLimit',
            'ShopInformation.RepresentativeComment',
            'ShopInformation.RepresentativePhoto',
            'ShopInformation.BalancePerCustomer',
            'ShopInformation.ApproveStatus',
            'ShopInformation.EntryDate',
            'Business.BusinessName',
            'ShopInformation.RepresentativeComment',
        )
            ->join('Users', 'Users.UserID', '=', 'ShopInformation.AssignVROStaffId')
            ->join('Business', 'Business.Business', '=', 'ShopInformation.Business')
            ->where('ShopInformation.ShopID', $shopId)
            ->first();

        return response()->json(['status' => 'Success',
            'message' => 'Shop Information fetched successfully!',
            'data' => $shopInfo], 200);


    }
}
