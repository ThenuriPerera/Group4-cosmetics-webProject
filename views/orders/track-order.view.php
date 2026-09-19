<?php
if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}
?>

<!-- Leaflet Map -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
/* ============================================================
   SMART ORDER TRACKING
   ============================================================ */

.orders-page {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
}

.order-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 30px;
    margin-bottom: 35px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
    border: 1px solid #eeeeee;
}

.order-header {
    background: linear-gradient(135deg, #fff4f8, #f9f4ff);
    padding: 22px;
    border-radius: 18px;
    margin-bottom: 25px;
}

.order-header p {
    margin: 8px 0;
    color: #444;
}

.order-header strong {
    color: #222;
}

/* ============================================================
   SMART TRACKING
   ============================================================ */

.smart-tracking {
    margin-top: 25px;
}

.smart-tracking h3 {
    font-size: 25px;
    margin-bottom: 8px;
    color: #222;
}

.live-badge {
    display: inline-block;
    padding: 7px 13px;
    border-radius: 20px;
    background: #e8fff1;
    color: #168044;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 18px;
}

.live-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #20b866;
    border-radius: 50%;
    margin-right: 6px;
    animation: livePulse 1.5s infinite;
}

@keyframes livePulse {
    0% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: 0.4;
        transform: scale(1.4);
    }

    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.tracking-number {
    margin-bottom: 30px;
    color: #555;
}

/* ============================================================
   PROGRESS
   ============================================================ */

.tracking-progress {
    position: relative;
    margin: 40px 10px 45px;
}

.progress-line {
    position: absolute;
    top: 24px;
    left: 5%;
    right: 5%;
    height: 5px;
    background: #e5e5e5;
    border-radius: 10px;
    z-index: 1;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #e83e8c, #9b59b6);
    border-radius: 10px;
    transition: width 0.8s ease;
}

.tracking-steps {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
}

.tracking-step {
    width: 16%;
    text-align: center;
    color: #999;
    font-size: 12px;
    font-weight: 600;
}

.step-circle {
    width: 48px;
    height: 48px;
    margin: 0 auto 10px;
    border-radius: 50%;
    background: #eeeeee;
    color: #888;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    border: 4px solid #ffffff;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
}

.tracking-step.active {
    color: #d63384;
}

.tracking-step.active .step-circle {
    background: linear-gradient(135deg, #e83e8c, #9b59b6);
    color: white;
}

.tracking-step.current .step-circle {
    animation: currentPulse 1.6s infinite;
}

@keyframes currentPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(232, 62, 140, 0.4);
    }

    70% {
        box-shadow: 0 0 0 12px rgba(232, 62, 140, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(232, 62, 140, 0);
    }
}

/* ============================================================
   CURRENT STATUS
   ============================================================ */

.current-status {
    margin-top: 25px;
    padding: 22px;
    border-radius: 18px;
    background: linear-gradient(135deg, #fff5fa, #f8f1ff);
    border: 1px solid #f0d9e7;
}

.current-status h4 {
    margin: 0 0 8px;
    color: #555;
}

.current-status p {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #d63384;
}

.smart-message {
    margin-top: 12px;
    color: #555;
    font-size: 15px;
}

/* ============================================================
   SHIPMENT INFO
   ============================================================ */

.shipment-info {
    margin-top: 20px;
    padding: 20px;
    background: #fafafa;
    border-radius: 16px;
}

.shipment-info p {
    margin: 9px 0;
}

/* ============================================================
   GPS MAP
   ============================================================ */

.gps-tracking {
    margin-top: 28px;
    padding: 24px;
    border-radius: 20px;
    background: #f8fbff;
    border: 1px solid #dceeff;
}

.gps-tracking h4 {
    margin-top: 0;
    font-size: 21px;
    color: #222;
}

.gps-status {
    color: #168044;
    font-weight: 700;
}

.gps-live-status {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px 0 18px;
    color: #168044;
    font-weight: 700;
}

.map-wrapper {
    position: relative;
    margin-top: 20px;
    overflow: hidden;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.courier-map {
    width: 100%;
    height: 430px;
    z-index: 1;
}

.map-overlay {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 500;
    background: rgba(255, 255, 255, 0.95);
    padding: 10px 14px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
    font-size: 13px;
    font-weight: 700;
}

.map-location-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 15px;
}

.location-box {
    background: white;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #e7e7e7;
}

.location-box span {
    display: block;
    color: #888;
    font-size: 12px;
    margin-bottom: 5px;
}

.location-box strong {
    font-size: 14px;
    color: #222;
}

.map-updated {
    margin-top: 12px;
    font-size: 13px;
    color: #777;
}

.map-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}

/* ============================================================
   BUTTONS
   ============================================================ */

.btn {
    display: inline-block;
    padding: 11px 18px;
    background: #222;
    color: white !important;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.btn:hover {
    opacity: 0.9;
}

.map-btn {
    background: #d63384;
}

/* ============================================================
   HISTORY
   ============================================================ */

.tracking-history {
    margin-top: 30px;
}

.tracking-history h4 {
    font-size: 20px;
    margin-bottom: 15px;
}

.history-timeline {
    list-style: none;
    padding: 0;
    margin: 0;
}

.history-timeline li {
    position: relative;
    padding: 14px 15px 14px 25px;
    margin-bottom: 8px;
    background: #fafafa;
    border-left: 4px solid #d63384;
    border-radius: 8px;
}

.history-timeline li strong {
    color: #333;
}

.history-timeline li span {
    color: #777;
    font-size: 13px;
}

/* ============================================================
   EMPTY STATE
   ============================================================ */

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

/* ============================================================
   MOBILE
   ============================================================ */

@media (max-width: 768px) {

    .order-card {
        padding: 18px;
    }

    .tracking-step {
        font-size: 9px;
    }

    .step-circle {
        width: 38px;
        height: 38px;
        font-size: 12px;
    }

    .progress-line {
        top: 19px;
    }

    .courier-map {
        height: 330px;
    }

    .map-location-info {
        grid-template-columns: 1fr;
    }
}
</style>


<section class="orders-page">

    <?php if (isset($_GET['paid'])): ?>

        <p class="success">
            Payment confirmed — thank you!
        </p>

    <?php endif; ?>


    <?php foreach ($orders as $order): ?>

        <?php

        $orderId = (int) $order['order_id'];

        $shipment = $shipmentByOrder[$orderId] ?? null;

        $history = $historyByOrder[$orderId] ?? [];


        /*
        |--------------------------------------------------------------------------
        | Delivery status
        |--------------------------------------------------------------------------
        */

        $deliveryStatus = strtolower(
            trim($shipment['delivery_status'] ?? '')
        );


        /*
        |--------------------------------------------------------------------------
        | Six tracking stages
        |--------------------------------------------------------------------------
        */

        if ($deliveryStatus === 'delivered') {

            $currentStep = 6;

        } elseif (
            $deliveryStatus === 'out for delivery' ||
            $deliveryStatus === 'out_for_delivery'
        ) {

            $currentStep = 5;

        } elseif (
            $deliveryStatus === 'in transit' ||
            $deliveryStatus === 'in_transit'
        ) {

            $currentStep = 4;

        } elseif ($deliveryStatus === 'shipped') {

            $currentStep = 3;

        } elseif ($deliveryStatus === 'processing') {

            $currentStep = 2;

        } else {

            $currentStep = 1;
        }


        $progressPercent = (($currentStep - 1) / 5) * 100;


        /*
        |--------------------------------------------------------------------------
        | GPS
        |--------------------------------------------------------------------------
        */

        $latitude = $shipment['current_latitude'] ?? null;

        $longitude = $shipment['current_longitude'] ?? null;

        $gpsUpdated = $shipment['location_updated_at'] ?? null;


        $hasGps =
            $latitude !== null &&
            $longitude !== null &&
            $latitude !== '' &&
            $longitude !== '';


        /*
        |--------------------------------------------------------------------------
        | Smart status message
        |--------------------------------------------------------------------------
        */

        $statusMessages = [

            1 => 'Your order has been received and is waiting to be processed.',

            2 => 'Your order is being prepared by our team.',

            3 => 'Your order has been handed over to the courier.',

            4 => 'Your package is currently on the way.',

            5 => 'Your courier is heading to your delivery address.',

            6 => 'Your order has been successfully delivered.'
        ];

        $smartMessage =
            $statusMessages[$currentStep]
            ?? 'Your order is being processed.';
        ?>


        <div
            class="order-card"
            data-order-id="<?= $orderId ?>"
        >

            <!-- =====================================================
                 ORDER HEADER
                 ====================================================== -->

            <div class="order-header">

                <p>
                    <strong>
                        Order #<?= $orderId ?>
                    </strong>
                </p>

                <p>
                    Placed:
                    <?= htmlspecialchars(
                        $order['order_date'] ?? ''
                    ) ?>
                </p>

                <p>
                    Order total:
                    <strong>
                        Rs.
                        <?= number_format(
                            (float)($order['total_amount'] ?? 0),
                            2
                        ) ?>
                    </strong>
                </p>

            </div>


            <?php if ($shipment): ?>

                <!-- =================================================
                     SMART TRACKING
                     ================================================== -->

                <div class="smart-tracking">

                    <h3>
                        Smart Order Tracking
                    </h3>


                    <div class="live-badge">

                        <span class="live-dot"></span>

                        LIVE TRACKING

                    </div>


                    <p class="tracking-number">

                        Tracking number:

                        <strong
                            class="js-tracking-number"
                        >
                            <?= htmlspecialchars(
                                $shipment['tracking_number']
                                ?? 'Not yet assigned'
                            ) ?>
                        </strong>

                    </p>


                    <!-- =================================================
                         PROGRESS
                         ================================================== -->

                    <div class="tracking-progress">

                        <div class="progress-line">

                            <div
                                class="progress-fill js-progress-fill"
                                style="width: <?= $progressPercent ?>%;"
                            ></div>

                        </div>


                        <div class="tracking-steps">

                            <!-- 1 -->

                            <div
                                class="tracking-step
                                <?= $currentStep >= 1 ? 'active' : '' ?>
                                <?= $currentStep === 1 ? 'current' : '' ?>"
                            >

                                <div class="step-circle">
                                    <?= $currentStep > 1 ? '✓' : '1' ?>
                                </div>

                                <span>
                                    Order Placed
                                </span>

                            </div>


                            <!-- 2 -->

                            <div
                                class="tracking-step
                                <?= $currentStep >= 2 ? 'active' : '' ?>
                                <?= $currentStep === 2 ? 'current' : '' ?>"
                            >

                                <div class="step-circle">
                                    <?= $currentStep > 2 ? '✓' : '2' ?>
                                </div>

                                <span>
                                    Processing
                                </span>

                            </div>


                            <!-- 3 -->

                            <div
                                class="tracking-step
                                <?= $currentStep >= 3 ? 'active' : '' ?>
                                <?= $currentStep === 3 ? 'current' : '' ?>"
                            >

                                <div class="step-circle">
                                    <?= $currentStep > 3 ? '✓' : '3' ?>
                                </div>

                                <span>
                                    Shipped
                                </span>

                            </div>


                            <!-- 4 -->

                            <div
                                class="tracking-step
                                <?= $currentStep >= 4 ? 'active' : '' ?>
                                <?= $currentStep === 4 ? 'current' : '' ?>"
                            >

                                <div class="step-circle">
                                    <?= $currentStep > 4 ? '✓' : '4' ?>
                                </div>

                                <span>
                                    In Transit
                                </span>

                            </div>


                            <!-- 5 -->

                            <div
                                class="tracking-step
                                <?= $currentStep >= 5 ? 'active' : '' ?>
                                <?= $currentStep === 5 ? 'current' : '' ?>"
                            >

                                <div class="step-circle">
                                    <?= $currentStep > 5 ? '✓' : '5' ?>
                                </div>

                                <span>
                                    Out for Delivery
                                </span>

                            </div>


                            <!-- 6 -->

                            <div
                                class="tracking-step
                                <?= $currentStep >= 6 ? 'active' : '' ?>
                                <?= $currentStep === 6 ? 'current' : '' ?>"
                            >

                                <div class="step-circle">
                                    <?= $currentStep >= 6 ? '✓' : '6' ?>
                                </div>

                                <span>
                                    Delivered
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         CURRENT STATUS
                         ================================================== -->

                    <div class="current-status">

                        <h4>
                            Current delivery status
                        </h4>

                        <p class="js-delivery-status">

                            📦
                            <?= htmlspecialchars(
                                $shipment['delivery_status']
                                ?? 'Processing'
                            ) ?>

                        </p>


                        <div class="smart-message js-smart-message">

                            <strong>
                                Smart update
                            </strong>

                            <br>

                            <?= htmlspecialchars(
                                $smartMessage
                            ) ?>

                        </div>

                    </div>


                    <!-- =================================================
                         COURIER INFORMATION
                         ================================================== -->

                    <div class="shipment-info">

                        <p>

                            🚚

                            <strong>
                                Courier
                            </strong>

                            <span class="js-courier">
                                <?= htmlspecialchars(
                                    $shipment['company_name']
                                    ?? 'Not assigned'
                                ) ?>
                            </span>

                        </p>


                        <?php if (!empty($shipment['contact_number'])): ?>

                            <p>

                                📞

                                <strong>
                                    Courier contact
                                </strong>

                                <span class="js-contact">
                                    <?= htmlspecialchars(
                                        $shipment['contact_number']
                                    ) ?>
                                </span>

                            </p>

                        <?php endif; ?>


                        <?php if (!empty($shipment['estimate_delivery'])): ?>

                            <p>

                                📅

                                <strong>
                                    Estimated delivery
                                </strong>

                                <span class="js-estimate">
                                    <?= htmlspecialchars(
                                        $shipment['estimate_delivery']
                                    ) ?>
                                </span>

                            </p>

                        <?php endif; ?>

                    </div>


                    <!-- =================================================
                         SMART LIVE MAP
                         ================================================== -->

                    <div class="gps-tracking">

                        <h4>
                            📍 Courier Location
                        </h4>


                        <?php if ($hasGps): ?>

                            <div class="gps-live-status">

                                <span class="live-dot"></span>

                                <span class="js-gps-status">
                                    Location available
                                </span>

                            </div>


                            <!-- MAP -->

                            <div class="map-wrapper">

                                <div class="map-overlay">

                                    🚚 Courier is here

                                </div>


                                <div
                                    id="courier-map-<?= $orderId ?>"
                                    class="courier-map"
                                    data-lat="<?= htmlspecialchars($latitude) ?>"
                                    data-lng="<?= htmlspecialchars($longitude) ?>"
                                ></div>

                            </div>


                            <!-- LOCATION DETAILS -->

                            <div class="map-location-info">

                                <div class="location-box">

                                    <span>
                                        Latitude
                                    </span>

                                    <strong
                                        class="js-latitude"
                                    >
                                        <?= htmlspecialchars($latitude) ?>
                                    </strong>

                                </div>


                                <div class="location-box">

                                    <span>
                                        Longitude
                                    </span>

                                    <strong
                                        class="js-longitude"
                                    >
                                        <?= htmlspecialchars($longitude) ?>
                                    </strong>

                                </div>

                            </div>


                            <p class="map-updated">

                                Last location update:

                                <strong class="js-gps-updated">

                                    <?= htmlspecialchars(
                                        $gpsUpdated
                                        ?? 'Waiting for update'
                                    ) ?>

                                </strong>

                            </p>


                            <div class="map-buttons">

                                <a
                                    class="btn map-btn js-google-map"
                                    href="https://www.google.com/maps?q=<?= urlencode($latitude . ',' . $longitude) ?>"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    📍 Open in Google Maps
                                </a>

                            </div>


                        <?php else: ?>

                            <p class="gps-status">

                                ⚪ Location not available yet

                            </p>

                            <p>

                                The courier's live location will
                                appear here when GPS tracking starts.

                            </p>


                            <div
                                id="courier-map-<?= $orderId ?>"
                                class="courier-map"
                                style="display:none;"
                            ></div>

                        <?php endif; ?>

                    </div>

                </div>


            <?php else: ?>


                <!-- =================================================
                     NO SHIPMENT
                     ================================================== -->

                <div class="shipment-info">

                    <p>
                        🚚
                        <strong>
                            Shipment tracking has not started yet.
                        </strong>
                    </p>

                    <p>
                        Tracking information will appear after
                        the editor creates the shipment.
                    </p>

                </div>

            <?php endif; ?>


            <!-- =====================================================
                 ORDER HISTORY
                 ====================================================== -->

            <div class="tracking-history">

                <h4>
                    📋 Order History
                </h4>


                <?php if (!empty($history)): ?>

                    <ul class="history-timeline">

                        <?php foreach ($history as $h): ?>

                            <li>

                                <strong>

                                    <?= htmlspecialchars(
                                        $h['order_history_status']
                                        ?? ''
                                    ) ?>

                                </strong>

                                <span>

                                    —
                                    <?= htmlspecialchars(
                                        $h['time_stamp']
                                        ?? ''
                                    ) ?>

                                </span>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                <?php else: ?>

                    <p>
                        No tracking history available yet.
                    </p>

                <?php endif; ?>

            </div>


            <!-- =====================================================
                 ACTIONS
                 ====================================================== -->

            <div class="order-actions">

                <a
                    class="btn"
                    href="<?= htmlspecialchars(
                        lg_url(
                            '/modules/orders/download-invoice.php?order_id='
                            . $orderId
                        )
                    ) ?>"
                    target="_blank"
                    rel="noopener"
                >
                    📄 Download Invoice
                </a>

            </div>

        </div>

    <?php endforeach; ?>


    <?php if (empty($orders)): ?>

        <div class="empty-state">

            <h2>
                Your next favourite is waiting.
            </h2>

            <p>
                Your orders will appear here after checkout.
            </p>

            <a
                class="btn"
                href="<?= htmlspecialchars(
                    lg_url('modules/products/index.php')
                ) ?>"
            >
                Explore the collection
            </a>

        </div>

    <?php endif; ?>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Status configuration
    |--------------------------------------------------------------------------
    */

    const statusSteps = {
        'pending': 1,
        'processing': 2,
        'shipped': 3,
        'in transit': 4,
        'in_transit': 4,
        'out for delivery': 5,
        'out_for_delivery': 5,
        'delivered': 6
    };


    const statusMessages = {

        'pending':
            'Your order has been received and is waiting to be processed.',

        'processing':
            'Your order is being prepared by our team.',

        'shipped':
            'Your order has been handed over to the courier.',

        'in transit':
            'Your package is currently on the way.',

        'in_transit':
            'Your package is currently on the way.',

        'out for delivery':
            'Your courier is heading to your delivery address.',

        'out_for_delivery':
            'Your courier is heading to your delivery address.',

        'delivered':
            'Your order has been successfully delivered.'
    };


    /*
    |--------------------------------------------------------------------------
    | Store maps
    |--------------------------------------------------------------------------
    */

    const maps = {};


    /*
    |--------------------------------------------------------------------------
    | Create map for every order
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.courier-map')
        .forEach(function (mapElement) {

            const orderCard =
                mapElement.closest('.order-card');

            if (!orderCard) {
                return;
            }


            const orderId =
                orderCard.dataset.orderId;

            const lat =
                parseFloat(mapElement.dataset.lat);

            const lng =
                parseFloat(mapElement.dataset.lng);


            /*
            | No GPS yet
            */

            if (
                isNaN(lat) ||
                isNaN(lng)
            ) {
                return;
            }


            /*
            | Create Leaflet map
            */

            const map =
                L.map(mapElement.id, {
                    zoomControl: true
                }).setView(
                    [lat, lng],
                    15
                );


            /*
            | OpenStreetMap tiles
            */

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,
                    attribution:
                        '&copy; OpenStreetMap contributors'
                }
            ).addTo(map);


            /*
            | Courier icon
            */

            const courierIcon =
                L.divIcon({

                    className: 'courier-marker',

                    html: `
                        <div style="
                            width:44px;
                            height:44px;
                            border-radius:50%;
                            background:#d63384;
                            border:4px solid white;
                            box-shadow:0 4px 15px rgba(0,0,0,.3);
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:22px;
                        ">
                            🚚
                        </div>
                    `,

                    iconSize: [44, 44],

                    iconAnchor: [22, 22]

                });


            /*
            | Add courier marker
            */

            const marker =
                L.marker(
                    [lat, lng],
                    {
                        icon: courierIcon
                    }
                )
                .addTo(map)
                .bindPopup(
                    '<strong>🚚 Courier Location</strong><br>Live location'
                );


            /*
            | Save map information
            */

            maps[orderId] = {

                map: map,

                marker: marker,

                lastLat: lat,

                lastLng: lng

            };


            /*
            | Fix map size after page rendering
            */

            setTimeout(function () {

                map.invalidateSize();

            }, 500);

        });


    /*
    |--------------------------------------------------------------------------
    | Update map location
    |--------------------------------------------------------------------------
    */

    function updateMap(
        orderId,
        latitude,
        longitude,
        updatedAt
    ) {

        const mapData =
            maps[orderId];

        if (!mapData) {
            return;
        }


        const lat =
            parseFloat(latitude);

        const lng =
            parseFloat(longitude);


        if (
            isNaN(lat) ||
            isNaN(lng)
        ) {
            return;
        }


        /*
        | Move marker smoothly
        */

        mapData.marker.setLatLng(
            [lat, lng]
        );


        /*
        | Move map to new location
        */

        mapData.map.panTo(
            [lat, lng],
            {
                animate: true,
                duration: 1
            }
        );


        /*
        | Update stored coordinates
        */

        mapData.lastLat = lat;

        mapData.lastLng = lng;


        /*
        | Update coordinate text
        */

        const card =
            document.querySelector(
                '.order-card[data-order-id="' +
                orderId +
                '"]'
            );


        if (!card) {
            return;
        }


        const latitudeElement =
            card.querySelector('.js-latitude');

        const longitudeElement =
            card.querySelector('.js-longitude');

        const updatedElement =
            card.querySelector('.js-gps-updated');

        const googleMap =
            card.querySelector('.js-google-map');


        if (latitudeElement) {

            latitudeElement.textContent =
                latitude;

        }


        if (longitudeElement) {

            longitudeElement.textContent =
                longitude;

        }


        if (
            updatedElement &&
            updatedAt
        ) {

            updatedElement.textContent =
                updatedAt;

        }


        if (googleMap) {

            googleMap.href =
                'https://www.google.com/maps?q=' +
                encodeURIComponent(
                    latitude + ',' + longitude
                );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Update delivery status
    |--------------------------------------------------------------------------
    */

    function updateStatus(
        orderId,
        shipment
    ) {

        if (!shipment) {
            return;
        }


        const card =
            document.querySelector(
                '.order-card[data-order-id="' +
                orderId +
                '"]'
            );


        if (!card) {
            return;
        }


        const rawStatus =
            String(
                shipment.delivery_status || 'Processing'
            );


        const normalizedStatus =
            rawStatus
                .toLowerCase()
                .trim();


        const currentStep =
            statusSteps[normalizedStatus] || 2;


        /*
        | Progress
        */

        const progress =
            ((currentStep - 1) / 5) * 100;


        const progressFill =
            card.querySelector(
                '.js-progress-fill'
            );


        if (progressFill) {

            progressFill.style.width =
                progress + '%';

        }


        /*
        | Current status
        */

        const statusElement =
            card.querySelector(
                '.js-delivery-status'
            );


        if (statusElement) {

            statusElement.textContent =
                '📦 ' + rawStatus;

        }


        /*
        | Smart message
        */

        const messageElement =
            card.querySelector(
                '.js-smart-message'
            );


        if (messageElement) {

            const message =
                statusMessages[normalizedStatus]
                ||
                'Your order is being processed.';


            messageElement.innerHTML =
                '<strong>Smart update</strong><br>' +
                message;

        }


        /*
        | Tracking number
        */

        const trackingNumber =
            card.querySelector(
                '.js-tracking-number'
            );


        if (
            trackingNumber &&
            shipment.tracking_number
        ) {

            trackingNumber.textContent =
                shipment.tracking_number;

        }


        /*
        | Courier
        */

        const courier =
            card.querySelector(
                '.js-courier'
            );


        if (
            courier &&
            shipment.company_name
        ) {

            courier.textContent =
                shipment.company_name;

        }


        /*
        | Courier contact
        */

        const contact =
            card.querySelector(
                '.js-contact'
            );


        if (
            contact &&
            shipment.contact_number
        ) {

            contact.textContent =
                shipment.contact_number;

        }


        /*
        | Estimated delivery
        */

        const estimate =
            card.querySelector(
                '.js-estimate'
            );


        if (
            estimate &&
            shipment.estimate_delivery
        ) {

            estimate.textContent =
                shipment.estimate_delivery;

        }


        /*
        | GPS
        */

        if (
            shipment.current_latitude !== null &&
            shipment.current_longitude !== null &&
            shipment.current_latitude !== '' &&
            shipment.current_longitude !== ''
        ) {

            updateMap(

                orderId,

                shipment.current_latitude,

                shipment.current_longitude,

                shipment.location_updated_at

            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Check server for latest order information
    |--------------------------------------------------------------------------
    */

    function refreshOrder(orderId) {

        fetch(
            '<?= htmlspecialchars(
                lg_url(
                    '/modules/orders/order-status.php'
                )
            ) ?>?order_id=' +
            encodeURIComponent(orderId) +
            '&_=' +
            Date.now(),

            {
                method: 'GET',

                cache: 'no-store',

                headers: {
                    'Accept':
                        'application/json'
                }
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Server error'
                );

            }

            return response.json();

        })

        .then(function (data) {

            if (
                data &&
                data.success &&
                data.shipment
            ) {

                updateStatus(
                    orderId,
                    data.shipment
                );

            }

        })

        .catch(function (error) {

            console.log(
                'Tracking update failed:',
                error
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Automatically refresh every 5 seconds
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.order-card[data-order-id]')
        .forEach(function (card) {

            const orderId =
                card.dataset.orderId;


            /*
            | First refresh
            */

            refreshOrder(orderId);


            /*
            | Live refresh
            */

            setInterval(
                function () {

                    refreshOrder(orderId);

                },
                5000
            );

        });

});
</script>