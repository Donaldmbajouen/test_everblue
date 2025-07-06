<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.subscription.subscription_payment_receipt') }}</title>
    <style>
        @page {
            margin: 20px 0;
        }

        .currency-symbol {
            font-family: DejaVu Sans, Poppins, Nimbus Sans L, simsun, "Helvetica", Arial, "Liberation Sans", sans-serif !important;
        }

        .company-logo {
            text-align: center;
            display: block;
            height: 40px !important;
            width: 100%;
        }

        .company-logo img {
            padding: 0 20px;
            background-color: white;
            height: 100% !important;
        }

        .text-center {
            text-align: center;
        }

        .fs-16 {
            font-size: 16px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .bg-light {
            background-color: #F5F8FA !important;
        }

        .card {
            margin: 0 auto;
            border-radius: 10px;
            border: 1px solid #DDE0E4;
            padding: 10px;
        }

        .w-100 {
            width: 100%
        }

        .text-end {
            text-align: right;
        }

        tbody tr td {
            font-size: 13px;
            font-weight: normal !important
        }

        tfoot {
            border-top: 1px solid #DDE0E4 !important;
        }

        tfoot tr td {
            font-size: 13px;
        }

        .text-gray {
            color: #3F4254 !important;
        }

        .text-gray-100 {
            color: #747685 !important;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .text-primary {
            color: #4F158C;
        }

        .fs-14 {
            font-size: 14px;
        }

        .fe-bold {
            font-weight: 600;
        }

        .fs-20 {
            font-size: 20px;
        }

        .px-40 {
            padding-left: 40px;
            padding-right: 40px;
        }

        .w-100 {
            width: 100%;
        }

        .w-50 {
            width: 50%;
        }

        .border-left-gray {
            border-left: 1px solid #DDE0E4;
        }

        .mb-2 {
            margin-bottom: 4px !important;
        }

        span {
            margin-bottom: 3px;
        }

        .p-0 {
            padding: 0px !important
        }

        .m-4 {
            margin: 4px !important
        }

        .mt-10 {
            margin-top: 10px !important
        }

        .mb-10 {
            margin-bottom: 10px !important
        }


        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 3px;
        }
    </style>
</head>

<body>
    <div class="mb-20 company-logo">
        <img src="{{ asset(getAppLogo()) }}" alt="{{ getAppName() }}" class="object-fit-contain" />
    </div>
    <table align="center" class="w-50">
        <tr align="center">
            <th class="p-0 m-4 fs-16" align="center"> {{ getAppName() }}</th>
        </tr>
    </table>
    <br>

    <div class="">
        <table class="table w-100">
            <tr>
                <td class="px-40 w-50" style="vertical-align:top;">
                    <div class="" style="vertical-align:top;">
                        <h3 class="mb-2">{{ __('messages.subscription.user_details') }}</h3>
                        <table>
                            <tr class="mb-3">
                                <td class="fs-13 fw-bold">{{ __('messages.common.name') }}</td>
                                <td>:</td>
                                <td class="fs-13 text-gray">{{ $subscription->user->name }}</td>
                            </tr>
                            <tr class="mb-3">
                                <td class="fs-13 fw-bold">{{ __('messages.user.email') }}</td>
                                <td>:</td>
                                <td class="fs-13 text-gray">{{ $subscription->user->email }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="px-40 w-50 border-left-gray" style="vertical-align:top;">
                    <div class="" style="vertical-align:top;">
                        <h3 class="mb-2">{{ __('messages.subscription.payment_details') }}</h3>
                        <table>
                            <tr>
                                <td class="fs-13 fw-bold">{{ __('messages.subscription.paid_amount') }}</td>
                                <td>:</td>
                                <td class="fs-13 text-gray currency-symbol">
                                    {{ $subscription->plan->currency->symbol }}
                                    {{ $subscription->payable_amount ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="fs-13 fw-bold">{{ __('messages.subscription.paid_on') }}</td>
                                <td>:</td>
                                <td class="fs-13 text-gray">
                                    {{ date('d/m/Y', strtotime($subscription->transaction_date)) }}</td>
                            </tr>
                            <tr>
                                <td class="fs-13 fw-bold">{{ __('messages.subscription.payment_type') }}</td>
                                <td>:</td>
                                <td class="fs-13 text-gray">
                                    {{ __(\App\Models\Subscription::PAYMENT_TYPES[$subscription->payment_type]) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <br>
    <div class="px-40">
        <div class="card">
            <table class="table mb-0 w-100">
                <tbody>
                    <tr>
                        <td class="">
                            <div class="">
                                <h3 class="pt-0 mt-0 mb-2">{{ __('messages.subscription.plan_details') }}</h3>
                                <table>
                                    <tr>
                                        <td class="fs-13 fw-bold">{{ __('messages.plan.plan') }}</td>
                                        <td>:</td>

                                        <td class="fs-13 text-gray">{{ $subscription->plan->name . ' / ' }}
                                            {{ $subscription->trial_ends_at ? __('messages.subscription.trial_plan') : __(\App\Enums\PlanFrequency::from($subscription->plan_frequency)->getLabel()) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fs-13 fw-bold">{{ __('messages.subscription.start_date') }}</td>
                                        <td>:</td>
                                        <td class="fs-13 text-gray">
                                            {{ date('d/m/Y', strtotime($subscription->starts_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fs-13 fw-bold">{{ __('messages.subscription.end_date') }}</td>
                                        <td>:</td>
                                        <td class="fs-13 text-gray">
                                            {{ date('d/m/Y', strtotime($subscription->ends_at)) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-100 fw-bold">{{ __('messages.plan.plan_amount') }}</td>
                        <td></td>
                        <td class="text-end currency-symbol">{{ $subscription->plan->currency->symbol }}
                            {{ $subscription->plan_amount }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-gray-100 fw-bold">{{ __('messages.subscription.payable_amount') }}</td>
                        <td></td>
                        <td class="text-end currency-symbol">{{ $subscription->plan->currency->symbol }}
                            {{ $subscription->payable_amount ?? 0 }}</td>
                    </tr>
                </tfoot>
                <tfoot>
                    <tr>
                        <td class="fs-14 fw-bold">{{ __('messages.subscription.grand_total') }}</td>
                        <td></td>
                        <td class="text-end fs-14 fw-bold currency-symbol">
                            {{ $subscription->plan->currency->symbol }} {{ $subscription->payable_amount ?? 0 }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
