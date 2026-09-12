<?php
/**
 * Luminé Glow - Editor Dashboard View
 *
 * Navigation only:
 * 1. Product Management
 * 2. Order Tracking
 */
?>

<style>
    .editor-dashboard {
        min-height: 75vh;
        padding: 40px 20px 60px;
        background:
            radial-gradient(circle at top left, rgba(255, 220, 230, 0.35), transparent 35%),
            linear-gradient(135deg, #fff9fb 0%, #ffffff 50%, #fff6f8 100%);
    }

    .editor-dashboard-inner {
        max-width: 1150px;
        margin: 0 auto;
    }

    .editor-welcome {
        text-align: center;
        margin-bottom: 45px;
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

    .editor-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 28px;
        max-width: 950px;
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
        transition: transform 0.25s ease,
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

    .editor-note {
        max-width: 950px;
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
    }
</style>

<section class="editor-dashboard">
    <div class="editor-dashboard-inner">

        <div class="editor-welcome">

            <div class="editor-badge">
                ✦ EDITOR WORKSPACE
            </div>

            <h1>
                Welcome to <span>Luminé Glow</span>
            </h1>

            <p>
                Manage your beauty catalogue and keep customer orders moving
                smoothly from processing to delivery.
            </p>

        </div>


        <div class="editor-actions">

            <!-- Product Management -->
            <a
                href="<?= htmlspecialchars(lg_url('/modules/products/manage.php')) ?>"
                class="editor-card"
            >

                <div class="editor-card-content">

                    <div class="editor-icon">
                        🛍
                    </div>

                    <h2>Product Management</h2>

                    <p>
                        Add new beauty products, update existing products,
                        manage stock information, and remove products from
                        the catalogue.
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


            <!-- Order Tracking -->
            <a
                href="<?= htmlspecialchars(lg_url('/modules/editor/order-tracking.php')) ?>"
                class="editor-card"
            >

                <div class="editor-card-content">

                    <div class="editor-icon">
                        📦
                    </div>

                    <h2>Order Tracking</h2>

                    <p>
                        View customer orders, update order status, manage
                        courier information, tracking numbers, and estimated
                        delivery dates.
                    </p>

                </div>

                <div class="editor-card-footer">

                    <span class="editor-card-label">
                        Track Orders
                    </span>

                    <span class="editor-arrow">
                        →
                    </span>

                </div>

            </a>

        </div>


        <div class="editor-note">
            <strong>Shared workspace:</strong>
            Product Management is shared by both Editor and Admin roles,
            so both users work with the same product management interface.
        </div>

    </div>
</section>