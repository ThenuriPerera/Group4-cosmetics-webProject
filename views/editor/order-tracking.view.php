<?php
/**
 * Luminé Glow - Modern Editor Order Tracking
 */
?>

<style>
    /* =========================================================
       LUMINÉ GLOW ORDER TRACKING
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
       CUSTOMER SECTION
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
       TRACKING FORM
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
       EMPTY STATE
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
                        Order Tracking
                    </h1>

                    <p class="lg-subtitle">
                        Manage customer deliveries and shipment updates.
                    </p>
                </div>

            </div>

            <a
                href="<?= lg_url('/modules/editor/dashboard.php') ?>"
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

                <div
                    class="lg-stat-number"
                    id="totalOrders"
                >
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

                <div
                    class="lg-stat-number"
                    id="processingOrders"
                >
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

                <div
                    class="lg-stat-number"
                    id="shippedOrders"
                >
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

                <div
                    class="lg-stat-number"
                    id="deliveredOrders"
                >
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

            <div class="lg-orders" id="ordersContainer">

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

                    $customerName = trim($order['customer_name'] ?? '');

                    $initials = '';

                    foreach (preg_split('/\s+/', $customerName) as $word) {
                        if ($word !== '') {
                            $initials .= strtoupper(substr($word, 0, 1));
                        }
                    }

                    $initials = substr($initials, 0, 2);

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

                        <!-- ORDER HEADER -->

                        <div class="lg-order-header">

                            <div>

                                <div class="lg-order-number">
                                    Order #<?= (int)$order['order_id'] ?>
                                </div>

                                <div class="lg-order-date">
                                    <?= htmlspecialchars($order['order_date']) ?>
                                </div>

                            </div>


                            <span
                                class="lg-status <?= $statusClass ?>"
                            >
                                <?= htmlspecialchars($status) ?>
                            </span>

                        </div>


                        <!-- CUSTOMER -->

                        <div class="lg-customer">

                            <div class="lg-avatar">
                                <?= htmlspecialchars($initials ?: 'CU') ?>
                            </div>

                            <div>

                                <div class="lg-customer-name">
                                    <?= htmlspecialchars($order['customer_name']) ?>
                                </div>

                                <div class="lg-customer-email">
                                    <?= htmlspecialchars($order['customer_email']) ?>
                                </div>

                            </div>

                        </div>


                        <!-- ORDER INFO -->

                        <div class="lg-info-grid">

                            <div class="lg-info">

                                <span class="lg-info-label">
                                    Order Total
                                </span>

                                <span class="lg-info-value">
                                    Rs.
                                    <?= number_format(
                                        (float)$order['total_amount'],
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
                                        $order['delivery_status']
                                        ?? 'Pending'
                                    ) ?>
                                </span>

                            </div>


                            <div class="lg-info">

                                <span class="lg-info-label">
                                    Courier
                                </span>

                                <span class="lg-info-value">

                                    <?= !empty($order['courier_name'])
                                        ? htmlspecialchars($order['courier_name'])
                                        : 'Not assigned'
                                    ?>

                                </span>

                            </div>

                        </div>


                        <!-- UPDATE -->

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
                                    value="<?= (int)$order['order_id'] ?>"
                                >


                                <div class="lg-form-grid">

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

                                            <?php foreach ($orderStatuses as $option): ?>

                                                <option
                                                    value="<?= htmlspecialchars($option) ?>"
                                                    <?= $status === $option ? 'selected' : '' ?>
                                                >
                                                    <?= htmlspecialchars($option) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>


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
                                                'Delivered'
                                            ];

                                            ?>

                                            <?php foreach ($deliveryStatuses as $option): ?>

                                                <option
                                                    value="<?= htmlspecialchars($option) ?>"
                                                    <?= ($order['delivery_status'] ?? 'Pending') === $option ? 'selected' : '' ?>
                                                >
                                                    <?= htmlspecialchars($option) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>


                                    <div class="lg-field">

                                        <label>
                                            Courier
                                        </label>

                                        <select name="courier_id">

                                            <option value="0">
                                                Select courier
                                            </option>

                                            <?php foreach ($couriers as $courier): ?>

                                                <option
                                                    value="<?= (int)$courier['courier_id'] ?>"
                                                    <?= ((int)($order['courier_id'] ?? 0) === (int)$courier['courier_id'])
                                                        ? 'selected'
                                                        : ''
                                                    ?>
                                                >
                                                    <?= htmlspecialchars($courier['company_name']) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>


                                    <div class="lg-field">

                                        <label>
                                            Tracking Number
                                        </label>

                                        <input
                                            type="text"
                                            name="tracking_number"
                                            value="<?= htmlspecialchars(
                                                $order['tracking_number'] ?? ''
                                            ) ?>"
                                            placeholder="e.g. LG123456"
                                        >

                                    </div>


                                    <div class="lg-field full">

                                        <label>
                                            Estimated Delivery
                                        </label>

                                        <input
                                            type="date"
                                            name="estimate_delivery"
                                            value="<?= htmlspecialchars(
                                                $order['estimate_delivery'] ?? ''
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

    const searchInput = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('statusFilter');
    const cards = document.querySelectorAll('.lg-order-card');
    const noResults = document.getElementById('noSearchResults');

    function filterOrders() {

        if (!cards.length) {
            return;
        }

        const search = (searchInput?.value || '')
            .toLowerCase()
            .trim();

        const status = statusFilter?.value || 'all';

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

            if (matchesSearch && matchesStatus) {

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

});
</script>