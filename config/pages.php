<?php
/** Page identity and asset mapping. Add a route here when adding a template. */
$lgPages = [
 'home' => ['Home', '', 'home'],
 'auth/login' => ['Welcome back', 'Sign in to your beauty space.', 'auth'],
 'auth/register' => ['Create your account', 'Make your beauty routine personal.', 'auth'],
 'auth/profile' => ['My account', 'Your details and delivery addresses, all in one place.', 'account'],
 'products/index' => ['The beauty collection', 'Discover skincare and makeup for your everyday routine.', 'catalogue'],
 'products/product' => ['Product details', 'Find a new favourite for your routine.', 'catalogue'],
 'products/shade-finder' => ['Find your shade', 'Explore products matched to your natural skin tone.', 'beauty'],
 'products/beauty-quiz' => ['Your beauty profile', 'A few simple questions to get to know your skin.', 'beauty'],
 'products/manage' => ['Product studio', 'Keep your collection accurate and up to date.', 'admin'],
 'orders/track-order' => ['My orders', 'Follow your purchases from checkout to delivery.', 'account'],
 'orders/wishlist' => ['My wishlist', 'Keep your favourite products close.', 'account'],
 'orders/reviews' => ['Share your experience', 'Your review can help someone find their next favourite.', 'account'],
 'cart/cart' => ['Your shopping bag', 'Review the little things that make your routine.', 'shopping'],
 'cart/checkout' => ['Delivery details', 'Choose where you would like your order delivered.', 'shopping'],
 'cart/payment' => ['Review and pay', 'Review your total before continuing to payment.', 'shopping'],
 'cart/payment-cancel' => ['Payment not completed', 'You can return to your bag and try again.', 'shopping'],
 'admin/dashboard' => ['Store overview', 'A clear view of your store activity.', 'admin'],
 'admin/review-moderation' => ['Review moderation', 'Review customer feedback before it appears in the shop.', 'admin'],
];
$lgPage = $lgPages[$pageKey ?? 'home'] ?? ['Luminé Glow', '', 'account'];
$pageTitle = $lgPage[0];
$pageDescription = $lgPage[1];
$pageStyle = $lgPage[2];
$pageScript = [
 'cart/cart' => 'cart',
 'products/beauty-quiz' => 'beauty-quiz',
 'products/shade-finder' => 'shade-finder',
][$pageKey ?? 'home'] ?? null;
