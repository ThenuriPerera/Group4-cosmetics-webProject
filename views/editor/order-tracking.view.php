<?php
/**
 * Luminé Glow - SmartTrack Editor Operations Center
 *
 * Editor features:
 * - Update order status
 * - Update delivery status
 * - Select courier
 * - Set tracking number
 * - Set estimated delivery
 * - Start live browser GPS tracking
 * - Stop GPS tracking
 * - Automatically send GPS coordinates to server
 * - Display current GPS coordinates
 * - Display last GPS update
 */
?>

<style>
/* =========================================================
   LUMINE GLOW SMARTTRACK
   ========================================================= */

.lg-tracking-page {
    min-height: 100vh;
    padding: 35px 35px 60px;
    background:
        radial-gradient(circle at top right, rgba(236, 208, 225, 0.35), transparent 30%),
        radial-gradient(circle at bottom left, rgba(222, 214, 239, 0.25), transparent 28%),
        #faf8fb;
    color: #29232b;
}

.lg-container {
    max-width: 1450px;
    margin: 0 auto;
}

/* =========================================================
   TOP HEADER
   ========================================================= */

.lg-topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 25px;
    margin-bottom: 30px;
}

.lg-title-area {
    display: flex;
    gap: 18px;
    align-items: center;
}

.lg-icon-box {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    background: linear-gradient(135deg, #f3dce9, #e4d9f0);
    box-shadow: 0 10px 25px rgba(111, 75, 105, 0.12);
}

.lg-eyebrow {
    margin: 0 0 5px;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 11px;
    font-weight: 700;
    color: #98758e;
}

.lg-title {
    margin: 0;
    font-size: 34px;
    font-weight: 700;
    letter-spacing: -1px;
    color: #2c252d;
}

.lg-subtitle {
    margin: 7px 0 0;
    color: #857b84;
    font-size: 14px;
}

.lg-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 18px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.85);
    color: #514650;
    text-decoration: none;
    border: 1px solid #eee5ed;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s ease;
}

.lg-back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(74, 51, 70, 0.08);
}

/* =========================================================
   ALERTS
   ========================================================= */

.lg-alert {
    padding: 15px 18px;
    border-radius: 14px;
    margin-bottom: 22px;
    font-size: 14px;
    font-weight: 600;
}

.lg-success {
    background: #edf8f1;
    color: #286542;
    border: 1px solid #d8efdf;
}

.lg-error {
    background: #fff0f1;
    color: #a23847;
    border: 1px solid #f3d8dc;
}

/* =========================================================
   STATISTICS
   ========================================================= */

.lg-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 30px;
}

.lg-stat {
    position: relative;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid #eee7ed;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 8px 30px rgba(60, 39, 56, 0.05);
}

.lg-stat::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    right: -30px;
    top: -30px;
    background: #f5e8f0;
}

.lg-stat-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: #f7edf3;
    font-size: 19px;
    margin-bottom: 15px;
}

.lg-stat-label {
    color: #8a8089;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.lg-stat-number {
    margin-top: 5px;
    font-size: 26px;
    font-weight: 700;
    color: #30282f;
}

/* =========================================================
   TOOLBAR
   ========================================================= */

.lg-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}

.lg-search-box {
    position: relative;
    flex: 1;
    max-width: 430px;
}

.lg-search-box span {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    opacity: 0.6;
}

.lg-search {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #ebe3e9;
    background: white;
    padding: 13px 15px 13px 43px;
    border-radius: 13px;
    outline: none;
    font-size: 14px;
    color: #40363f;
}

.lg-search:focus {
    border-color: #c8a9be;
    box-shadow: 0 0 0 3px rgba(200, 169, 190, 0.12);
}

.lg-filter {
    border: 1px solid #ebe3e9;
    background: white;
    border-radius: 13px;
    padding: 13px 15px;
    font-size: 14px;
    color: #514650;
    outline: none;
    cursor: pointer;
}

/* =========================================================
   ORDER GRID
   ========================================================= */

.lg-orders {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
}

.lg-order-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #eee7ed;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(60, 39, 56, 0.055);
    transition: 0.25s ease;
}

.lg-order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(60, 39, 56, 0.10);
}

/* =========================================================
   ORDER HEADER
   ========================================================= */

.lg-order-header {
    padding: 20px 22px;
    border-bottom: 1px solid #f0e9ef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.lg-order-number {
    font-size: 17px;
    font-weight: 700;
    color: #30282f;
}

.lg-order-date {
    margin-top: 5px;
    font-size: 12px;
    color: #978c95;
}

/* =========================================================
   STATUS BADGES
   ========================================================= */

.lg-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.lg-status::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.lg-status-pending {
    background: #fff6df;
    color: #9a741c;
}

.lg-status-processing {
    background: #eee9ff;
    color: #6952a8;
}

.lg-status-shipped {
    background: #eaf4ff;
    color: #3971a7;
}

.lg-status-delivered {
    background: #eaf8ef;
    color: #398055;
}

.lg-status-cancelled {
    background: #fff0f1;
    color: #a54855;
}

/* =========================================================
   CUSTOMER
   ========================================================= */

.lg-customer {
    padding: 20px 22px 5px;
    display: flex;
    align-items: center;
    gap: 13px;
}

.lg-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e8d3e1, #ddd6ed);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #624d5d;
    font-weight: 700;
    font-size: 16px;
}

.lg-customer-name {
    font-weight: 700;
    font-size: 14px;
    color: #3b323a;
}

.lg-customer-email {
    margin-top: 4px;
    font-size: 12px;
    color: #948991;
}

/* =========================================================
   ORDER INFORMATION
   ========================================================= */

.lg-info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    padding: 20px 22px;
}

.lg-info {
    background: #faf7fa;
    border-radius: 13px;
    padding: 12px;
}

.lg-info-label {
    display: block;
    color: #998f98;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 700;
    margin-bottom: 5px;
}

.lg-info-value {
    color: #3d343c;
    font-size: 13px;
    font-weight: 700;
}

/* =========================================================
   SMART GPS PANEL
   ========================================================= */

.lg-gps-panel {
    margin: 0 22px 18px;
    border: 1px solid #e7dce5;
    border-radius: 18px;
    background:
        linear-gradient(
            135deg,
            rgba(250, 241, 247, 0.95),
            rgba(246, 243, 250, 0.95)
        );
    overflow: hidden;
}

.lg-gps-header {
    padding: 16px 17px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #eadfe7;
}

.lg-gps-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.lg-gps-icon {
    width: 40px;
    height: 40px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ead9e6;
    font-size: 19px;
}

.lg-gps-heading {
    font-size: 14px;
    font-weight: 800;
    color: #41363f;
}

.lg-gps-subheading {
    margin-top: 3px;
    font-size: 11px;
    color: #8d808a;
}

.lg-gps-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 999px;
    background: #f0ebef;
    color: #81747e;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.lg-gps-badge.active {
    background: #e6f7ec;
    color: #287348;
}

.lg-gps-badge.active::before {
    content: "";
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #32a85c;
    box-shadow: 0 0 0 4px rgba(50, 168, 92, 0.12);
}

.lg-gps-content {
    padding: 16px;
}

.lg-gps-status {
    margin-bottom: 13px;
    padding: 11px 12px;
    border-radius: 11px;
    background: white;
    border: 1px solid #eee5eb;
    color: #756a73;
    font-size: 12px;
    line-height: 1.5;
}

.lg-gps-status.success {
    background: #f0faf3;
    border-color: #d9efdf;
    color: #2c7046;
}

.lg-gps-status.error {
    background: #fff2f3;
    border-color: #f0d9dd;
    color: #a34855;
}

.lg-gps-coordinates {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 13px;
}

.lg-gps-coordinate {
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid #eee6ec;
    border-radius: 11px;
    padding: 11px;
}

.lg-gps-coordinate-label {
    display: block;
    color: #998d96;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 800;
    margin-bottom: 4px;
}

.lg-gps-coordinate-value {
    color: #40363f;
    font-size: 12px;
    font-weight: 700;
    word-break: break-all;
}

.lg-gps-time {
    font-size: 10px;
    color: #958992;
    margin-bottom: 13px;
}

.lg-gps-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.lg-gps-start,
.lg-gps-stop {
    border: none;
    padding: 11px 12px;
    border-radius: 11px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 800;
    transition: 0.2s ease;
}

.lg-gps-start {
    background: linear-gradient(135deg, #6d5368, #92738a);
    color: white;
}

.lg-gps-start:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(109, 83, 104, 0.20);
}

.lg-gps-stop {
    background: #f4e9ee;
    color: #725a68;
    border: 1px solid #e5d8e0;
}

.lg-gps-stop:hover {
    background: #eadde5;
}

.lg-gps-start:disabled,
.lg-gps-stop:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* =========================================================
   UPDATE FORM
   ========================================================= */

.lg-update-area {
    margin: 0 22px 22px;
    border: 1px solid #eee6ec;
    border-radius: 17px;
    background: #fcfafc;
    overflow: hidden;
}

.lg-update-heading {
    padding: 14px 16px;
    border-bottom: 1px solid #eee6ec;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #776a74;
}

.lg-form {
    padding: 16px;
}

.lg-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 13px;
}

.lg-field.full {
    grid-column: 1 / -1;
}

.lg-field label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #766a73;
    margin-bottom: 6px;
}

.lg-field input,
.lg-field select {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #e5dde4;
    background: white;
    padding: 10px 11px;
    border-radius: 10px;
    font-size: 13px;
    color: #40363f;
    outline: none;
}

.lg-field input:focus,
.lg-field select:focus {
    border-color: #c3a4ba;
    box-shadow: 0 0 0 3px rgba(195, 164, 186, 0.10);
}

.lg-update-button {
    width: 100%;
    border: none;
    margin-top: 14px;
    padding: 12px;
    border-radius: 11px;
    cursor: pointer;
    color: white;
    background: linear-gradient(135deg, #6d5368, #92738a);
    font-size: 13px;
    font-weight: 700;
    transition: 0.2s ease;
}

.lg-update-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(109, 83, 104, 0.20);
}

/* =========================================================
   EMPTY
   ========================================================= */

.lg-empty {
    background: white;
    border: 1px solid #eee7ed;
    border-radius: 22px;
    padding: 70px 30px;
    text-align: center;
    box-shadow: 0 10px 35px rgba(60, 39, 56, 0.05);
}

.lg-empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    border-radius: 22px;
    background: #f6eaf2;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.lg-empty h3 {
    margin: 0;
    color: #3c323a;
    font-size: 20px;
}

.lg-empty p {
    color: #938891;
    font-size: 14px;
    margin-top: 8px;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1100px) {
    .lg-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .lg-orders {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .lg-tracking-page {
        padding: 22px 15px 40px;
    }

    .lg-topbar {
        flex-direction: column;
    }

    .lg-title {
        font-size: 27px;
    }

    .lg-stats {
        grid-template-columns: 1fr 1fr;
    }

    .lg-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .lg-search-box {
        max-width: none;
    }

    .lg-info-grid {
        grid-template-columns: 1fr;
    }

    .lg-form-grid {
        grid-template-columns: 1fr;
    }

    .lg-field.full {
        grid-column: auto;
    }

    .lg-gps-coordinates {
        grid-template-columns: 1fr;
    }

    .lg-gps-actions {
        grid-template-columns: 1fr;
    }
}
</style>


<div class="lg-tracking-page">

    <div class="lg-container">

        <!-- =====================================================
             HEADER
             ===================================================== -->

        <div class="lg-topbar">

            <div class="lg-title-area">

                <div class="lg-icon-box">
                    🚚
                </div>

                <div>

                    <p class="lg-eyebrow">
                        Operations Center
                    </p>

                    <h1 class="lg-title">
                        SmartTrack
                    </h1>

                    <p class="lg-subtitle">
                        Manage customer deliveries with live location tracking.
                    </p>

                </div>

            </div>

            <a
                href="<?= htmlspecialchars(lg_url('/modules/editor/dashboard.php')) ?>"
                class="lg-back-btn"
            >
                ← Dashboard
            </a>

        </div>


        <!-- =====================================================
             ALERTS
             ===================================================== -->

        <?php if (!empty($message)): ?>

            <div class="lg-alert lg-success">
                ✓ <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <?php if (!empty($error)): ?>

            <div class="lg-alert lg-error">
                ⚠ <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <!-- =====================================================
             STATISTICS
             ===================================================== -->

        <?php

        $totalOrders = count($orders);

        $pendingOrders = 0;
        $processingOrders = 0;
        $shippedOrders = 0;
        $deliveredOrders = 0;

        foreach ($orders as $statOrder) {

            switch ($statOrder['order_status']) {

                case 'Pending':
                    $pendingOrders++;
                    break;

                case 'Processing':
                    $processingOrders++;
                    break;

                case 'Shipped':
                    $shippedOrders++;
                    break;

                case 'Delivered':
                    $deliveredOrders++;
                    break;
            }
        }

        ?>

        <div class="lg-stats">

            <div class="lg-stat">

                <div class="lg-stat-icon">
                    📦
                </div>

                <div class="lg-stat-label">
                    Total Orders
                </div>

                <div class="lg-stat-number">
                    <?= $totalOrders ?>
                </div>

            </div>


            <div class="lg-stat">

                <div class="lg-stat-icon">
                    ⏳
                </div>

                <div class="lg-stat-label">
                    Processing
                </div>

                <div class="lg-stat-number">
                    <?= $processingOrders ?>
                </div>

            </div>


            <div class="lg-stat">

                <div class="lg-stat-icon">
                    🚚
                </div>

                <div class="lg-stat-label">
                    Shipped
                </div>

                <div class="lg-stat-number">
                    <?= $shippedOrders ?>
                </div>

            </div>


            <div class="lg-stat">

                <div class="lg-stat-icon">
                    ✓
                </div>

                <div class="lg-stat-label">
                    Delivered
                </div>

                <div class="lg-stat-number">
                    <?= $deliveredOrders ?>
                </div>

            </div>

        </div>


        <!-- =====================================================
             SEARCH + FILTER
             ===================================================== -->

        <?php if (!empty($orders)): ?>

            <div class="lg-toolbar">

                <div class="lg-search-box">

                    <span>⌕</span>

                    <input
                        type="text"
                        id="orderSearch"
                        class="lg-search"
                        placeholder="Search by order number, customer or email..."
                    >

                </div>


                <select
                    id="statusFilter"
                    class="lg-filter"
                >

                    <option value="all">
                        All Orders
                    </option>

                    <option value="Pending">
                        Pending
                    </option>

                    <option value="Processing">
                        Processing
                    </option>

                    <option value="Shipped">
                        Shipped
                    </option>

                    <option value="Delivered">
                        Delivered
                    </option>

                    <option value="Cancelled">
                        Cancelled
                    </option>

                </select>

            </div>

        <?php endif; ?>


        <!-- =====================================================
             ORDERS
             ===================================================== -->

        <?php if (empty($orders)): ?>

            <div class="lg-empty">

                <div class="lg-empty-icon">
                    📦
                </div>

                <h3>
                    No customer orders yet
                </h3>

                <p>
                    New customer orders will appear here automatically.
                </p>

            </div>

        <?php else: ?>

            <div
                class="lg-orders"
                id="ordersContainer"
            >

                <?php foreach ($orders as $order): ?>

                    <?php

                    $status = $order['order_status'];

                    $statusClass = match ($status) {

                        'Pending' => 'lg-status-pending',

                        'Processing' => 'lg-status-processing',

                        'Shipped' => 'lg-status-shipped',

                        'Delivered' => 'lg-status-delivered',

                        'Cancelled' => 'lg-status-cancelled',

                        default => 'lg-status-pending'
                    };


                    $customerName =
                        trim($order['customer_name'] ?? '');


                    $initials = '';

                    foreach (
                        preg_split('/\s+/', $customerName)
                        as $word
                    ) {

                        if ($word !== '') {

                            $initials .=
                                strtoupper(
                                    substr($word, 0, 1)
                                );
                        }
                    }

                    $initials =
                        substr($initials, 0, 2);


                    $deliveryStatus =
                        $order['delivery_status']
                        ?? 'Pending';


                    /*
                     * IMPORTANT:
                     *
                     * Controller returns company_name.
                     * Therefore we use company_name here.
                     */

                    $courierName =
                        $order['company_name']
                        ?? 'Not assigned';


                    $hasShipment =
                        !empty($order['shipment_id']);


                    $hasGps =
                        $order['current_latitude'] !== null &&
                        $order['current_longitude'] !== null;

                    ?>

                    <div
                        class="lg-order-card"
                        data-status="<?= htmlspecialchars($status) ?>"
                        data-search="<?= htmlspecialchars(
                            strtolower(
                                '#' . $order['order_id']
                                . ' '
                                . $order['customer_name']
                                . ' '
                                . $order['customer_email']
                            )
                        ) ?>"
                    >

                        <!-- =================================================
                             ORDER HEADER
                             ================================================= -->

                        <div class="lg-order-header">

                            <div>

                                <div class="lg-order-number">
                                    Order #<?= (int) $order['order_id'] ?>
                                </div>

                                <div class="lg-order-date">
                                    <?= htmlspecialchars(
                                        $order['order_date']
                                    ) ?>
                                </div>

                            </div>


                            <span
                                class="lg-status <?= $statusClass ?>"
                            >
                                <?= htmlspecialchars($status) ?>
                            </span>

                        </div>


                        <!-- =================================================
                             CUSTOMER
                             ================================================= -->

                        <div class="lg-customer">

                            <div class="lg-avatar">
                                <?= htmlspecialchars(
                                    $initials ?: 'CU'
                                ) ?>
                            </div>

                            <div>

                                <div class="lg-customer-name">
                                    <?= htmlspecialchars(
                                        $order['customer_name']
                                    ) ?>
                                </div>

                                <div class="lg-customer-email">
                                    <?= htmlspecialchars(
                                        $order['customer_email']
                                    ) ?>
                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             ORDER INFO
                             ================================================= -->

                        <div class="lg-info-grid">

                            <div class="lg-info">

                                <span class="lg-info-label">
                                    Order Total
                                </span>

                                <span class="lg-info-value">
                                    Rs.
                                    <?= number_format(
                                        (float) $order['total_amount'],
                                        2
                                    ) ?>
                                </span>

                            </div>


                            <div class="lg-info">

                                <span class="lg-info-label">
                                    Delivery
                                </span>

                                <span class="lg-info-value">
                                    <?= htmlspecialchars(
                                        $deliveryStatus
                                    ) ?>
                                </span>

                            </div>


                            <div class="lg-info">

                                <span class="lg-info-label">
                                    Courier
                                </span>

                                <span class="lg-info-value">
                                    <?= htmlspecialchars(
                                        $courierName
                                    ) ?>
                                </span>

                            </div>

                        </div>


                        <!-- =================================================
                             SMART GPS TRACKING
                             ================================================= -->

                        <div
                            class="lg-gps-panel"
                            data-order-id="<?= (int) $order['order_id'] ?>"
                        >

                            <div class="lg-gps-header">

                                <div class="lg-gps-title">

                                    <div class="lg-gps-icon">
                                        📍
                                    </div>

                                    <div>

                                        <div class="lg-gps-heading">
                                            Live GPS Tracking
                                        </div>

                                        <div class="lg-gps-subheading">
                                            Automatic courier location updates
                                        </div>

                                    </div>

                                </div>


                                <div
                                    class="lg-gps-badge"
                                    id="gpsBadge-<?= (int) $order['order_id'] ?>"
                                >
                                    GPS Offline
                                </div>

                            </div>


                            <div class="lg-gps-content">

                                <div
                                    class="lg-gps-status"
                                    id="gpsStatus-<?= (int) $order['order_id'] ?>"
                                >

                                    <?php if ($hasGps): ?>

                                        📍 Previous GPS location is available.
                                        Start tracking to continue receiving
                                        live location updates.

                                    <?php elseif (!$hasShipment): ?>

                                        ⚠ Save the shipment information first,
                                        then GPS tracking can be started.

                                    <?php else: ?>

                                        GPS tracking is ready.
                                        Click <strong>Start GPS Tracking</strong>
                                        to share the courier's current location.

                                    <?php endif; ?>

                                </div>


                                <div class="lg-gps-coordinates">

                                    <div class="lg-gps-coordinate">

                                        <span class="lg-gps-coordinate-label">
                                            Latitude
                                        </span>

                                        <span
                                            class="lg-gps-coordinate-value"
                                            id="gpsLat-<?= (int) $order['order_id'] ?>"
                                        >
                                            <?= $hasGps
                                                ? htmlspecialchars(
                                                    $order['current_latitude']
                                                )
                                                : '—'
                                            ?>
                                        </span>

                                    </div>


                                    <div class="lg-gps-coordinate">

                                        <span class="lg-gps-coordinate-label">
                                            Longitude
                                        </span>

                                        <span
                                            class="lg-gps-coordinate-value"
                                            id="gpsLng-<?= (int) $order['order_id'] ?>"
                                        >
                                            <?= $hasGps
                                                ? htmlspecialchars(
                                                    $order['current_longitude']
                                                )
                                                : '—'
                                            ?>
                                        </span>

                                    </div>

                                </div>


                                <div
                                    class="lg-gps-time"
                                    id="gpsTime-<?= (int) $order['order_id'] ?>"
                                >

                                    Last update:
                                    <?= !empty($order['location_updated_at'])
                                        ? htmlspecialchars(
                                            $order['location_updated_at']
                                        )
                                        : 'No GPS update yet'
                                    ?>

                                </div>


                                <div class="lg-gps-actions">

                                    <button
                                        type="button"
                                        class="lg-gps-start"
                                        data-order-id="<?= (int) $order['order_id'] ?>"
                                        <?= !$hasShipment ||
                                            $deliveryStatus === 'Delivered' ||
                                            $status === 'Cancelled'
                                                ? 'disabled'
                                                : ''
                                        ?>
                                    >
                                        📍 Start GPS Tracking
                                    </button>


                                    <button
                                        type="button"
                                        class="lg-gps-stop"
                                        data-order-id="<?= (int) $order['order_id'] ?>"
                                        disabled
                                    >
                                        ■ Stop GPS Tracking
                                    </button>

                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             UPDATE SHIPMENT
                             ================================================= -->

                        <div class="lg-update-area">

                            <div class="lg-update-heading">
                                Update shipment
                            </div>


                            <form
                                method="POST"
                                class="lg-form"
                            >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="update_tracking"
                                >


                                <input
                                    type="hidden"
                                    name="order_id"
                                    value="<?= (int) $order['order_id'] ?>"
                                >


                                <div class="lg-form-grid">

                                    <!-- ORDER STATUS -->

                                    <div class="lg-field">

                                        <label>
                                            Order Status
                                        </label>

                                        <select name="order_status">

                                            <?php

                                            $orderStatuses = [
                                                'Pending',
                                                'Processing',
                                                'Shipped',
                                                'Delivered',
                                                'Cancelled'
                                            ];

                                            ?>

                                            <?php foreach (
                                                $orderStatuses
                                                as $option
                                            ): ?>

                                                <option
                                                    value="<?= htmlspecialchars($option) ?>"
                                                    <?= $status === $option
                                                        ? 'selected'
                                                        : ''
                                                    ?>
                                                >
                                                    <?= htmlspecialchars($option) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>


                                    <!-- DELIVERY STATUS -->

                                    <div class="lg-field">

                                        <label>
                                            Delivery Status
                                        </label>

                                        <select name="delivery_status">

                                            <?php

                                            $deliveryStatuses = [
                                                'Pending',
                                                'Shipped',
                                                'In Transit',
                                                'Out for Delivery',
                                                'Delivered'
                                            ];

                                            ?>

                                            <?php foreach (
                                                $deliveryStatuses
                                                as $option
                                            ): ?>

                                                <option
                                                    value="<?= htmlspecialchars($option) ?>"
                                                    <?= $deliveryStatus === $option
                                                        ? 'selected'
                                                        : ''
                                                    ?>
                                                >
                                                    <?= htmlspecialchars($option) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>


                                    <!-- COURIER -->

                                    <div class="lg-field">

                                        <label>
                                            Courier
                                        </label>

                                        <select name="courier_id">

                                            <option value="0">
                                                Select courier
                                            </option>

                                            <?php foreach (
                                                $couriers
                                                as $courier
                                            ): ?>

                                                <option
                                                    value="<?= (int) $courier['courier_id'] ?>"
                                                    <?= (
                                                        (int) (
                                                            $order['courier_id']
                                                            ?? 0
                                                        )
                                                        ===
                                                        (int) $courier['courier_id']
                                                    )
                                                        ? 'selected'
                                                        : ''
                                                    ?>
                                                >
                                                    <?= htmlspecialchars(
                                                        $courier['company_name']
                                                    ) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>


                                    <!-- TRACKING NUMBER -->

                                    <div class="lg-field">

                                        <label>
                                            Tracking Number
                                        </label>

                                        <input
                                            type="text"
                                            name="tracking_number"
                                            value="<?= htmlspecialchars(
                                                $order['tracking_number']
                                                ?? ''
                                            ) ?>"
                                            placeholder="e.g. LG123456"
                                        >

                                    </div>


                                    <!-- ESTIMATE -->

                                    <div class="lg-field full">

                                        <label>
                                            Estimated Delivery
                                        </label>

                                        <input
                                            type="date"
                                            name="estimate_delivery"
                                            value="<?= htmlspecialchars(
                                                $order['estimate_delivery']
                                                ?? ''
                                            ) ?>"
                                        >

                                    </div>

                                </div>


                                <button
                                    type="submit"
                                    class="lg-update-button"
                                >
                                    ✓ Save Tracking Update
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>


            <!-- NO SEARCH RESULTS -->

            <div
                id="noSearchResults"
                class="lg-empty"
                style="display:none; margin-top:20px;"
            >

                <div class="lg-empty-icon">
                    🔎
                </div>

                <h3>
                    No orders found
                </h3>

                <p>
                    Try a different customer name, email or order status.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SEARCH + FILTER
    |--------------------------------------------------------------------------
    */

    const searchInput =
        document.getElementById('orderSearch');

    const statusFilter =
        document.getElementById('statusFilter');

    const cards =
        document.querySelectorAll('.lg-order-card');

    const noResults =
        document.getElementById('noSearchResults');


    function filterOrders() {

        if (!cards.length) {
            return;
        }

        const search =
            (searchInput?.value || '')
                .toLowerCase()
                .trim();

        const status =
            statusFilter?.value || 'all';

        let visible = 0;


        cards.forEach(function (card) {

            const cardSearch =
                card.dataset.search || '';

            const cardStatus =
                card.dataset.status || '';


            const matchesSearch =
                cardSearch.includes(search);

            const matchesStatus =
                status === 'all' ||
                cardStatus === status;


            if (
                matchesSearch &&
                matchesStatus
            ) {

                card.style.display = '';
                visible++;

            } else {

                card.style.display = 'none';
            }

        });


        if (noResults) {

            noResults.style.display =
                visible === 0
                    ? 'block'
                    : 'none';
        }
    }


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterOrders
        );
    }


    if (statusFilter) {

        statusFilter.addEventListener(
            'change',
            filterOrders
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SMART GPS TRACKING
    |--------------------------------------------------------------------------
    |
    | One browser GPS watcher is maintained for each order.
    |
    */

    const gpsWatchers = {};


    /*
    |--------------------------------------------------------------------------
    | UPDATE GPS DISPLAY
    |--------------------------------------------------------------------------
    */

    function updateGpsDisplay(
        orderId,
        latitude,
        longitude,
        updatedAt
    ) {

        const latElement =
            document.getElementById(
                'gpsLat-' + orderId
            );

        const lngElement =
            document.getElementById(
                'gpsLng-' + orderId
            );

        const timeElement =
            document.getElementById(
                'gpsTime-' + orderId
            );

        const statusElement =
            document.getElementById(
                'gpsStatus-' + orderId
            );


        if (latElement) {

            latElement.textContent =
                Number(latitude).toFixed(8);
        }


        if (lngElement) {

            lngElement.textContent =
                Number(longitude).toFixed(8);
        }


        if (timeElement) {

            timeElement.textContent =
                'Last update: ' + updatedAt;
        }


        if (statusElement) {

            statusElement.classList.remove(
                'error'
            );

            statusElement.classList.add(
                'success'
            );

            statusElement.innerHTML =
                '✓ Live GPS location successfully sent to SmartTrack.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SET GPS BADGE
    |--------------------------------------------------------------------------
    */

    function setGpsBadge(
        orderId,
        active
    ) {

        const badge =
            document.getElementById(
                'gpsBadge-' + orderId
            );


        if (!badge) {
            return;
        }


        if (active) {

            badge.textContent =
                'GPS Active';

            badge.classList.add(
                'active'
            );

        } else {

            badge.textContent =
                'GPS Offline';

            badge.classList.remove(
                'active'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SET GPS STATUS
    |--------------------------------------------------------------------------
    */

    function setGpsStatus(
        orderId,
        message,
        type
    ) {

        const element =
            document.getElementById(
                'gpsStatus-' + orderId
            );


        if (!element) {
            return;
        }


        element.classList.remove(
            'success',
            'error'
        );


        if (type) {

            element.classList.add(
                type
            );
        }


        element.textContent =
            message;
    }


    /*
    |--------------------------------------------------------------------------
    | SEND GPS TO SERVER
    |--------------------------------------------------------------------------
    */

    function sendGpsLocation(
        orderId,
        latitude,
        longitude
    ) {

        const formData =
            new FormData();


        formData.append(
            'action',
            'update_gps'
        );

        formData.append(
            'order_id',
            orderId
        );

        formData.append(
            'latitude',
            latitude
        );

        formData.append(
            'longitude',
            longitude
        );


        fetch(
            '<?= htmlspecialchars(
                lg_url('/modules/editor/order-tracking.php')
            ) ?>',
            {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }
        )
        .then(function (response) {

            return response.json();

        })
        .then(function (data) {

            if (!data.success) {

                setGpsStatus(
                    orderId,
                    '⚠ ' + (
                        data.message ||
                        'GPS update failed.'
                    ),
                    'error'
                );

                return;
            }


            updateGpsDisplay(
                orderId,
                data.latitude,
                data.longitude,
                data.updated_at
            );

        })
        .catch(function () {

            setGpsStatus(
                orderId,
                '⚠ Could not send GPS location to the server.',
                'error'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | START GPS
    |--------------------------------------------------------------------------
    */

    function startGpsTracking(orderId) {

        if (!navigator.geolocation) {

            setGpsStatus(
                orderId,
                '⚠ Your browser does not support GPS location services.',
                'error'
            );

            return;
        }


        if (gpsWatchers[orderId]) {

            return;
        }


        const startButton =
            document.querySelector(
                '.lg-gps-start[data-order-id="' +
                orderId +
                '"]'
            );


        const stopButton =
            document.querySelector(
                '.lg-gps-stop[data-order-id="' +
                orderId +
                '"]'
            );


        if (startButton) {
            startButton.disabled = true;
        }


        if (stopButton) {
            stopButton.disabled = false;
        }


        setGpsBadge(
            orderId,
            true
        );


        setGpsStatus(
            orderId,
            '📡 Requesting browser location permission...',
            null
        );


        /*
        |--------------------------------------------------------------------------
        | Browser GPS watcher
        |--------------------------------------------------------------------------
        */

        const watchId =
            navigator.geolocation.watchPosition(

                function (position) {

                    const latitude =
                        position.coords.latitude;

                    const longitude =
                        position.coords.longitude;


                    setGpsStatus(
                        orderId,
                        '📍 GPS signal received. Sending current courier location...',
                        null
                    );


                    sendGpsLocation(
                        orderId,
                        latitude,
                        longitude
                    );
                },


                function (error) {

                    let message =
                        'Unable to read GPS location.';


                    switch (error.code) {

                        case error.PERMISSION_DENIED:

                            message =
                                '⚠ Location permission was denied. Please allow location access in your browser.';

                            break;


                        case error.POSITION_UNAVAILABLE:

                            message =
                                '⚠ Your current location is unavailable.';

                            break;


                        case error.TIMEOUT:

                            message =
                                '⚠ GPS request timed out. Trying again...';

                            break;
                    }


                    setGpsStatus(
                        orderId,
                        message,
                        'error'
                    );


                    setGpsBadge(
                        orderId,
                        false
                    );


                    if (startButton) {
                        startButton.disabled = false;
                    }


                    if (stopButton) {
                        stopButton.disabled = true;
                    }


                    if (
                        gpsWatchers[orderId]
                    ) {

                        navigator.geolocation.clearWatch(
                            gpsWatchers[orderId]
                        );

                        delete gpsWatchers[orderId];
                    }
                },


                {
                    enableHighAccuracy: true,
                    maximumAge: 10000,
                    timeout: 15000
                }
            );


        gpsWatchers[orderId] =
            watchId;


        setGpsStatus(
            orderId,
            '🟢 GPS tracking is active. Your browser will automatically send location updates when the position changes.',
            'success'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STOP GPS
    |--------------------------------------------------------------------------
    */

    function stopGpsTracking(orderId) {

        if (gpsWatchers[orderId]) {

            navigator.geolocation.clearWatch(
                gpsWatchers[orderId]
            );

            delete gpsWatchers[orderId];
        }


        const startButton =
            document.querySelector(
                '.lg-gps-start[data-order-id="' +
                orderId +
                '"]'
            );


        const stopButton =
            document.querySelector(
                '.lg-gps-stop[data-order-id="' +
                orderId +
                '"]'
            );


        if (startButton) {
            startButton.disabled = false;
        }


        if (stopButton) {
            stopButton.disabled = true;
        }


        setGpsBadge(
            orderId,
            false
        );


        setGpsStatus(
            orderId,
            'GPS tracking stopped. The last saved location remains available to the customer.',
            null
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START BUTTON EVENTS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.lg-gps-start')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const orderId =
                        this.dataset.orderId;

                    startGpsTracking(
                        orderId
                    );
                }
            );
        });


    /*
    |--------------------------------------------------------------------------
    | STOP BUTTON EVENTS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.lg-gps-stop')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const orderId =
                        this.dataset.orderId;

                    stopGpsTracking(
                        orderId
                    );
                }
            );
        });


    /*
    |--------------------------------------------------------------------------
    | CLEAN UP GPS WHEN LEAVING PAGE
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'beforeunload',
        function () {

            Object.keys(
                gpsWatchers
            ).forEach(function (orderId) {

                navigator.geolocation.clearWatch(
                    gpsWatchers[orderId]
                );
            });
        }
    );

});
</script>