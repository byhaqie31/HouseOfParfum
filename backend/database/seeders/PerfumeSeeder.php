<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfumeSeeder extends Seeder
{
    /**
     * Idempotent: truncate the perfume table and re-insert the full catalogue.
     * Existing user data (users, sanctum tokens) is untouched.
     */
    public function run()
    {
        // delete() (not truncate()) — TRUNCATE silently no-op'd inside this
        // Docker MySQL setup, accumulating duplicates each re-run.
        DB::table('perfume')->delete();

        $now = now();

        // Fill in defaults so each row only has to declare meaningful fields.
        $mk = function (array $row) use ($now) {
            return array_merge([
                'image'          => '',
                'rrp'            => null,
                'rrp_rm'         => null,
                'history'        => '',
                'ref_shop'       => '',
                'percent_summer' => 50,
                'percent_autumn' => 50,
                'percent_winter' => 50,
                'percent_day'    => 50,
                'percent_night'  => 50,
                'created_at'     => $now,
                'updated_at'     => $now,
            ], $row);
        };

        $rows = [
            // ─── Calvin Klein ──────────────────────────────────────────
            $mk([
                'brand' => 'CK', 'name' => 'CK Euphoria', 'image' => 'images/perfumes/CK_CK Euphoria_60.jpg',
                'price' => 85, 'rrp' => 120, 'rrp_rm' => 550, 'size' => 60, 'quality' => 'Original', 'year' => '2005',
                'suit' => 'Women', 'main_accord' => 'Amber, Floral, Fruity',
                'top_notes' => 'Pomegranate, Persimmon, Green Notes',
                'middle_notes' => 'Black Orchid, Lotus Blossom, Champaca',
                'base_notes' => 'Mahogany, Amber, Violet, Cream',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'CK-EUPH-60',
                'percent_summer' => 20, 'percent_autumn' => 70, 'percent_winter' => 80, 'percent_day' => 30, 'percent_night' => 85,
            ]),
            $mk([
                'brand' => 'CK', 'name' => 'Euphoria', 'image' => 'images/perfumes/CK_Euphoria_60.jpg',
                'price' => 78, 'rrp' => 110, 'rrp_rm' => 500, 'size' => 60, 'quality' => 'Original', 'year' => '2005',
                'suit' => 'Women', 'main_accord' => 'Amber, Woody, Floral',
                'top_notes' => 'Pomegranate, Persimmon',
                'middle_notes' => 'Black Orchid, Lotus',
                'base_notes' => 'Mahogany, Amber, Musk',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'CK-EUPH2-60',
            ]),

            // ─── Chanel ────────────────────────────────────────────────
            $mk([
                'brand' => 'CN', 'name' => 'Coco Mademoiselle', 'image' => 'images/perfumes/CN_Coco Mademoiselle_30.jpg',
                'price' => 120, 'rrp' => 165, 'rrp_rm' => 750, 'size' => 30, 'quality' => 'Original', 'year' => '2001',
                'suit' => 'Women', 'main_accord' => 'Citrus, Floral, Woody',
                'top_notes' => 'Orange, Mandarin, Bergamot',
                'middle_notes' => 'Rose, Jasmine, Mimosa',
                'base_notes' => 'Patchouli, Vetiver, Vanilla, White Musk',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'CN-COCO-30',
                'percent_summer' => 75, 'percent_autumn' => 50, 'percent_winter' => 30, 'percent_day' => 85, 'percent_night' => 50,
            ]),
            $mk([
                'brand' => 'CN', 'name' => 'No. 5', 'price' => 140, 'rrp_rm' => 850, 'size' => 50, 'quality' => 'Original', 'year' => '1921',
                'suit' => 'Women', 'main_accord' => 'Aldehydic, Floral, Powdery',
                'top_notes' => 'Aldehydes, Ylang-Ylang, Neroli, Bergamot',
                'middle_notes' => 'Iris, Jasmine, Rose, Lily of the Valley',
                'base_notes' => 'Sandalwood, Vetiver, Musk, Amber, Civet',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'CN-N5-50',
                'percent_summer' => 30, 'percent_autumn' => 70, 'percent_winter' => 85, 'percent_day' => 45, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'CN', 'name' => 'Chance Eau Tendre', 'price' => 125, 'rrp_rm' => 780, 'size' => 50, 'quality' => 'Original', 'year' => '2010',
                'suit' => 'Women', 'main_accord' => 'Floral, Fruity, Fresh',
                'top_notes' => 'Grapefruit, Quince',
                'middle_notes' => 'Hyacinth, Jasmine',
                'base_notes' => 'White Musk, Iris, Amber',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'CN-CHTND-50',
                'percent_summer' => 80, 'percent_autumn' => 45, 'percent_winter' => 25, 'percent_day' => 85, 'percent_night' => 45,
            ]),

            // ─── Dior ──────────────────────────────────────────────────
            $mk([
                'brand' => 'DR', 'name' => 'Miss Dior', 'image' => 'images/perfumes/DR_Miss Dior_60.jpg',
                'price' => 130, 'rrp' => 175, 'rrp_rm' => 800, 'size' => 60, 'quality' => 'Original', 'year' => '2012',
                'suit' => 'Women', 'main_accord' => 'Floral, Chypre, Fresh',
                'top_notes' => 'Mandarin, Blood Orange, Rose',
                'middle_notes' => 'Grasse Rose, Turkish Rose, Peony',
                'base_notes' => 'Patchouli, Musk, Sandalwood',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'DR-MISS-60',
                'percent_summer' => 80, 'percent_autumn' => 40, 'percent_winter' => 20, 'percent_day' => 90, 'percent_night' => 35,
            ]),
            $mk([
                'brand' => 'DR', 'name' => 'Sauvage', 'price' => 135, 'rrp_rm' => 820, 'size' => 60, 'quality' => 'Original', 'year' => '2015',
                'suit' => 'Men', 'main_accord' => 'Fresh, Spicy, Amber',
                'top_notes' => 'Calabrian Bergamot, Pepper',
                'middle_notes' => 'Sichuan Pepper, Lavender, Pink Pepper, Vetiver',
                'base_notes' => 'Ambroxan, Cedar, Labdanum',
                'suit_season' => 'All Season', 'suit_time' => 'Day, Night', 'variation_id' => 'DR-SAUV-60',
                'percent_summer' => 65, 'percent_autumn' => 65, 'percent_winter' => 55, 'percent_day' => 75, 'percent_night' => 75,
            ]),
            $mk([
                'brand' => 'DR', 'name' => 'J\'adore', 'price' => 140, 'rrp_rm' => 850, 'size' => 50, 'quality' => 'Original', 'year' => '1999',
                'suit' => 'Women', 'main_accord' => 'Floral, Fruity, Sweet',
                'top_notes' => 'Pear, Melon, Magnolia, Peach',
                'middle_notes' => 'Jasmine, Lily-of-the-Valley, Tuberose, Freesia, Rose',
                'base_notes' => 'Musk, Vanilla, Blackberry, Cedar',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'DR-JAD-50',
                'percent_summer' => 75, 'percent_autumn' => 50, 'percent_winter' => 35, 'percent_day' => 80, 'percent_night' => 55,
            ]),

            // ─── Watsons ───────────────────────────────────────────────
            $mk([
                'brand' => 'WS', 'name' => 'Dementia', 'image' => 'images/perfumes/WS_Dementia_30.jpg',
                'price' => 25, 'rrp' => 40, 'rrp_rm' => 180, 'size' => 30, 'quality' => 'Inspired', 'year' => '2022',
                'suit' => 'Unisex', 'main_accord' => 'Woody, Aromatic, Fresh',
                'top_notes' => 'Bergamot, Lemon, Lavender',
                'middle_notes' => 'Geranium, Sage, Cedar',
                'base_notes' => 'Sandalwood, Musk, Amber',
                'suit_season' => 'All Season', 'suit_time' => 'Day', 'variation_id' => 'WS-DEM-30',
                'percent_summer' => 60, 'percent_autumn' => 50, 'percent_winter' => 40, 'percent_day' => 80, 'percent_night' => 30,
            ]),
            $mk([
                'brand' => 'WS', 'name' => 'Emily VS', 'image' => 'images/perfumes/WS_Emily VS_60.jpg',
                'price' => 30, 'rrp' => 45, 'rrp_rm' => 200, 'size' => 60, 'quality' => 'Inspired', 'year' => '2022',
                'suit' => 'Women', 'main_accord' => 'Floral, Sweet, Powdery',
                'top_notes' => 'Pink Pepper, Raspberry',
                'middle_notes' => 'Peony, Rose, Magnolia',
                'base_notes' => 'Musk, Cashmeran, Vanilla',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'WS-EMILY-60',
                'percent_summer' => 70, 'percent_autumn' => 45, 'percent_winter' => 25, 'percent_day' => 85, 'percent_night' => 40,
            ]),

            // ─── Yves Saint Laurent ────────────────────────────────────
            $mk([
                'brand' => 'YSL', 'name' => 'YSL Premium', 'image' => 'images/perfumes/YSL_YSL Premium_90.jpg',
                'price' => 150, 'rrp' => 200, 'rrp_rm' => 900, 'size' => 90, 'quality' => 'Original', 'year' => '2019',
                'suit' => 'Women', 'main_accord' => 'Floral, Oriental, Vanilla',
                'top_notes' => 'Cranberry, Bergamot, Pink Pepper',
                'middle_notes' => 'Peony, Orange Blossom, Jasmine',
                'base_notes' => 'Vanilla, Cashmeran, Cedar, Patchouli',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'YSL-PREM-90',
                'percent_summer' => 25, 'percent_autumn' => 75, 'percent_winter' => 85, 'percent_day' => 35, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'YSL', 'name' => 'Sara', 'image' => 'images/perfumes/YSL_Sara_90.png',
                'price' => 140, 'rrp' => 190, 'rrp_rm' => 850, 'size' => 90, 'quality' => 'Original', 'year' => '2020',
                'suit' => 'Women', 'main_accord' => 'Floral, Musky, Powdery',
                'top_notes' => 'Pear, Bergamot, Mandarin',
                'middle_notes' => 'Iris, Rose, Jasmine',
                'base_notes' => 'Musk, Cedar, Cashmere Wood',
                'suit_season' => 'Spring, Fall', 'suit_time' => 'Day, Night', 'variation_id' => 'YSL-SARA-90',
                'percent_summer' => 45, 'percent_autumn' => 70, 'percent_winter' => 55, 'percent_day' => 60, 'percent_night' => 65,
            ]),
            $mk([
                'brand' => 'YSL', 'name' => 'Black Opium', 'price' => 145, 'rrp_rm' => 880, 'size' => 50, 'quality' => 'Original', 'year' => '2014',
                'suit' => 'Women', 'main_accord' => 'Sweet, Coffee, Vanilla',
                'top_notes' => 'Pink Pepper, Orange Blossom, Pear',
                'middle_notes' => 'Coffee, Jasmine, Bitter Almond, Licorice',
                'base_notes' => 'Vanilla, Patchouli, Cedar, Cashmeran',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'YSL-BOP-50',
                'percent_summer' => 35, 'percent_autumn' => 80, 'percent_winter' => 85, 'percent_day' => 45, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'YSL', 'name' => 'Libre', 'price' => 145, 'rrp_rm' => 880, 'size' => 50, 'quality' => 'Original', 'year' => '2019',
                'suit' => 'Women', 'main_accord' => 'Floral, Lavender, Vanilla',
                'top_notes' => 'Mandarin, Lavender, Black Currant',
                'middle_notes' => 'Jasmine, Orange Blossom, Lavender Essence',
                'base_notes' => 'Madagascar Vanilla, Ambergris, Cedar, Musk',
                'suit_season' => 'All Season', 'suit_time' => 'Day, Night', 'variation_id' => 'YSL-LIB-50',
                'percent_summer' => 55, 'percent_autumn' => 65, 'percent_winter' => 60, 'percent_day' => 70, 'percent_night' => 70,
            ]),

            // ─── Le Labo ───────────────────────────────────────────────
            $mk([
                'brand' => 'LL', 'name' => 'Santal 33', 'price' => 200, 'rrp_rm' => 1190, 'size' => 50, 'quality' => 'Original', 'year' => '2011',
                'suit' => 'Unisex', 'main_accord' => 'Woody, Leather, Smoky',
                'top_notes' => 'Violet, Cardamom',
                'middle_notes' => 'Iris, Ambrox, Papyrus',
                'base_notes' => 'Australian Sandalwood, Cedarwood, Leather',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Day, Night', 'variation_id' => 'LL-SAN33-50',
                'percent_summer' => 40, 'percent_autumn' => 80, 'percent_winter' => 85, 'percent_day' => 65, 'percent_night' => 80,
            ]),
            $mk([
                'brand' => 'LL', 'name' => 'Rose 31', 'price' => 200, 'rrp_rm' => 1190, 'size' => 50, 'quality' => 'Original', 'year' => '2006',
                'suit' => 'Unisex', 'main_accord' => 'Floral, Woody, Spicy',
                'top_notes' => 'Rose Centifolia, Cumin',
                'middle_notes' => 'Olibanum, Guaiac Wood, Agarwood',
                'base_notes' => 'Cedarwood, Amber, Vetiver, Musk',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'LL-ROS31-50',
                'percent_summer' => 35, 'percent_autumn' => 75, 'percent_winter' => 80, 'percent_day' => 55, 'percent_night' => 85,
            ]),
            $mk([
                'brand' => 'LL', 'name' => 'Bergamote 22', 'price' => 200, 'rrp_rm' => 1190, 'size' => 50, 'quality' => 'Original', 'year' => '2006',
                'suit' => 'Unisex', 'main_accord' => 'Citrus, Floral, Musky',
                'top_notes' => 'Bergamot, Grapefruit',
                'middle_notes' => 'Petitgrain, Orange Blossom, Amber',
                'base_notes' => 'Vetiver, Musk, Cedarwood',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'LL-BRG22-50',
                'percent_summer' => 85, 'percent_autumn' => 55, 'percent_winter' => 30, 'percent_day' => 90, 'percent_night' => 50,
            ]),
            $mk([
                'brand' => 'LL', 'name' => 'The Noir 29', 'price' => 200, 'rrp_rm' => 1190, 'size' => 50, 'quality' => 'Original', 'year' => '2015',
                'suit' => 'Unisex', 'main_accord' => 'Tea, Fig, Woody',
                'top_notes' => 'Bay Leaves, Fig, Bergamot',
                'middle_notes' => 'Hay, Tobacco, Black Tea',
                'base_notes' => 'Vetiver, Cedarwood, Musk',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Day, Night', 'variation_id' => 'LL-NOR29-50',
                'percent_summer' => 40, 'percent_autumn' => 80, 'percent_winter' => 75, 'percent_day' => 60, 'percent_night' => 75,
            ]),

            // ─── Aesop ─────────────────────────────────────────────────
            $mk([
                'brand' => 'AS', 'name' => 'Hwyl', 'price' => 120, 'rrp_rm' => 720, 'size' => 50, 'quality' => 'Original', 'year' => '2017',
                'suit' => 'Unisex', 'main_accord' => 'Woody, Smoky, Earthy',
                'top_notes' => 'Cypress, Pepper',
                'middle_notes' => 'Frankincense, Mint',
                'base_notes' => 'Vetiver, Smoke, Cedarwood',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'AS-HWYL-50',
                'percent_summer' => 30, 'percent_autumn' => 80, 'percent_winter' => 85, 'percent_day' => 55, 'percent_night' => 80,
            ]),
            $mk([
                'brand' => 'AS', 'name' => 'Tacit', 'price' => 120, 'rrp_rm' => 720, 'size' => 50, 'quality' => 'Original', 'year' => '2015',
                'suit' => 'Unisex', 'main_accord' => 'Citrus, Herbal, Green',
                'top_notes' => 'Yuzu, Basil',
                'middle_notes' => 'Cardamom, Vetiver',
                'base_notes' => 'Cedarwood, Musk',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'AS-TAC-50',
                'percent_summer' => 85, 'percent_autumn' => 50, 'percent_winter' => 30, 'percent_day' => 90, 'percent_night' => 45,
            ]),
            $mk([
                'brand' => 'AS', 'name' => 'Marrakech Intense', 'price' => 130, 'rrp_rm' => 780, 'size' => 50, 'quality' => 'Original', 'year' => '2014',
                'suit' => 'Unisex', 'main_accord' => 'Oriental, Spicy, Resinous',
                'top_notes' => 'Cardamom, Clove, Bergamot',
                'middle_notes' => 'Rose, Jasmine, Neroli',
                'base_notes' => 'Sandalwood, Cedar, Frankincense',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'AS-MAR-50',
                'percent_summer' => 30, 'percent_autumn' => 75, 'percent_winter' => 85, 'percent_day' => 45, 'percent_night' => 90,
            ]),

            // ─── Diptyque ──────────────────────────────────────────────
            $mk([
                'brand' => 'DP', 'name' => 'Philosykos', 'price' => 165, 'rrp_rm' => 980, 'size' => 75, 'quality' => 'Original', 'year' => '1996',
                'suit' => 'Unisex', 'main_accord' => 'Green, Woody, Fig',
                'top_notes' => 'Fig Leaves',
                'middle_notes' => 'Fig, Coconut',
                'base_notes' => 'Cedar, Fig Tree Wood',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'DP-PHI-75',
                'percent_summer' => 90, 'percent_autumn' => 50, 'percent_winter' => 25, 'percent_day' => 85, 'percent_night' => 50,
            ]),
            $mk([
                'brand' => 'DP', 'name' => 'Tam Dao', 'price' => 165, 'rrp_rm' => 980, 'size' => 75, 'quality' => 'Original', 'year' => '2003',
                'suit' => 'Unisex', 'main_accord' => 'Woody, Sandalwood, Creamy',
                'top_notes' => 'Italian Cypress, Rosewood, Myrtle',
                'middle_notes' => 'Sandalwood, Cedar',
                'base_notes' => 'Amber, Spices, White Musk',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Day, Night', 'variation_id' => 'DP-TAM-75',
                'percent_summer' => 40, 'percent_autumn' => 80, 'percent_winter' => 80, 'percent_day' => 65, 'percent_night' => 75,
            ]),
            $mk([
                'brand' => 'DP', 'name' => 'Eau Rose', 'price' => 155, 'rrp_rm' => 920, 'size' => 75, 'quality' => 'Original', 'year' => '2012',
                'suit' => 'Women', 'main_accord' => 'Floral, Rose, Fresh',
                'top_notes' => 'Bergamot, Lychee',
                'middle_notes' => 'Centifolia Rose, Damask Rose, Geranium',
                'base_notes' => 'White Musk, Honey, Cedarwood',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'DP-ROS-75',
                'percent_summer' => 80, 'percent_autumn' => 55, 'percent_winter' => 35, 'percent_day' => 85, 'percent_night' => 55,
            ]),
            $mk([
                'brand' => 'DP', 'name' => 'L\'Ombre dans l\'Eau', 'price' => 155, 'rrp_rm' => 920, 'size' => 75, 'quality' => 'Original', 'year' => '1983',
                'suit' => 'Women', 'main_accord' => 'Green, Floral, Fresh',
                'top_notes' => 'Blackcurrant Leaves',
                'middle_notes' => 'Bulgarian Rose, Cassis',
                'base_notes' => 'White Musk',
                'suit_season' => 'Spring', 'suit_time' => 'Day', 'variation_id' => 'DP-OMB-75',
                'percent_summer' => 75, 'percent_autumn' => 50, 'percent_winter' => 30, 'percent_day' => 85, 'percent_night' => 45,
            ]),

            // ─── Jo Malone London ──────────────────────────────────────
            $mk([
                'brand' => 'JM', 'name' => 'English Pear & Freesia', 'price' => 140, 'rrp_rm' => 850, 'size' => 100, 'quality' => 'Original', 'year' => '2010',
                'suit' => 'Women', 'main_accord' => 'Fruity, Floral, Fresh',
                'top_notes' => 'King William Pear, Melon',
                'middle_notes' => 'Freesia, Rose',
                'base_notes' => 'Patchouli, Rhubarb, Amber',
                'suit_season' => 'Spring, Fall', 'suit_time' => 'Day', 'variation_id' => 'JM-EPF-100',
                'percent_summer' => 70, 'percent_autumn' => 65, 'percent_winter' => 40, 'percent_day' => 85, 'percent_night' => 55,
            ]),
            $mk([
                'brand' => 'JM', 'name' => 'Wood Sage & Sea Salt', 'price' => 140, 'rrp_rm' => 850, 'size' => 100, 'quality' => 'Original', 'year' => '2014',
                'suit' => 'Unisex', 'main_accord' => 'Aromatic, Salty, Woody',
                'top_notes' => 'Ambrette Seeds',
                'middle_notes' => 'Sea Salt',
                'base_notes' => 'Sage, Driftwood, Grapefruit',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'JM-WSS-100',
                'percent_summer' => 85, 'percent_autumn' => 60, 'percent_winter' => 35, 'percent_day' => 90, 'percent_night' => 55,
            ]),
            $mk([
                'brand' => 'JM', 'name' => 'Pomegranate Noir', 'price' => 150, 'rrp_rm' => 900, 'size' => 100, 'quality' => 'Original', 'year' => '2005',
                'suit' => 'Unisex', 'main_accord' => 'Fruity, Spicy, Woody',
                'top_notes' => 'Pomegranate, Raspberry, Plum',
                'middle_notes' => 'Casablanca Lily, Pink Pepper',
                'base_notes' => 'Patchouli, Spicy Woods, Frankincense',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'JM-PNR-100',
                'percent_summer' => 35, 'percent_autumn' => 80, 'percent_winter' => 85, 'percent_day' => 55, 'percent_night' => 85,
            ]),
            $mk([
                'brand' => 'JM', 'name' => 'Velvet Rose & Oud', 'price' => 165, 'rrp_rm' => 980, 'size' => 100, 'quality' => 'Original', 'year' => '2013',
                'suit' => 'Unisex', 'main_accord' => 'Rose, Oud, Spicy',
                'top_notes' => 'Damask Rose, Clove',
                'middle_notes' => 'Damask Rose Absolute',
                'base_notes' => 'Oud, Praline',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'JM-VRO-100',
                'percent_summer' => 30, 'percent_autumn' => 80, 'percent_winter' => 90, 'percent_day' => 45, 'percent_night' => 95,
            ]),

            // ─── Tom Ford ──────────────────────────────────────────────
            $mk([
                'brand' => 'TF', 'name' => 'Tobacco Vanille', 'price' => 290, 'rrp_rm' => 1720, 'size' => 50, 'quality' => 'Original', 'year' => '2007',
                'suit' => 'Unisex', 'main_accord' => 'Tobacco, Vanilla, Spicy',
                'top_notes' => 'Tobacco Leaf, Spices',
                'middle_notes' => 'Vanilla, Cacao, Tonka Bean',
                'base_notes' => 'Dried Fruits, Woods',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'TF-TBV-50',
                'percent_summer' => 25, 'percent_autumn' => 80, 'percent_winter' => 90, 'percent_day' => 40, 'percent_night' => 95,
            ]),
            $mk([
                'brand' => 'TF', 'name' => 'Oud Wood', 'price' => 290, 'rrp_rm' => 1720, 'size' => 50, 'quality' => 'Original', 'year' => '2007',
                'suit' => 'Unisex', 'main_accord' => 'Oud, Woody, Spicy',
                'top_notes' => 'Agarwood, Rosewood, Cardamom, Chinese Pepper',
                'middle_notes' => 'Oud, Sandalwood, Vetiver',
                'base_notes' => 'Tonka Bean, Vanilla, Amber',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'TF-OUD-50',
                'percent_summer' => 30, 'percent_autumn' => 80, 'percent_winter' => 90, 'percent_day' => 55, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'TF', 'name' => 'Black Orchid', 'price' => 230, 'rrp_rm' => 1380, 'size' => 50, 'quality' => 'Original', 'year' => '2006',
                'suit' => 'Unisex', 'main_accord' => 'Floral, Oriental, Sweet',
                'top_notes' => 'Truffle, Gardenia, Black Currant, Ylang-Ylang, Jasmine, Bergamot',
                'middle_notes' => 'Orchid, Spices, Gardenia, Fruity Notes, Lotus',
                'base_notes' => 'Mexican Chocolate, Patchouli, Vanilla, Sandalwood, Vetiver, Incense',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'TF-BOR-50',
                'percent_summer' => 35, 'percent_autumn' => 75, 'percent_winter' => 85, 'percent_day' => 45, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'TF', 'name' => 'Tuscan Leather', 'price' => 320, 'rrp_rm' => 1900, 'size' => 50, 'quality' => 'Original', 'year' => '2007',
                'suit' => 'Unisex', 'main_accord' => 'Leather, Woody, Smoky',
                'top_notes' => 'Saffron, Raspberry, Thyme',
                'middle_notes' => 'Olibanum, Jasmine',
                'base_notes' => 'Suede, Leather, Amberwood',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'TF-TUS-50',
                'percent_summer' => 30, 'percent_autumn' => 80, 'percent_winter' => 85, 'percent_day' => 60, 'percent_night' => 90,
            ]),

            // ─── Byredo ────────────────────────────────────────────────
            $mk([
                'brand' => 'BR', 'name' => 'Gypsy Water', 'price' => 220, 'rrp_rm' => 1300, 'size' => 50, 'quality' => 'Original', 'year' => '2008',
                'suit' => 'Unisex', 'main_accord' => 'Woody, Aromatic, Vanilla',
                'top_notes' => 'Bergamot, Lemon, Pepper, Juniper Berries',
                'middle_notes' => 'Incense, Pine Needles, Orris',
                'base_notes' => 'Amber, Vanilla, Sandalwood',
                'suit_season' => 'All Season', 'suit_time' => 'Day, Night', 'variation_id' => 'BR-GYP-50',
                'percent_summer' => 60, 'percent_autumn' => 75, 'percent_winter' => 65, 'percent_day' => 75, 'percent_night' => 75,
            ]),
            $mk([
                'brand' => 'BR', 'name' => 'Bal d\'Afrique', 'price' => 220, 'rrp_rm' => 1300, 'size' => 50, 'quality' => 'Original', 'year' => '2009',
                'suit' => 'Unisex', 'main_accord' => 'Citrus, Floral, Musky',
                'top_notes' => 'Lemon, Neroli, Bergamot, Bucchu',
                'middle_notes' => 'Violet, Cyclamen, Marigold, Jasmine Petals',
                'base_notes' => 'Vetiver, Cedarwood, Black Amber, Musk',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'BR-BAL-50',
                'percent_summer' => 85, 'percent_autumn' => 60, 'percent_winter' => 35, 'percent_day' => 90, 'percent_night' => 55,
            ]),
            $mk([
                'brand' => 'BR', 'name' => 'Mojave Ghost', 'price' => 220, 'rrp_rm' => 1300, 'size' => 50, 'quality' => 'Original', 'year' => '2014',
                'suit' => 'Unisex', 'main_accord' => 'Floral, Woody, Musky',
                'top_notes' => 'Ambrette, Jamaican Nesberry, Violet',
                'middle_notes' => 'Sandalwood, Magnolia',
                'base_notes' => 'Crisp Amber, Cedar, Chantilly Musk',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'BR-MOJ-50',
                'percent_summer' => 80, 'percent_autumn' => 60, 'percent_winter' => 40, 'percent_day' => 85, 'percent_night' => 60,
            ]),

            // ─── Maison Margiela Replica ───────────────────────────────
            $mk([
                'brand' => 'MM', 'name' => 'Jazz Club', 'price' => 145, 'rrp_rm' => 870, 'size' => 100, 'quality' => 'Original', 'year' => '2013',
                'suit' => 'Men', 'main_accord' => 'Tobacco, Sweet, Spicy',
                'top_notes' => 'Pink Pepper, Neroli, Lemon',
                'middle_notes' => 'Rum, Tobacco Leaf, Clary Sage',
                'base_notes' => 'Vanilla Bean, Tonka Bean, Vetiver, Cedar, Styrax',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'MM-JAZ-100',
                'percent_summer' => 30, 'percent_autumn' => 80, 'percent_winter' => 85, 'percent_day' => 45, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'MM', 'name' => 'By the Fireplace', 'price' => 145, 'rrp_rm' => 870, 'size' => 100, 'quality' => 'Original', 'year' => '2015',
                'suit' => 'Unisex', 'main_accord' => 'Smoky, Sweet, Woody',
                'top_notes' => 'Pink Pepper, Orange Blossom, Clove',
                'middle_notes' => 'Chestnut, Guaiac Wood, Juniper',
                'base_notes' => 'Vanilla, Cashmeran, Peru Balsam',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'MM-FIR-100',
                'percent_summer' => 25, 'percent_autumn' => 80, 'percent_winter' => 95, 'percent_day' => 50, 'percent_night' => 90,
            ]),
            $mk([
                'brand' => 'MM', 'name' => 'Beach Walk', 'price' => 145, 'rrp_rm' => 870, 'size' => 100, 'quality' => 'Original', 'year' => '2012',
                'suit' => 'Unisex', 'main_accord' => 'Salty, Coconut, Floral',
                'top_notes' => 'Bergamot, Pink Pepper, Lemon',
                'middle_notes' => 'Ylang-Ylang, Coconut Milk, Heliotrope',
                'base_notes' => 'Musk, Cedarwood, Benzoin',
                'suit_season' => 'Summer', 'suit_time' => 'Day', 'variation_id' => 'MM-BCH-100',
                'percent_summer' => 95, 'percent_autumn' => 45, 'percent_winter' => 20, 'percent_day' => 90, 'percent_night' => 50,
            ]),
            $mk([
                'brand' => 'MM', 'name' => 'Lazy Sunday Morning', 'price' => 145, 'rrp_rm' => 870, 'size' => 100, 'quality' => 'Original', 'year' => '2012',
                'suit' => 'Unisex', 'main_accord' => 'Clean, Floral, Musky',
                'top_notes' => 'Aldehydes, Pear, Bergamot',
                'middle_notes' => 'Iris, Lily-of-the-Valley, Lily, Rose',
                'base_notes' => 'White Musk, Patchouli, Ambrette',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day', 'variation_id' => 'MM-LZY-100',
                'percent_summer' => 80, 'percent_autumn' => 60, 'percent_winter' => 40, 'percent_day' => 90, 'percent_night' => 55,
            ]),

            // ─── Frederic Malle ────────────────────────────────────────
            $mk([
                'brand' => 'FM', 'name' => 'Portrait of a Lady', 'price' => 340, 'rrp_rm' => 2050, 'size' => 50, 'quality' => 'Original', 'year' => '2010',
                'suit' => 'Women', 'main_accord' => 'Rose, Oriental, Patchouli',
                'top_notes' => 'Raspberry, Clove, Cinnamon',
                'middle_notes' => 'Turkish Rose, Patchouli, Sandalwood, Benzoin',
                'base_notes' => 'Frankincense, White Musk, Ambrox',
                'suit_season' => 'Fall, Winter', 'suit_time' => 'Night', 'variation_id' => 'FM-POL-50',
                'percent_summer' => 30, 'percent_autumn' => 80, 'percent_winter' => 90, 'percent_day' => 50, 'percent_night' => 95,
            ]),
            $mk([
                'brand' => 'FM', 'name' => 'Carnal Flower', 'price' => 340, 'rrp_rm' => 2050, 'size' => 50, 'quality' => 'Original', 'year' => '2005',
                'suit' => 'Women', 'main_accord' => 'Floral, Tuberose, Green',
                'top_notes' => 'Bergamot, Melon, Eucalyptus',
                'middle_notes' => 'Tuberose, Ylang-Ylang, Jasmine, Orange Blossom',
                'base_notes' => 'Coconut, White Musk',
                'suit_season' => 'Spring, Summer', 'suit_time' => 'Day, Night', 'variation_id' => 'FM-CFL-50',
                'percent_summer' => 85, 'percent_autumn' => 55, 'percent_winter' => 30, 'percent_day' => 75, 'percent_night' => 80,
            ]),
        ];

        DB::table('perfume')->insert($rows);
    }
}
