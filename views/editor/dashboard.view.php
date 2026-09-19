<?php
/**
 * Luminé Glow - Editor Dashboard View
 *
 * Main Editor/Admin workspace.
 */
?>

<style>
    .editor-dashboard {
        min-height: 75vh;
        padding: 40px 20px 70px;
        background:
            radial-gradient(
                circle at top left,
                rgba(255, 220, 230, 0.35),
                transparent 35%
            ),
            linear-gradient(
                135deg,
                #fff9fb 0%,
                #ffffff 50%,
                #fff6f8 100%
            );
    }

    .editor-dashboard-inner {
        max-width: 1150px;
        margin: 0 auto;
    }

    /* ---------------------------------------------------------
       WELCOME
    --------------------------------------------------------- */

    .editor-welcome {
        text-align: center;
        margin-bottom: 42px;
    }

    .editor-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 30px;
        background: #fff0f4;
        color: #a94b68;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 18px;
    }

    .editor-welcome h1 {
        margin: 0 0 12px;
        font-size: clamp(32px, 5vw, 48px);
        font-weight: 800;
        color: #2d2025;
        letter-spacing: -1px;
    }

    .editor-welcome h1 span {
        color: #c45d7d;
    }

    .editor-welcome p {
        max-width: 650px;
        margin: 0 auto;
        color: #75666c;
        font-size: 16px;
        line-height: 1.7;
    }

    /* ---------------------------------------------------------
       SECTION TITLE
    --------------------------------------------------------- */

    .editor-section-title {
        max-width: 1050px;
        margin: 0 auto 18px;
        color: #5d4850;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* ---------------------------------------------------------
       SMART ORDER OVERVIEW
    --------------------------------------------------------- */

    .smart-order-panel {
        max-width: 1050px;
        margin: 0 auto 38px;
        padding: 30px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid #f1dbe3;
        box-shadow: 0 18px 45px rgba(99, 45, 62, 0.08);
    }

    .smart-order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .smart-order-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .smart-order-title-icon {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f4;
        font-size: 24px;
    }

    .smart-order-title h2 {
        margin: 0 0 4px;
        color: #302329;
        font-size: 23px;
    }

    .smart-order-title p {
        margin: 0;
        color: #88777e;
        font-size: 13px;
    }

    .smart-order-badge {
        padding: 8px 14px;
        border-radius: 20px;
        background: #f7f4f5;
        color: #725e66;
        font-size: 12px;
        font-weight: 700;
    }

    /* ---------------------------------------------------------
       STAT CARDS
    --------------------------------------------------------- */

    .order-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }

    .order-stat {
        padding: 19px;
        border-radius: 18px;
        border: 1px solid #f0e1e6;
        background: #fffafb;
        transition: transform 0.2s ease;
    }

    .order-stat:hover {
        transform: translateY(-3px);
    }

    .order-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .order-stat-icon {
        font-size: 21px;
    }

    .order-stat-number {
        font-size: 28px;
        line-height: 1;
        font-weight: 800;
        color: #302329;
    }

    .order-stat-label {
        color: #786970;
        font-size: 12px;
        font-weight: 700;
    }

    /* ---------------------------------------------------------
       STATUS BREAKDOWN
    --------------------------------------------------------- */

    .order-status-title {
        margin: 0 0 15px;
        color: #5d4850;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .order-status-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
    }

    .status-box {
        padding: 15px 10px;
        border-radius: 15px;
        text-align: center;
        background: #faf7f8;
        border: 1px solid #f0e4e8;
    }

    .status-box strong {
        display: block;
        color: #302329;
        font-size: 21px;
        margin-bottom: 4px;
    }

    .status-box span {
        display: block;
        color: #806f76;
        font-size: 11px;
        font-weight: 700;
    }

    /* ---------------------------------------------------------
       ATTENTION AREA
    --------------------------------------------------------- */

    .attention-section {
        margin-top: 28px;
    }

    .attention-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .attention-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border-radius: 16px;
        background: #fff9fa;
        border: 1px solid #f1dfe5;
    }

    .attention-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff0f3;
        font-size: 18px;
    }

    .attention-card strong {
        display: block;
        color: #302329;
        font-size: 20px;
        line-height: 1.1;
    }

    .attention-card span {
        display: block;
        margin-top: 3px;
        color: #7d6c73;
        font-size: 11px;
        line-height: 1.3;
    }

    /* ---------------------------------------------------------
       QUICK ORDER LINK
    --------------------------------------------------------- */

    .smart-order-action {
        display: flex;
        justify-content: flex-end;
        margin-top: 25px;
    }

    .smart-order-button {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 12px 19px;
        border-radius: 13px;
        background: #b34f70;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition:
            transform 0.2s ease,
            background 0.2s ease;
    }

    .smart-order-button:hover {
        background: #9e4261;
        transform: translateY(-2px);
    }

    /* ---------------------------------------------------------
       WORKSPACE CARDS
    --------------------------------------------------------- */

    .editor-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 28px;
        max-width: 1050px;
        margin: 0 auto;
    }

    .editor-card {
        position: relative;
        overflow: hidden;
        min-height: 330px;
        padding: 38px;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #f3dce4;
        box-shadow: 0 18px 45px rgba(99, 45, 62, 0.08);
        text-decoration: none;
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .editor-card::before {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: #fff0f4;
        top: -80px;
        right: -60px;
        z-index: 0;
    }

    .editor-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 55px rgba(99, 45, 62, 0.14);
        border-color: #e8b8c8;
    }

    .editor-card-content {
        position: relative;
        z-index: 1;
    }

    .editor-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 22px;
        background: #fff1f5;
        color: #b34f70;
        font-size: 30px;
        margin-bottom: 25px;
        box-shadow: inset 0 0 0 1px #f4dce5;
    }

    .editor-card h2 {
        margin: 0 0 12px;
        color: #302329;
        font-size: 25px;
        font-weight: 750;
    }

    .editor-card p {
        margin: 0;
        color: #796b71;
        font-size: 15px;
        line-height: 1.7;
        max-width: 390px;
    }

    .editor-card-footer {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 30px;
    }

    .editor-card-label {
        color: #b34f70;
        font-weight: 700;
        font-size: 14px;
    }

    .editor-arrow {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #b34f70;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: transform 0.25s ease;
    }

    .editor-card:hover .editor-arrow {
        transform: translateX(5px);
    }

    /* ---------------------------------------------------------
       INFORMATION
    --------------------------------------------------------- */

    .editor-note {
        max-width: 1050px;
        margin: 32px auto 0;
        padding: 18px 22px;
        border-radius: 18px;
        background: #fff8fa;
        border: 1px solid #f2dfe6;
        color: #75666c;
        text-align: center;
        font-size: 14px;
        line-height: 1.6;
    }

    .editor-note strong {
        color: #9d4764;
    }

    /* ---------------------------------------------------------
       RESPONSIVE
    --------------------------------------------------------- */

    @media (max-width: 950px) {

        .order-stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .order-status-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .attention-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 760px) {

        .editor-dashboard {
            padding: 30px 15px 45px;
        }

        .editor-actions {
            grid-template-columns: 1fr;
        }

        .editor-card {
            min-height: 290px;
            padding: 30px;
        }

        .editor-welcome {
            margin-bottom: 32px;
        }

        .smart-order-panel {
            padding: 20px;
        }

        .smart-order-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .order-status-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .attention-grid {
            grid-template-columns: 1fr;
        }

        .smart-order-action {
            justify-content: stretch;
        }

        .smart-order-button {
            justify-content: center;
            width: 100%;
        }
    }
</style>


<section class="editor-dashboard">

<div class="editor-dashboard-inner">

    <!-- =====================================================
         WELCOME
    ====================================================== -->

    <div class="editor-welcome">

        <div class="editor-badge">
            ✦ EDITOR WORKSPACE
        </div>

        <h1>
            Welcome to <span>Luminé Glow</span>
        </h1>

        <p>
            Manage your beauty catalogue and get a complete overview
            of customer orders, shipments, couriers and delivery activity.
        </p>

    </div>


    <!-- =====================================================
         SMART ORDER OVERVIEW
    ====================================================== -->

    <div class="editor-section-title">
        Smart Order Overview
    </div>


    <div class="smart-order-panel">

        <div class="smart-order-header">

            <div class="smart-order-title">

                <div class="smart-order-title-icon">
                    📊
                </div>

                <div>

                    <h2>
                        Order Control Center
                    </h2>

                    <p>
                        Monitor the complete order journey from checkout
                        to final delivery.
                    </p>

                </div>

            </div>

            <div class="smart-order-badge">
                ✦ LIVE DATABASE OVERVIEW
            </div>

        </div>


        <!-- =================================================
             MAIN STATISTICS
        ================================================== -->

        <div class="order-stat-grid">

            <div class="order-stat">

                <div class="order-stat-top">

                    <span class="order-stat-icon">
                        🛒
                    </span>

                    <span class="order-stat-number">
                        <?= (int) $totalOrders ?>
                    </span>

                </div>

                <div class="order-stat-label">
                    Total Orders
                </div>

            </div>


            <div class="order-stat">

                <div class="order-stat-top">

                    <span class="order-stat-icon">
                        ⏳
                    </span>

                    <span class="order-stat-number">
                        <?= (int) $pendingOrders ?>
                    </span>

                </div>

                <div class="order-stat-label">
                    Pending
                </div>

            </div>


            <div class="order-stat">

                <div class="order-stat-top">

                    <span class="order-stat-icon">
                        🚚
                    </span>

                    <span class="order-stat-number">
                        <?= (int) $inTransitOrders ?>
                    </span>

                </div>

                <div class="order-stat-label">
                    In Transit
                </div>

            </div>


            <div class="order-stat">

                <div class="order-stat-top">

                    <span class="order-stat-icon">
                        ✅
                    </span>

                    <span class="order-stat-number">
                        <?= (int) $deliveredOrders ?>
                    </span>

                </div>

                <div class="order-stat-label">
                    Delivered
                </div>

            </div>

        </div>


        <!-- =================================================
             STATUS BREAKDOWN
        ================================================== -->

        <div class="order-status-title">
            Delivery Status Breakdown
        </div>


        <div class="order-status-grid">

            <div class="status-box">

                <strong>
                    <?= (int) $pendingOrders ?>
                </strong>

                <span>
                    Pending
                </span>

            </div>


            <div class="status-box">

                <strong>
                    <?= (int) $shippedOrders ?>
                </strong>

                <span>
                    Shipped
                </span>

            </div>


            <div class="status-box">

                <strong>
                    <?= (int) $inTransitOrders ?>
                </strong>

                <span>
                    In Transit
                </span>

            </div>


            <div class="status-box">

                <strong>
                    <?= (int) $outForDeliveryOrders ?>
                </strong>

                <span>
                    Out for Delivery
                </span>

            </div>


            <div class="status-box">

                <strong>
                    <?= (int) $deliveredOrders ?>
                </strong>

                <span>
                    Delivered
                </span>

            </div>


            <div class="status-box">

                <strong>
                    <?= (int) $ordersWithoutShipment ?>
                </strong>

                <span>
                    No Shipment
                </span>

            </div>

        </div>


        <!-- =================================================
             NEEDS ATTENTION
        ================================================== -->

        <div class="attention-section">

            <div class="order-status-title">
                ⚠ Needs Attention
            </div>


            <div class="attention-grid">

                <div class="attention-card">

                    <div class="attention-icon">
                        📦
                    </div>

                    <div>

                        <strong>
                            <?= (int) $ordersWithoutShipment ?>
                        </strong>

                        <span>
                            Orders without shipment
                        </span>

                    </div>

                </div>


                <div class="attention-card">

                    <div class="attention-icon">
                        🚚
                    </div>

                    <div>

                        <strong>
                            <?= (int) $ordersWithoutCourier ?>
                        </strong>

                        <span>
                            Courier not assigned
                        </span>

                    </div>

                </div>


                <div class="attention-card">

                    <div class="attention-icon">
                        📍
                    </div>

                    <div>

                        <strong>
                            <?= (int) $gpsNotStarted ?>
                        </strong>

                        <span>
                            GPS not started
                        </span>

                    </div>

                </div>


                <div class="attention-card">

                    <div class="attention-icon">
                        ⏰
                    </div>

                    <div>

                        <strong>
                            <?= (int) $overdueOrders ?>
                        </strong>

                        <span>
                            Overdue deliveries
                        </span>

                    </div>

                </div>

            </div>


            <?php if ($gpsOffline > 0): ?>

                <div
                    class="attention-card"
                    style="margin-top:12px;"
                >

                    <div class="attention-icon">
                        📡
                    </div>

                    <div>

                        <strong>
                            <?= (int) $gpsOffline ?>
                        </strong>

                        <span>
                            Active shipments with GPS not updated
                            for more than 30 minutes
                        </span>

                    </div>

                </div>

            <?php endif; ?>

        </div>


        <!-- =================================================
             OPEN ORDER TRACKING
        ================================================== -->

        <div class="smart-order-action">

            <a
                href="<?= htmlspecialchars(
                    lg_url('/modules/editor/order-tracking.php')
                ) ?>"
                class="smart-order-button"
            >
                📦 Open Order Management
                <span>→</span>
            </a>

        </div>

    </div>


    <!-- =====================================================
         WORKSPACE
    ====================================================== -->

    <div class="editor-section-title">
        Workspace
    </div>


    <div class="editor-actions">

        <!-- PRODUCT MANAGEMENT -->

        <a
            href="<?= htmlspecialchars(
                lg_url('/modules/products/manage.php')
            ) ?>"
            class="editor-card"
        >

            <div class="editor-card-content">

                <div class="editor-icon">
                    🛍
                </div>

                <h2>
                    Product Management
                </h2>

                <p>
                    Add new beauty products, update existing products,
                    manage stock information, categories, brands and
                    product details.
                </p>

            </div>


            <div class="editor-card-footer">

                <span class="editor-card-label">
                    Manage Products
                </span>

                <span class="editor-arrow">
                    →
                </span>

            </div>

        </a>


        <!-- ORDER TRACKING -->

        <a
            href="<?= htmlspecialchars(
                lg_url('/modules/editor/order-tracking.php')
            ) ?>"
            class="editor-card"
        >

            <div class="editor-card-content">

                <div class="editor-icon">
                    📦
                </div>

                <h2>
                    Order Tracking
                </h2>

                <p>
                    Manage customer orders from processing to delivery.
                    Update order status, delivery status, courier,
                    tracking number, estimated delivery and GPS location.
                </p>

            </div>


            <div class="editor-card-footer">

                <span class="editor-card-label">
                    Manage Order Tracking
                </span>

                <span class="editor-arrow">
                    →
                </span>

            </div>

        </a>

    </div>


    <!-- =====================================================
         INFORMATION
    ====================================================== -->

    <div class="editor-note">

        <strong>Smart Order Center:</strong>

        Monitor order progress, shipment creation, courier assignment,
        GPS tracking and overdue deliveries from one dashboard.
        Use Order Tracking to update shipment and delivery information.

    </div>

</div>

</section>