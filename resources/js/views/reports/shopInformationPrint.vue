<template>
    <div class="container-fluid">
        <breadcrumb :options="['Shop Information Print']"></breadcrumb>
        <div class="card">
            <div class="card-body">
                <div id="printDiv">
                    <h1>Credit Customer Physical Verification Report</h1>
                    <h2>{{shopInfo.BusinessName}}</h2>

                    <div class="section">
                        <table>
                            <tr>
                                <td>Customer Name</td>
                                <td>{{shopInfo.CustomerName}}</td>
                            </tr>
                            <tr>
                                <td>Customer Code</td>
                                <td>{{shopInfo.CustomerCode}}</td>
                            </tr>
                            <tr>
                                <td>Proprietor's Name </td>
                                <td>{{shopInfo.ProprietorName}}</td>
                            </tr>
                            <tr>
                                <td rowspan="4">Person Contacted</td>
                                <td>Name: {{shopInfo.ContactPersonName}} </td>
                            </tr>
                            <tr>
                                <td>Designation:  {{shopInfo.ContactPersonDesignation}}</td>
                            </tr>
                            <tr>
                                <td>Address:  {{shopInfo.CustomerAddress}}</td>
                            </tr>
                            <tr>
                                <td>Phone:  {{shopInfo.CustomerMobileNo}}</td>
                            </tr>
                            <tr>
                                <td>Type of Legal Entity</td>
                                <td class="checkbox-group">
                                    <span>Proprietorship</span>
                                    <span>Partnership</span>
                                    <span>Private Limited Company</span>
                                    <span>NGO</span>
                                    <span>Government Institutions</span>
                                    <span>PLC</span>
                                    <span>Individual</span>
                                </td>
                            </tr>
                            <tr>
                                <td >Outlet Shop Condition</td>
                                <td class="checkbox-group-outlet">
                                    <span>Poor</span>
                                    <span>Moderate</span>
                                    <span>Good</span>
                                </td>
                                <td class="checkbox-group">
                                    <span>Owned</span>
                                    <span>Rented Shop</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Deed  Agreement </td>
                                <td class="checkbox-group-deed-agreement">
                                    <span>Yes</span>
                                    <span>No</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Shop Picture</td>
                                <td class="checkbox-group">
                                    <span>Yes</span>
                                    <span>No</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Shop Location</td>
                                <td>
                                    {{shopInfo.ShopAddress}}
                                </td>
                            </tr>
                            <tr>
                                <td>Reputation of the Customer in the Market</td>
                                <td class="checkbox-group">
                                    <span>Bad</span>
                                    <span>Poor</span>
                                    <span>Average</span>
                                    <span>Good</span>
                                    <span>Excellent</span>
                                </td>
                            </tr>

                            <tr>
                                <td>Business Performance with other Company's [If any]:</td>
                                <td>
                                    <table id="otherCompaniesTable" class="inner-table">
                                        <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Invoice Amount</th>
                                            <th>Payment Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody v-for="item in shopInfo.competitor_shop_businesses" v-if="shopInfo.competitor_shop_businesses.length>0">
                                        <td>{{item.Name}}</td>
                                        <td style="text-align:right">{{item.InvoiceAmount}}</td>
                                        <td style="text-align:right">{{item.PaymentAmount}}</td>
                                        <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>Payment Terms with ACI:</td>
                                <td class="checkbox-group">
                                    <span>30 Days</span>
                                    <span>60 Days</span>
                                    <span>90 Days</span>
                                    <span>120 Days</span>
                                    <span>180 Days</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Payment Behavior:</td>
                                <td class="checkbox-group">
                                    <span>Partial Payment</span>
                                    <span>Invoice Payment</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Mode of Payment:</td>
                                <td class="checkbox-group">
                                    <span>Cheque</span>
                                    <span>DD</span>
                                    <span>Cash</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Potential for Yearly Purchase in BDT:</td>
                                <td>{{shopInfo.YearlyPurchasePotential}}</td>
                            </tr>
                            <tr>
                                <td>Business with other SBU of ACI</td>
                                <td>{{shopInfo.SBU}}</td>
                            </tr>
                            <tr>
                                <td>Credit Status with other SBU of ACI</td>
                                <td>
                                    <table id="otherBusiinessTable" class="inner-table">
                                        <thead>
                                        <tr>
                                            <th>Business</th>
                                            <th>Credit limit</th>
                                            <th>Days</th>
                                            <th>Total Due</th>
                                            <th>Over Due</th>
                                            <th>Age</th>
                                        </tr>
                                        </thead>
                                        <tbody v-for="item in shopInfo.business_with_a_c_i" v-if="shopInfo.business_with_a_c_i.length>0">
                                        <td>{{item.Business}}</td>
                                        <td style="text-align:right">{{item.Limit}}</td>
                                        <td style="text-align:right">{{item.Days}}</td>
                                        <td style="text-align:right">{{item.Dues}}</td>
                                        <td style="text-align:right">{{item.OverDue}}</td>
                                        <td style="text-align:right">{{item.Age}}</td>
                                        <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td >Balance As Per Customer (for extension):</td>
                                <td>Amount: <span style="text-align: right">{{shopInfo.BalancePerCustomer}}</span></td>
                            </tr>
                            <tr>
                                <td rowspan="2">Proposed Credit limit</td>
                                <td>Amount: <span style="text-align: right">{{shopInfo.CustomerProposedCreditLimit}}</span></td>
                            </tr>
                            <tr>
                                <td>In Word: {{inWords(parseInt(shopInfo.CustomerProposedCreditLimit))}} </td>
                            </tr>
                            <tr>
                                <td>Meta Data Information</td>
                                <td> {{shopInfo.ShopAddress}} -{{shopInfo.EntryDate}}</td>
                            </tr>
                            <tr >
                                <td style="text-align: center" colspan="2">☑ I hereby declare that the information provided by the representative is true and accurate to the best of my knowledge and belief</td>
                            </tr>
                            <tr>
                                <td><strong>Comments:</strong></td>
                                <td>{{shopInfo.RepresentativeComment}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="section">
                        <h3 style="text-align:center">Recommended By Credit [Finance]</h3>
                        <table>
                            <tr>
                                <td>Allow Credit Facility:</td>
                                <td class="checkbox-allow-credit">
                                    <span>Yes</span>
                                    <span>No</span>
                                    <span>Tk.</span>
                                </td>
                            </tr>
                        </table>

                        <table class="signature-table">
                            <tr>
                                <td class="signature-cell">
                                    <p style="text-align:center"><strong>Verified by</strong></p>
                                    <p>Meta Data Information: {{shopInfo.ShopAddress}} -{{shopInfo.EntryDate}}</p>
                                    <p>Name: {{shopInfo.AssignVroName}}</p>
                                    <p>Designation: <span class="underline"></span></p>
                                    <p>Date: <span class="underline"></span></p>
                                </td>
                                <td class="signature-cell">
                                    <p style="text-align:center"><strong>Vetted By</strong></p>
                                    <p>Signature: <span class="underline"></span></p>
                                    <p>Name: <span class="underline"></span></p>
                                    <p>Designation: <span class="underline"></span></p>
                                    <p>Date: <span class="underline"></span></p>
                                </td>
                                <td class="signature-cell">
                                    <p style="text-align:center"><strong>Approved By</strong></p>
                                    <p>Signature: <span class="underline"></span></p>
                                    <p>Name: <span class="underline"></span></p>
                                    <p>Designation: <span class="underline"></span></p>
                                    <p>Date: <span class="underline"></span></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Common} from "../../mixins/common";
import {baseurl} from "../../base_url";
import * as url from "url";

export default {
    mixins: [Common],
    data() {
        return {
            shopInfo: {},
            invoices: [],
            totalDiscountAmount: 0,
            totalAfterDiscountAmount: 0
        }
    },
    computed: {
        me() {
            return this.$store.state.me
        }
    },
    created() {
        this.getData()
    },
    mounted() {
        document.title = 'Shop Information Print';
    },
    methods: {
        getData() {
            this.axiosGet('reports/get-shop-information/'+this.$route.params.shopId,(response) => {
                this.shopInfo = response.data

                const typeOfEntity =  this.shopInfo.TypeOfEntity;
                var OwnerShip =  this.shopInfo.OwnerShip;
                const CustomerReputation =  this.shopInfo.CustomerReputation;
                const PaymentTermsInDays =  this.shopInfo.PaymentTermsInDays;
                const PaymentBehaviour =  this.shopInfo.PaymentBehaviour;
                const ModeOfPayment =  this.shopInfo.ModeOfPayment;
                const ShopPhoto =  this.shopInfo.ShopPhoto;
                const DeedAgreement =  this.shopInfo.DeedAgreement;
                const Condition =  this.shopInfo.Condition;
                if(ShopPhoto) {
                    var ShopPhotoStatus = 'Yes';
                }
                if(OwnerShip ==='Rented') {
                    OwnerShip = 'Rented Shop';
                }

                // Find and mark the selected option
                document.querySelectorAll('.checkbox-group span').forEach(span => {
                    if (span.textContent.trim() === typeOfEntity) {
                        span.classList.add('selected');
                    }
                    if (span.textContent.trim() === OwnerShip) {
                        span.classList.add('selected');
                    }
                    if (span.textContent.trim() === CustomerReputation) {
                        span.classList.add('selected');
                    }
                    if (span.textContent.trim() == (PaymentTermsInDays + ' Days')) {
                        span.classList.add('selected');
                    }
                    if (span.textContent.trim() === PaymentBehaviour) {
                        span.classList.add('selected');
                    }
                    if (span.textContent.trim() === ModeOfPayment) {
                        span.classList.add('selected');
                    }
                    if (span.textContent.trim() === ShopPhotoStatus) {
                        span.classList.add('selected');
                    }
                });
                document.querySelectorAll('.checkbox-group-deed-agreement span').forEach(span => {
                    console.log(span.textContent.trim(),DeedAgreement )
                    if (span.textContent.trim() === DeedAgreement) {
                        span.classList.add('selected');
                    }
                });
                document.querySelectorAll('.checkbox-group-outlet span').forEach(span => {
                    console.log(span.textContent.trim(),Condition )
                    if (span.textContent.trim() === Condition) {
                        span.classList.add('selected');
                    }
                });
                // setTimeout(() => {
                //     window.print()
                // },2000)
            },function(error) {
            });
        },
        inWords(num) {
            let a = ['', 'ONE ', 'TWO ', 'THREE ', 'FOUR ', 'FIVE ', 'SIX ', 'SEVEN ', 'EIGHT ', 'NINE ', 'TEN ', 'ELEVEN ', 'TWELVE ', 'THIRTEEN ', 'FOURTEEN ', 'FIFTEEN ', 'SIXTEEN ', 'SEVENTEEN ', 'EIGHTEEN ', 'NINETEEN '];
            let b = ['', '', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY'];


            if ((num = num.toString()).length > 9) return 'overflow';
            let n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return;
            let str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'CRORE ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'LAKH ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'THOUSAND ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'HUNDRED ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'AND ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'ONLY ' : '';
            return str;
        },

}
}
</script>

<style scoped>
#printDiv {
    padding: 15px;
}
.head {
    padding: 10px 0 0 30px;
}
.head p {
    margin: 0;
}
.r-info {
    margin: 40px 0;
}
.r-info p {
    margin: 0;
}
.preserve {
    font-size: 15px;
    color: #000000;
    margin: 30px 0 0 0 !important;
    text-transform: capitalize;
}
.note {
    margin-top: 10px;
    margin-bottom: 10px;
}
.bold {
    font-weight: bold;
    font-size: 14px;
}
.signature {
    margin-top: 50px;
}
.signature p {
    margin: 0;
}
.signature .pad {
    padding: 10px 0;
}
.col-md-1 {
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
}
.col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    position: relative;
    width: 100%;
}
.col-md-3 {
    flex: 0 0 25%;
    max-width: 25%;
    position: relative;
    width: 100%;
}
.col-md-2 {
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
}
.offset-4 {
    margin-left: 33.333333%;
}

body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 20px;
    color:#000000;
}
h1, h2 {
    text-align: center;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
table, th, td {
    border: 1px solid black;
}
th, td {
    padding: 8px;
    text-align: left;
}
.section {
    margin-bottom: 30px;
}
.checkbox-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f9f9f9;
    margin: 4px 0;
}
.checkbox-group span {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.checkbox-group span:before {
    content: "□";
    font-size: 16px;
    color: #555;
}
.checkbox-group-deed-agreement {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f9f9f9;
    margin: 4px 0;
}
.checkbox-group-deed-agreement span {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.checkbox-group-deed-agreement span:before {
    content: "□";
    font-size: 16px;
    color: #555;
}
.checkbox-group-deed-agreement span.selected {
    font-weight: bold;
    color: #0066cc;
}
.checkbox-group-deed-agreement span.selected:before {
    content: "✓";
    color: #0066cc;
}

.checkbox-group-outlet {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f9f9f9;
    margin: 4px 0;
}
.checkbox-group-outlet span {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.checkbox-group-outlet span:before {
    content: "□";
    font-size: 16px;
    color: #555;
}
.checkbox-group-outlet span.selected {
    font-weight: bold;
    color: #0066cc;
}
.checkbox-group-outlet span.selected:before {
    content: "✓";
    color: #0066cc;
}


.checkbox-allow-credit{
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f9f9f9;
    margin: 4px 0;
}
.checkbox-allow-credit span {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.checkbox-allow-credit span:before {
    content: "□";
    font-size: 16px;
    color: #555;
}
.signature-section {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}
.signature-box {
    width: 30%;
    text-align: center;
}
.signature-table {
    width: 100%;
    margin-top: 30px;
}
.signature-cell {
    width: 33%;
    vertical-align: top;
    padding: 10px;
}
.underline {
    border-bottom: 1px solid black;
    padding-bottom: 20px;
    margin-bottom: 5px;
    display: inline-block;
    min-width: 200px;
}
.checkbox-group span.selected {
    font-weight: bold;
    color: #0066cc;
}
.checkbox-group span.selected:before {
    content: "✓";
    color: #0066cc;
}
</style>

<style>
thead {
    background: #ffffff !important;
    color: #000000 !important;
}
.table-bordered thead th {
    font-weight: bold;
}
</style>
