<?php

/*
|--------------------------------------------------------------------------
| SmartTrack helpers
|--------------------------------------------------------------------------
*/

function smarttrack_stage_from_order($order, $shipment)
{
    $orderStatus = $order['order_status'] ?? 'Pending';
    $deliveryStatus = $shipment['delivery_status'] ?? null;

    if ($orderStatus === 'Delivered' || $deliveryStatus === 'Delivered') {
        return 5;
    }

    if ($deliveryStatus === 'Out for Delivery') {
        return 4;
    }

    if ($deliveryStatus === 'In Transit') {
        return 3;
    }

    if (
        $deliveryStatus === 'Shipped' ||
        $orderStatus === 'Shipped'
    ) {
        return 2;
    }

    if ($orderStatus === 'Processing') {
        return 1;
    }

    return 0;
}


function smarttrack_status_text($order, $shipment)
{
    if (!empty($shipment['delivery_status'])) {
        return $shipment['delivery_status'];
    }

    return $order['order_status'] ?? 'Pending';
}


/*
|--------------------------------------------------------------------------
| Tracking stages
|--------------------------------------------------------------------------
*/

$trackingStages = [
    [
        'name' => 'Order Placed',
        'icon' => '♡',
        'description' => 'Your beauty order is confirmed'
    ],
    [
        'name' => 'Processing',
        'icon' => '✦',
        'description' => 'We are preparing your order'
    ],
    [
        'name' => 'Shipped',
        'icon' => '♢',
        'description' => 'Your package has left us'
    ],
    [
        'name' => 'In Transit',
        'icon' => '➜',
        'description' => 'Your package is on the way'
    ],
    [
        'name' => 'Out for Delivery',
        'icon' => '⌂',
        'description' => 'Almost at your doorstep'
    ],
    [
        'name' => 'Delivered',
        'icon' => '✓',
        'description' => 'Enjoy your Lumine Glow order'
    ]
];

?>

<style>

/* =========================================================
   LUMINE GLOW SMARTTRACK
   Premium cosmetics-inspired tracking interface
   ========================================================= */

.smarttrack-page {
    min-height: 100vh;
    padding: 55px 20px 80px;
    background:
        radial-gradient(
            circle at 10% 10%,
            rgba(255, 221, 231, 0.45),
            transparent 28%
        ),
        radial-gradient(
            circle at 90% 15%,
            rgba(245, 225, 239, 0.50),
            transparent 30%
        ),
        #fff9fb;
}

.smarttrack-container {
    max-width: 1180px;
    margin: 0 auto;
}


/* =========================================================
   HERO
   ========================================================= */

.smarttrack-hero {
    position: relative;
    overflow: hidden;
    padding: 48px 50px;
    margin-bottom: 30px;
    border-radius: 30px;
    background:
        linear-gradient(
            135deg,
            #fff0f4 0%,
            #fde7ef 48%,
            #f8e9f3 100%
        );
    border: 1px solid #f6dce6;
    box-shadow: 0 18px 50px rgba(177, 99, 128, 0.10);
}

.smarttrack-hero::before {
    content: "";
    position: absolute;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    right: -70px;
    top: -80px;
    background: rgba(255,255,255,0.45);
}

.smarttrack-hero::after {
    content: "✦";
    position: absolute;
    right: 100px;
    bottom: 25px;
    font-size: 70px;
    color: rgba(177, 99, 128, 0.12);
}

.smarttrack-eyebrow {
    position: relative;
    margin-bottom: 12px;
    color: #b05b78;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.smarttrack-hero h1 {
    position: relative;
    margin: 0;
    color: #3c2630;
    font-size: 42px;
    line-height: 1.15;
    font-weight: 800;
}

.smarttrack-hero p {
    position: relative;
    max-width: 650px;
    margin: 15px 0 0;
    color: #745761;
    font-size: 16px;
    line-height: 1.7;
}


/* =========================================================
   ORDER CARD
   ========================================================= */

.smarttrack-order {
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 30px;
    background: #ffffff;
    border: 1px solid #f3e1e7;
    box-shadow: 0 15px 45px rgba(150, 84, 111, 0.08);
}

.smarttrack-order-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 28px 32px;
    border-bottom: 1px solid #f7e9ed;
}

.smarttrack-order-label {
    margin-bottom: 5px;
    color: #a96a80;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.smarttrack-order-title {
    margin: 0;
    color: #3b2730;
    font-size: 25px;
    font-weight: 800;
}

.smarttrack-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 999px;
    background: #fff0f4;
    color: #ad5574;
    border: 1px solid #f5d5df;
    font-size: 13px;
    font-weight: 800;
}

.smarttrack-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #d87594;
    box-shadow: 0 0 0 4px rgba(216,117,148,0.12);
}


/* =========================================================
   PROGRESS AREA
   ========================================================= */

.smarttrack-progress-wrapper {
    padding: 38px 32px 25px;
}

.smarttrack-progress-heading {
    margin-bottom: 30px;
    text-align: center;
}

.smarttrack-progress-heading h3 {
    margin: 0 0 7px;
    color: #3b2730;
    font-size: 20px;
}

.smarttrack-progress-heading p {
    margin: 0;
    color: #9a7a85;
    font-size: 13px;
}


/* Progress bar */

.smarttrack-progress {
    position: relative;
    display: flex;
    justify-content: space-between;
    margin: 0 auto;
    max-width: 1050px;
}

.smarttrack-progress-line {
    position: absolute;
    top: 29px;
    left: 8%;
    right: 8%;
    height: 5px;
    overflow: hidden;
    border-radius: 20px;
    background: #f2e7eb;
}

.smarttrack-progress-fill {
    height: 100%;
    border-radius: 20px;
    background: linear-gradient(
        90deg,
        #d87594,
        #c76b91,
        #b86d9b
    );
    transition: width 0.6s ease;
}

.smarttrack-stage {
    position: relative;
    z-index: 2;
    width: 16.66%;
    text-align: center;
}

.smarttrack-stage-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 58px;
    height: 58px;
    margin: 0 auto 13px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #f0e3e8;
    color: #b99da8;
    font-size: 21px;
    box-shadow: 0 5px 18px rgba(157, 91, 118, 0.08);
    transition: all 0.3s ease;
}

.smarttrack-stage.completed .smarttrack-stage-circle {
    background: #c96f8e;
    border-color: #c96f8e;
    color: #ffffff;
    box-shadow:
        0 8px 20px rgba(201,111,142,0.25),
        0 0 0 6px rgba(201,111,142,0.08);
}

.smarttrack-stage.active .smarttrack-stage-circle {
    background: #fff4f7;
    border-color: #cf7593;
    color: #b85678;
    box-shadow:
        0 8px 22px rgba(201,111,142,0.20),
        0 0 0 7px rgba(201,111,142,0.08);
}

.smarttrack-stage-name {
    display: block;
    color: #9a7b86;
    font-size: 12px;
    line-height: 1.35;
    font-weight: 700;
}

.smarttrack-stage.completed .smarttrack-stage-name,
.smarttrack-stage.active .smarttrack-stage-name {
    color: #4b303a;
}

.smarttrack-stage-description {
    display: block;
    max-width: 120px;
    margin: 6px auto 0;
    color: #b29ba3;
    font-size: 10px;
    line-height: 1.4;
}


/* =========================================================
   INFO CARDS
   ========================================================= */

.smarttrack-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    padding: 0 32px 32px;
}

.smarttrack-info-card {
    padding: 20px;
    border-radius: 18px;
    background: #fff8fa;
    border: 1px solid #f6e5ea;
}

.smarttrack-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    margin-bottom: 13px;
    border-radius: 12px;
    background: #fde9ef;
    color: #bd607f;
    font-size: 17px;
}

.smarttrack-info-card small {
    display: block;
    margin-bottom: 6px;
    color: #a98b95;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.smarttrack-info-card strong {
    display: block;
    color: #49323b;
    font-size: 14px;
    word-break: break-word;
}


/* =========================================================
   LIVE LOCATION
   ========================================================= */

.smarttrack-location-card {
    margin: 0 32px 32px;
    overflow: hidden;
    border-radius: 24px;
    border: 1px solid #f1dfe6;
    background: #fffafb;
}

.smarttrack-location-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 22px 24px;
    border-bottom: 1px solid #f3e5e9;
}

.smarttrack-location-title {
    display: flex;
    align-items: center;
    gap: 13px;
}

.smarttrack-location-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: #fce8ef;
    color: #b95777;
    font-size: 20px;
}

.smarttrack-location-title h3 {
    margin: 0 0 3px;
    color: #432c35;
    font-size: 17px;
}

.smarttrack-location-title p {
    margin: 0;
    color: #a38891;
    font-size: 12px;
}

.smarttrack-live-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #fff0f3;
    color: #bd5e7c;
    font-size: 11px;
    font-weight: 800;
}

.smarttrack-live-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #d66f8d;
    animation: smarttrackPulse 1.7s infinite;
}

@keyframes smarttrackPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(214,111,141,0.45);
    }

    70% {
        box-shadow: 0 0 0 8px rgba(214,111,141,0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(214,111,141,0);
    }
}

.smarttrack-map {
    width: 100%;
    height: 430px;
    background: #f8f1f3;
}

.smarttrack-map iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: 0;
}

.smarttrack-no-location {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    padding: 30px;
    text-align: center;
    color: #9a7d87;
    background:
        radial-gradient(
            circle,
            #fff5f8,
            #fdf5f7
        );
}

.smarttrack-no-location-inner {
    max-width: 380px;
}

.smarttrack-no-location-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: #fde8ef;
    color: #bf6482;
    font-size: 28px;
}

.smarttrack-no-location strong {
    display: block;
    margin-bottom: 8px;
    color: #543641;
    font-size: 16px;
}

.smarttrack-no-location p {
    margin: 0;
    font-size: 13px;
    line-height: 1.6;
}


/* =========================================================
   DETAILS
   ========================================================= */

.smarttrack-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    padding: 0 32px 32px;
}

.smarttrack-detail-box {
    padding: 25px;
    border-radius: 22px;
    background: #fff9fb;
    border: 1px solid #f4e3e8;
}

.smarttrack-detail-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.smarttrack-detail-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #fdeaf0;
    color: #bb607d;
}

.smarttrack-detail-heading h3 {
    margin: 0;
    color: #4a3039;
    font-size: 16px;
}

.smarttrack-detail-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 12px 0;
    border-bottom: 1px solid #f3e7eb;
}

.smarttrack-detail-row:last-child {
    border-bottom: 0;
}

.smarttrack-detail-row span:first-child {
    color: #a0848d;
    font-size: 12px;
}

.smarttrack-detail-row span:last-child {
    color: #503640;
    font-size: 13px;
    font-weight: 700;
    text-align: right;
}


/* =========================================================
   HISTORY
   ========================================================= */

.smarttrack-history-section {
    margin: 0 32px 32px;
    padding: 26px;
    border-radius: 22px;
    background: #fff9fb;
    border: 1px solid #f4e3e8;
}

.smarttrack-history-title {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.smarttrack-history-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: #fdeaf0;
    color: #bb607d;
}

.smarttrack-history-title h3 {
    margin: 0;
    color: #49313a;
    font-size: 17px;
}

.smarttrack-history {
    position: relative;
}

.smarttrack-history::before {
    content: "";
    position: absolute;
    top: 10px;
    bottom: 10px;
    left: 6px;
    width: 2px;
    background: #f1dce3;
}

.smarttrack-history-item {
    position: relative;
    display: flex;
    gap: 18px;
    padding: 10px 0 18px;
}

.smarttrack-history-dot {
    position: relative;
    z-index: 2;
    flex-shrink: 0;
    width: 14px;
    height: 14px;
    margin-top: 4px;
    border-radius: 50%;
    background: #d27894;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #f2d8e0;
}

.smarttrack-history-content strong {
    display: block;
    margin-bottom: 4px;
    color: #513640;
    font-size: 13px;
}

.smarttrack-history-content small {
    color: #a38a92;
    font-size: 11px;
}


/* =========================================================
   GPS MINI PANEL
   ========================================================= */

.smarttrack-gps-panel {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    margin-top: 18px;
}

.smarttrack-gps-item {
    padding: 13px 15px;
    border-radius: 14px;
    background: #ffffff;
    border: 1px solid #f0e3e8;
}

.smarttrack-gps-item small {
    display: block;
    margin-bottom: 4px;
    color: #a58b94;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.smarttrack-gps-item strong {
    color: #553641;
    font-size: 12px;
}


/* =========================================================
   ACTIONS
   ========================================================= */

.smarttrack-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 0 32px 35px;
}

.smarttrack-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 180px;
    padding: 13px 20px;
    border-radius: 14px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    transition: all .25s ease;
}

.smarttrack-btn-primary {
    color: #ffffff;
    background: linear-gradient(
        135deg,
        #c96d8c,
        #b75d80
    );
    box-shadow: 0 8px 20px rgba(185,93,128,.20);
}

.smarttrack-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(185,93,128,.28);
}

.smarttrack-btn-secondary {
    color: #9c536e;
    background: #fff4f7;
    border: 1px solid #f1d6df;
}

.smarttrack-btn-secondary:hover {
    background: #fdeaf0;
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.smarttrack-empty {
    padding: 70px 30px;
    text-align: center;
    border-radius: 30px;
    background: #ffffff;
    border: 1px solid #f3e1e7;
    box-shadow: 0 15px 40px rgba(150,84,111,.08);
}

.smarttrack-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: #fdeaf0;
    color: #bf6583;
    font-size: 30px;
}

.smarttrack-empty h2 {
    margin: 0 0 10px;
    color: #4b3039;
}

.smarttrack-empty p {
    margin: 0;
    color: #9a7d86;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 950px) {

    .smarttrack-info-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .smarttrack-stage-description {
        display: none;
    }

    .smarttrack-gps-panel {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 700px) {

    .smarttrack-page {
        padding: 30px 12px 50px;
    }

    .smarttrack-hero {
        padding: 32px 24px;
        border-radius: 24px;
    }

    .smarttrack-hero h1 {
        font-size: 30px;
    }

    .smarttrack-order {
        border-radius: 22px;
    }

    .smarttrack-order-top {
        align-items: flex-start;
        flex-direction: column;
        padding: 23px;
    }

    .smarttrack-progress-wrapper {
        padding: 30px 15px 20px;
        overflow-x: auto;
    }

    .smarttrack-progress {
        min-width: 720px;
    }

    .smarttrack-info-grid {
        grid-template-columns: 1fr;
        padding: 0 20px 25px;
    }

    .smarttrack-location-card {
        margin: 0 20px 25px;
    }

    .smarttrack-location-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .smarttrack-map {
        height: 330px;
    }

    .smarttrack-details {
        grid-template-columns: 1fr;
        padding: 0 20px 25px;
    }

    .smarttrack-history-section {
        margin: 0 20px 25px;
    }

    .smarttrack-actions {
        padding: 0 20px 28px;
    }

    .smarttrack-btn {
        width: 100%;
    }

}


@media (max-width: 430px) {

    .smarttrack-hero p {
        font-size: 14px;
    }

    .smarttrack-progress {
        min-width: 650px;
    }

    .smarttrack-map {
        height: 290px;
    }

}

</style>


<div class="smarttrack-page">

    <div class="smarttrack-container">


        <!-- =================================================
             HERO
             ================================================= -->

        <section class="smarttrack-hero">

            <div class="smarttrack-eyebrow">
                ✦ Lumine Glow SmartTrack
            </div>

            <h1>
                Your beauty journey,<br>
                beautifully tracked.
            </h1>

            <p>
                Follow your Lumine Glow order from our beauty studio
                to your doorstep with live delivery updates.
            </p>

        </section>


        <?php if (empty($orders)): ?>

            <section class="smarttrack-empty">

                <div class="smarttrack-empty-icon">
                    ♡
                </div>

                <h2>
                    No orders to track
                </h2>

                <p>
                    Your Lumine Glow orders will appear here once you place
                    an order.
                </p>

            </section>

        <?php else: ?>


            <?php foreach ($orders as $order): ?>

                <?php

                $orderId = (int) $order['order_id'];

                $shipment =
                    $shipmentByOrder[$orderId] ?? null;

                $history =
                    $historyByOrder[$orderId] ?? [];

                $currentStage =
                    smarttrack_stage_from_order(
                        $order,
                        $shipment
                    );

                $statusText =
                    smarttrack_status_text(
                        $order,
                        $shipment
                    );

                $trackingNumber =
                    $shipment['tracking_number']
                    ?? 'Not assigned';

                $courierName =
                    $shipment['company_name']
                    ?? 'Not assigned';

                $courierPhone =
                    $shipment['contact_number']
                    ?? 'Not available';

                $estimate =
                    $shipment['estimate_delivery']
                    ?? null;

                $latitude =
                    $shipment['current_latitude']
                    ?? null;

                $longitude =
                    $shipment['current_longitude']
                    ?? null;

                $locationUpdated =
                    $shipment['location_updated_at']
                    ?? '';

                $hasLocation =
                    $latitude !== null &&
                    $latitude !== '' &&
                    $longitude !== null &&
                    $longitude !== '';


                /*
                |--------------------------------------------------------------------------
                | Progress percentage
                |--------------------------------------------------------------------------
                */

                $progressPercentage =
                    ($currentStage / 5) * 100;


                /*
                |--------------------------------------------------------------------------
                | OpenStreetMap
                |--------------------------------------------------------------------------
                */

                $mapUrl = '';

                if ($hasLocation) {

                    $lat = (float) $latitude;
                    $lng = (float) $longitude;

                    $delta = 0.025;

                    $bboxLeft =
                        $lng - $delta;

                    $bboxBottom =
                        $lat - $delta;

                    $bboxRight =
                        $lng + $delta;

                    $bboxTop =
                        $lat + $delta;

                    $mapUrl =
                        'https://www.openstreetmap.org/export/embed.html'
                        . '?bbox='
                        . rawurlencode(
                            $bboxLeft . ',' .
                            $bboxBottom . ',' .
                            $bboxRight . ',' .
                            $bboxTop
                        )
                        . '&layer=mapnik'
                        . '&marker='
                        . rawurlencode(
                            $lat . ',' . $lng
                        );
                }

                ?>


                <article class="smarttrack-order">


                    <!-- =============================================
                         ORDER HEADER
                         ============================================= -->

                    <div class="smarttrack-order-top">

                        <div>

                            <div class="smarttrack-order-label">
                                Your order
                            </div>

                            <h2 class="smarttrack-order-title">
                                Order #<?= $orderId ?>
                            </h2>

                        </div>


                        <div class="smarttrack-status-pill">

                            <span class="smarttrack-status-dot"></span>

                            <?= htmlspecialchars($statusText) ?>

                        </div>

                    </div>


                    <!-- =============================================
                         PROGRESS
                         ============================================= -->

                    <div class="smarttrack-progress-wrapper">

                        <div class="smarttrack-progress-heading">

                            <h3>
                                Your order journey
                            </h3>

                            <p>
                                We are keeping your beauty essentials
                                moving towards you.
                            </p>

                        </div>


                        <div class="smarttrack-progress">

                            <div class="smarttrack-progress-line">

                                <div
                                    class="smarttrack-progress-fill"
                                    style="width: <?= $progressPercentage ?>%;">
                                </div>

                            </div>


                            <?php foreach (
                                $trackingStages
                                as $stageIndex => $stage
                            ): ?>

                                <?php

                                if ($stageIndex < $currentStage) {

                                    $stageClass = 'completed';

                                } elseif (
                                    $stageIndex === $currentStage
                                ) {

                                    $stageClass = 'active';

                                } else {

                                    $stageClass = '';

                                }

                                ?>


                                <div
                                    class="smarttrack-stage <?= $stageClass ?>">

                                    <div
                                        class="smarttrack-stage-circle">

                                        <?php if (
                                            $stageIndex < $currentStage
                                        ): ?>

                                            ✓

                                        <?php else: ?>

                                            <?= htmlspecialchars(
                                                $stage['icon']
                                            ) ?>

                                        <?php endif; ?>

                                    </div>


                                    <span class="smarttrack-stage-name">

                                        <?= htmlspecialchars(
                                            $stage['name']
                                        ) ?>

                                    </span>


                                    <span
                                        class="smarttrack-stage-description">

                                        <?= htmlspecialchars(
                                            $stage['description']
                                        ) ?>

                                    </span>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>


                    <!-- =============================================
                         QUICK INFORMATION
                         ============================================= -->

                    <div class="smarttrack-info-grid">


                        <div class="smarttrack-info-card">

                            <div class="smarttrack-info-icon">
                                ♢
                            </div>

                            <small>
                                Tracking Number
                            </small>

                            <strong>
                                <?= htmlspecialchars(
                                    $trackingNumber
                                ) ?>
                            </strong>

                        </div>


                        <div class="smarttrack-info-card">

                            <div class="smarttrack-info-icon">
                                ✦
                            </div>

                            <small>
                                Delivery Partner
                            </small>

                            <strong>
                                <?= htmlspecialchars(
                                    $courierName
                                ) ?>
                            </strong>

                        </div>


                        <div class="smarttrack-info-card">

                            <div class="smarttrack-info-icon">
                                ♡
                            </div>

                            <small>
                                Estimated Delivery
                            </small>

                            <strong>

                                <?php if ($estimate): ?>

                                    <?= htmlspecialchars(
                                        date(
                                            'd M Y',
                                            strtotime($estimate)
                                        )
                                    ) ?>

                                <?php else: ?>

                                    Being calculated

                                <?php endif; ?>

                            </strong>

                        </div>


                        <div class="smarttrack-info-card">

                            <div class="smarttrack-info-icon">
                                ◷
                            </div>

                            <small>
                                Last Update
                            </small>

                            <strong>

                                <?php if ($locationUpdated): ?>

                                    <?= htmlspecialchars(
                                        date(
                                            'd M, h:i A',
                                            strtotime(
                                                $locationUpdated
                                            )
                                        )
                                    ) ?>

                                <?php else: ?>

                                    Waiting for GPS

                                <?php endif; ?>

                            </strong>

                        </div>

                    </div>


                    <!-- =============================================
                         LIVE LOCATION
                         ============================================= -->

                    <section class="smarttrack-location-card">


                        <div class="smarttrack-location-header">

                            <div class="smarttrack-location-title">

                                <div class="smarttrack-location-icon">
                                    ⌖
                                </div>

                                <div>

                                    <h3>
                                        Live delivery location
                                    </h3>

                                    <p>
                                        See where your order is right now
                                    </p>

                                </div>

                            </div>


                            <?php if ($hasLocation): ?>

                                <div class="smarttrack-live-badge">

                                    <span
                                        class="smarttrack-live-dot">
                                    </span>

                                    LIVE LOCATION

                                </div>

                            <?php else: ?>

                                <div class="smarttrack-live-badge">

                                    <span
                                        class="smarttrack-live-dot">
                                    </span>

                                    WAITING FOR GPS

                                </div>

                            <?php endif; ?>

                        </div>


                        <div class="smarttrack-map">


                            <?php if (
                                $hasLocation &&
                                $mapUrl
                            ): ?>

                                <iframe
                                    id="map-<?= $orderId ?>"
                                    title="Live courier location for order #<?= $orderId ?>"
                                    src="<?= htmlspecialchars(
                                        $mapUrl
                                    ) ?>"
                                    loading="eager"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>

                            <?php else: ?>

                                <div
                                    class="smarttrack-no-location"
                                    id="no-location-<?= $orderId ?>">

                                    <div
                                        class="smarttrack-no-location-inner">

                                        <div
                                            class="smarttrack-no-location-icon">
                                            ⌖
                                        </div>

                                        <strong>
                                            Your courier is getting ready
                                        </strong>

                                        <p>
                                            The live delivery map will
                                            appear automatically when the
                                            courier device sends its GPS
                                            location.
                                        </p>

                                    </div>

                                </div>

                            <?php endif; ?>


                        </div>


                        <?php if ($hasLocation): ?>

                            <div
                                class="smarttrack-gps-panel"
                                style="padding: 0 20px 20px;">

                                <div class="smarttrack-gps-item">

                                    <small>
                                        Latitude
                                    </small>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $latitude
                                        ) ?>
                                    </strong>

                                </div>


                                <div class="smarttrack-gps-item">

                                    <small>
                                        Longitude
                                    </small>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $longitude
                                        ) ?>
                                    </strong>

                                </div>


                                <div class="smarttrack-gps-item">

                                    <small>
                                        GPS Updated
                                    </small>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $locationUpdated
                                        ) ?>
                                    </strong>

                                </div>

                            </div>

                        <?php endif; ?>


                    </section>


                    <!-- =============================================
                         DELIVERY + GPS DETAILS
                         ============================================= -->

                    <div class="smarttrack-details">


                        <div class="smarttrack-detail-box">

                            <div
                                class="smarttrack-detail-heading">

                                <div
                                    class="smarttrack-detail-heading-icon">
                                    ♡
                                </div>

                                <h3>
                                    Delivery details
                                </h3>

                            </div>


                            <div class="smarttrack-detail-row">

                                <span>
                                    Courier
                                </span>

                                <span>
                                    <?= htmlspecialchars(
                                        $courierName
                                    ) ?>
                                </span>

                            </div>


                            <div class="smarttrack-detail-row">

                                <span>
                                    Contact
                                </span>

                                <span>
                                    <?= htmlspecialchars(
                                        $courierPhone
                                    ) ?>
                                </span>

                            </div>


                            <div class="smarttrack-detail-row">

                                <span>
                                    Tracking number
                                </span>

                                <span>
                                    <?= htmlspecialchars(
                                        $trackingNumber
                                    ) ?>
                                </span>

                            </div>


                            <div class="smarttrack-detail-row">

                                <span>
                                    Delivery status
                                </span>

                                <span>
                                    <?= htmlspecialchars(
                                        $statusText
                                    ) ?>
                                </span>

                            </div>


                            <div class="smarttrack-detail-row">

                                <span>
                                    Estimated arrival
                                </span>

                                <span>

                                    <?php if ($estimate): ?>

                                        <?= htmlspecialchars(
                                            date(
                                                'd M Y',
                                                strtotime($estimate)
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        Not available

                                    <?php endif; ?>

                                </span>

                            </div>

                        </div>


                        <div class="smarttrack-detail-box">

                            <div
                                class="smarttrack-detail-heading">

                                <div
                                    class="smarttrack-detail-heading-icon">
                                    ⌖
                                </div>

                                <h3>
                                    Live GPS
                                </h3>

                            </div>


                            <?php if ($hasLocation): ?>


                                <div class="smarttrack-detail-row">

                                    <span>
                                        Latitude
                                    </span>

                                    <span>
                                        <?= htmlspecialchars(
                                            $latitude
                                        ) ?>
                                    </span>

                                </div>


                                <div class="smarttrack-detail-row">

                                    <span>
                                        Longitude
                                    </span>

                                    <span>
                                        <?= htmlspecialchars(
                                            $longitude
                                        ) ?>
                                    </span>

                                </div>


                                <div class="smarttrack-detail-row">

                                    <span>
                                        Last updated
                                    </span>

                                    <span>
                                        <?= htmlspecialchars(
                                            $locationUpdated
                                        ) ?>
                                    </span>

                                </div>


                            <?php else: ?>

                                <p
                                    style="
                                        color:#9b7f88;
                                        font-size:13px;
                                        line-height:1.7;
                                        margin:0;
                                    ">

                                    Your courier's live GPS location
                                    has not been received yet.

                                </p>

                            <?php endif; ?>

                        </div>


                    </div>


                    <!-- =============================================
                         TRACKING HISTORY
                         ============================================= -->

                    <section
                        class="smarttrack-history-section">

                        <div class="smarttrack-history-title">

                            <div
                                class="smarttrack-history-title-icon">
                                ◷
                            </div>

                            <h3>
                                Your tracking journey
                            </h3>

                        </div>


                        <div class="smarttrack-history">


                            <?php if (!empty($history)): ?>


                                <?php foreach (
                                    $history as $historyItem
                                ): ?>


                                    <div
                                        class="smarttrack-history-item">

                                        <div
                                            class="smarttrack-history-dot">
                                        </div>


                                        <div
                                            class="smarttrack-history-content">

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $historyItem[
                                                        'order_history_status'
                                                    ]
                                                ) ?>

                                            </strong>


                                            <small>

                                                <?= htmlspecialchars(
                                                    date(
                                                        'd M Y, h:i A',
                                                        strtotime(
                                                            $historyItem[
                                                                'time_stamp'
                                                            ]
                                                        )
                                                    )
                                                ) ?>

                                            </small>

                                        </div>

                                    </div>


                                <?php endforeach; ?>


                            <?php else: ?>

                                <p
                                    style="
                                        color:#9b7f88;
                                        font-size:13px;
                                        margin:0;
                                    ">

                                    Your tracking history will appear
                                    here as your order moves forward.

                                </p>

                            <?php endif; ?>


                        </div>

                    </section>


                    <!-- =============================================
                         ACTION BUTTONS
                         ============================================= -->

                    <div class="smarttrack-actions">


                        <a
                            class="smarttrack-btn smarttrack-btn-primary"
                            href="<?= htmlspecialchars(
                                lg_url(
                                    '/modules/orders/view-order.php?order_id='
                                    . $orderId
                                )
                            ) ?>">

                            ♡
                            View Order Details

                        </a>


                        <a
                            class="smarttrack-btn smarttrack-btn-secondary"
                            href="<?= htmlspecialchars(
                                lg_url(
                                    '/modules/orders/track-order.php?order_id='
                                    . $orderId
                                )
                            ) ?>">

                            ↻
                            Refresh Tracking

                        </a>


                    </div>


                </article>


            <?php endforeach; ?>


        <?php endif; ?>


    </div>

</div>


<script>

/*
|--------------------------------------------------------------------------
| SmartTrack automatic refresh
|--------------------------------------------------------------------------
|
| The page refreshes every 30 seconds so the customer can receive
| the newest shipment/GPS information.
|--------------------------------------------------------------------------
*/

setTimeout(function () {

    window.location.reload();

}, 30000);

</script>