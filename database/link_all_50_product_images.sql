-- Link all 50 Lumine Glow sample products to the included generated catalog images.
-- Run after extracting this bundle into the project root.
USE lumine_glow;

UPDATE Product
SET image = CASE product_id
    WHEN 1 THEN '01-silk-veil-liquid-foundation.webp' -- Silk Veil Liquid Foundation
    WHEN 2 THEN '02-velvet-petal-lipstick.webp' -- Velvet Petal Lipstick
    WHEN 3 THEN '03-cloud-facial-cleanser.webp' -- Cloud Facial Cleanser
    WHEN 4 THEN '04-soft-bloom-face-cream.webp' -- Soft Bloom Face Cream
    WHEN 5 THEN '05-silken-roots-shampoo.webp' -- Silken Roots Shampoo
    WHEN 6 THEN '06-silken-lengths-conditioner.webp' -- Silken Lengths Conditioner
    WHEN 7 THEN '07-petal-hour-perfume.webp' -- Petal Hour Perfume
    WHEN 8 THEN '08-morning-bloom-body-mist.webp' -- Morning Bloom Body Mist
    WHEN 9 THEN '09-bloom-bath-body-wash.webp' -- Bloom Bath Body Wash
    WHEN 10 THEN '10-velvet-body-lotion.webp' -- Velvet Body Lotion
    WHEN 11 THEN '11-studio-soft-brush-set.webp' -- Studio Soft Brush Set
    WHEN 12 THEN '12-cloud-blend-makeup-sponge.webp' -- Cloud Blend Makeup Sponge
    WHEN 13 THEN '13-soft-focus-concealer.webp' -- Soft Focus Concealer
    WHEN 14 THEN '14-cloud-finish-loose-powder.webp' -- Cloud Finish Loose Powder
    WHEN 15 THEN '15-petal-touch-cream-blush.webp' -- Petal Touch Cream Blush
    WHEN 16 THEN '16-golden-hour-bronzer.webp' -- Golden Hour Bronzer
    WHEN 17 THEN '17-moonbeam-highlighter.webp' -- Moonbeam Highlighter
    WHEN 18 THEN '18-rose-muse-eyeshadow-palette.webp' -- Rose Muse Eyeshadow Palette
    WHEN 19 THEN '19-fine-line-liquid-eyeliner.webp' -- Fine Line Liquid Eyeliner
    WHEN 20 THEN '20-petal-lift-mascara.webp' -- Petal Lift Mascara
    WHEN 21 THEN '21-brow-sketch-pencil.webp' -- Brow Sketch Pencil
    WHEN 22 THEN '22-petal-pocket-lip-balm.webp' -- Petal Pocket Lip Balm
    WHEN 23 THEN '23-dew-ritual-facial-toner.webp' -- Dew Ritual Facial Toner
    WHEN 24 THEN '24-morning-dew-face-serum.webp' -- Morning Dew Face Serum
    WHEN 25 THEN '25-moon-petal-night-cream.webp' -- Moon Petal Night Cream
    WHEN 26 THEN '26-clear-petal-micellar-water.webp' -- Clear Petal Micellar Water
    WHEN 27 THEN '27-quiet-ritual-face-mask.webp' -- Quiet Ritual Face Mask
    WHEN 28 THEN '28-soft-morning-eye-cream.webp' -- Soft Morning Eye Cream
    WHEN 29 THEN '29-petal-sleep-lip-mask.webp' -- Petal Sleep Lip Mask
    WHEN 30 THEN '30-melt-away-cleansing-balm.webp' -- Melt Away Cleansing Balm
    WHEN 31 THEN '31-silken-ritual-hair-mask.webp' -- Silken Ritual Hair Mask
    WHEN 32 THEN '32-gloss-petal-hair-oil.webp' -- Gloss Petal Hair Oil
    WHEN 33 THEN '33-silken-air-leave-in-conditioner.webp' -- Silken Air Leave-in Conditioner
    WHEN 34 THEN '34-roots-ritual-scalp-serum.webp' -- Roots Ritual Scalp Serum
    WHEN 35 THEN '35-cloud-fresh-dry-shampoo.webp' -- Cloud Fresh Dry Shampoo
    WHEN 36 THEN '36-soft-shape-styling-cream.webp' -- Soft Shape Styling Cream
    WHEN 37 THEN '37-amber-evening-perfume.webp' -- Amber Evening Perfume
    WHEN 38 THEN '38-citrus-daybreak-body-mist.webp' -- Citrus Daybreak Body Mist
    WHEN 39 THEN '39-vanilla-dusk-perfume.webp' -- Vanilla Dusk Perfume
    WHEN 40 THEN '40-petal-journey-travel-perfume.webp' -- Petal Journey Travel Perfume
    WHEN 41 THEN '41-petal-polish-body-scrub.webp' -- Petal Polish Body Scrub
    WHEN 42 THEN '42-soft-petal-hand-cream.webp' -- Soft Petal Hand Cream
    WHEN 43 THEN '43-evening-ritual-foot-cream.webp' -- Evening Ritual Foot Cream
    WHEN 44 THEN '44-quiet-bloom-bath-salts.webp' -- Quiet Bloom Bath Salts
    WHEN 45 THEN '45-velvet-petal-body-butter.webp' -- Velvet Petal Body Butter
    WHEN 46 THEN '46-bloom-sink-hand-wash.webp' -- Bloom Sink Hand Wash
    WHEN 47 THEN '47-studio-soft-powder-brush.webp' -- Studio Soft Powder Brush
    WHEN 48 THEN '48-studio-eye-brush-trio.webp' -- Studio Eye Brush Trio
    WHEN 49 THEN '49-petal-curve-lash-curler.webp' -- Petal Curve Lash Curler
    WHEN 50 THEN '50-cloud-ritual-beauty-headband.webp' -- Cloud Ritual Beauty Headband
    ELSE image
END
WHERE product_id BETWEEN 1 AND 50;
