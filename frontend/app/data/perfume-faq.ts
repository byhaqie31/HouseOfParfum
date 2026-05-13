// Long-form reference content rendered on /almanac as a "book look" — chapters
// laid out as numbered Q&A with all answers expanded. Skip product-page FAQ
// templates (those belong on /perfume/[id]).

export type FaqEntry = {
  q: string
  a: string
}

export type FaqChapter = {
  id: string        // url anchor, e.g. "#history"
  number: string    // "01"
  title: string
  intro: string     // italic Bodoni opener
  entries: FaqEntry[]
}

export const PERFUME_FAQ: FaqChapter[] = [
  {
    id: 'about',
    number: '01',
    title: 'About perfume',
    intro: 'The first questions — what perfume is, how long it lives, why it behaves differently from one wearer to the next.',
    entries: [
      {
        q: 'What is perfume?',
        a: 'Perfume is a scented liquid composed of fragrance oils, aroma compounds, alcohol and sometimes water. The oils are the scent; the alcohol carries them off the skin and into the air.',
      },
      {
        q: 'What is the difference between perfume, eau de parfum and eau de toilette?',
        a: 'The names describe how concentrated the fragrance oil is in the alcohol base. Parfum holds the most, eau de parfum sits in the middle, and eau de toilette is lighter and shorter-lived.',
      },
      {
        q: 'How long does perfume usually last?',
        a: 'Between two and twelve hours, depending on the concentration, the ingredients, the wearer\'s skin and the climate. Heavier base notes outlast top notes by a wide margin.',
      },
      {
        q: 'Why does perfume smell different on different people?',
        a: 'Skin chemistry, body temperature, diet, hydration and even medication all shift how a fragrance unfolds. The same scent can read sweeter on one wearer and drier on another.',
      },
      {
        q: 'Can perfume expire?',
        a: 'Yes. Over time the scent can dull, the colour can darken and the alcohol can sour, especially when the bottle is exposed to heat, light or air.',
      },
      {
        q: 'How should I store my perfume?',
        a: 'Upright, in a cool, dry, dark place. Bathrooms are the worst spot — humidity and heat spikes degrade fragrance quickly. A bedroom drawer is ideal.',
      },
      {
        q: 'Should perfume be shaken before use?',
        a: 'No. Shaking introduces air and can dull the composition over time. The juice does not need to be agitated to spray cleanly.',
      },
      {
        q: 'Can I wear perfume every day?',
        a: 'Yes. Lighter, cleaner fragrances suit daily wear; heavier compositions are usually saved for evenings, cold weather or occasions.',
      },
    ],
  },
  {
    id: 'history',
    number: '02',
    title: 'Perfume history',
    intro: 'A short walk through where perfume came from — from temple incense to the workshops of Grasse.',
    entries: [
      {
        q: 'Where did perfume originally come from?',
        a: 'Perfume has ancient roots in Egypt, Mesopotamia, India, China and the Middle East, where scented oils and resins were used for ritual, beauty and status long before they were sold in bottles.',
      },
      {
        q: 'Why was perfume important in ancient times?',
        a: 'It served religion, medicine, personal grooming and luxury all at once. Temples burned incense as offering; physicians prescribed scented oils; royalty wore them as a marker of rank.',
      },
      {
        q: 'Who first made perfume?',
        a: 'One of the earliest recorded perfumers is Tapputi, a woman from ancient Mesopotamia whose distillation methods were preserved on cuneiform tablets dated around 1200 BCE.',
      },
      {
        q: 'How did perfume become popular in Europe?',
        a: 'European perfume traditions grew through trade with the Islamic world during the medieval period, then expanded through Italian and French royal courts in the Renaissance.',
      },
      {
        q: 'Why is France famous for perfume?',
        a: 'The town of Grasse, in the south of France, became a centre for cultivating jasmine, rose, tuberose and lavender. Its microclimate is now UNESCO-protected as part of the craft of perfumery.',
      },
      {
        q: 'How has perfume changed over time?',
        a: 'It moved from natural oils and incense to modern compositions that blend naturals with synthetic aroma molecules — which made fragrances steadier, more affordable and able to evoke scents nature alone cannot produce.',
      },
    ],
  },
  {
    id: 'making',
    number: '03',
    title: 'How perfume is made',
    intro: 'The craft — what goes into the bottle, how it is composed, why it changes through the day.',
    entries: [
      {
        q: 'How is perfume made?',
        a: 'Fragrance oils are blended in measured doses, dissolved into a high-grade alcohol base, sometimes with a touch of water, and then allowed to rest. The resting period — maceration — lets the composition settle and round out.',
      },
      {
        q: 'What are perfume notes?',
        a: 'Notes are the layers of scent in a composition. Perfumers organise them as top, heart (middle) and base — a structure that mirrors how the fragrance unfolds on skin.',
      },
      {
        q: 'What are top notes?',
        a: 'The first scents you perceive after spraying. They are usually fresh and volatile — citrus, herbs, light spices — and fade within the first half hour.',
      },
      {
        q: 'What are middle notes?',
        a: 'The heart of the perfume, appearing once the top notes have softened. Florals, fruits and warm spices often live here.',
      },
      {
        q: 'What are base notes?',
        a: 'The deepest, longest-lived part of the composition — woods, musks, resins, vanilla, amber. They anchor the fragrance and can linger on skin for hours.',
      },
      {
        q: 'What ingredients are used in perfume?',
        a: 'A mix of natural materials — flowers, fruits, spices, woods, resins, animalic notes — and synthetic aroma molecules that extend, stabilise or imagine scents that nature cannot supply.',
      },
      {
        q: 'What is maceration in perfume making?',
        a: 'A resting period of weeks or months during which the freshly-mixed perfume settles. Maceration smooths the edges, marries the materials and is why a perfume often smells better a month after it is bottled.',
      },
      {
        q: 'Why do some perfumes smell stronger than others?',
        a: 'The oil concentration is one factor; the choice of materials is another. Heavier resins, sweet ambers and dense woods project further than light florals or citruses at the same concentration.',
      },
      {
        q: 'What is the role of alcohol in perfume?',
        a: 'It dissolves the fragrance oils, carries them off the skin and helps the composition spread evenly when sprayed. Most fine fragrances use a high-grade denatured ethanol.',
      },
      {
        q: 'Are synthetic ingredients bad?',
        a: 'No. Many are safer, more stable and more consistent than naturals, and they unlock scents — clean musks, ozonic accords, certain aldehydes — that have no natural equivalent.',
      },
    ],
  },
  {
    id: 'notes',
    number: '04',
    title: 'Notes and scent families',
    intro: 'A quick vocabulary — what each major scent family actually smells like.',
    entries: [
      {
        q: 'What does floral smell like?',
        a: 'Like flowers. The family is wide — rose, jasmine, peony, tuberose, lavender, lily — and ranges from delicate and powdery to heady and indolic.',
      },
      {
        q: 'What does citrus smell like?',
        a: 'Bright, fresh and zesty. Bergamot, lemon, orange, grapefruit and mandarin lead this family — short-lived but instantly recognisable.',
      },
      {
        q: 'What does woody smell like?',
        a: 'Warm, dry and grounding. Sandalwood is creamy; cedar is sharp and pencil-like; oud is dense and resinous; vetiver leans smoky and earthy.',
      },
      {
        q: 'What does oriental or amber smell like?',
        a: 'Warm, sweet and resinous. Vanilla, benzoin, labdanum and opoponax layered with spice. The family is increasingly called "amber" to retire the colonial-era label.',
      },
      {
        q: 'What does gourmand smell like?',
        a: 'Edible. Vanilla, caramel, chocolate, coffee, almond — anything that smells like dessert. The genre was named in the early nineties.',
      },
      {
        q: 'What does aquatic smell like?',
        a: 'Cool and watery, often built around a synthetic molecule called Calone that reads as seawater and watermelon rind. The family defined nineties perfumery.',
      },
      {
        q: 'What does musky smell like?',
        a: 'Soft, clean, skin-like and slightly warm. Modern musks are almost always synthetic — gentler and more controllable than the animal-derived musk of old.',
      },
      {
        q: 'What does spicy smell like?',
        a: 'Warm and assertive. Cinnamon, cardamom, black pepper, clove, nutmeg, pink pepper — each pushes the composition toward a different mood.',
      },
      {
        q: 'What does green smell like?',
        a: 'Fresh and leafy — crushed grass, tomato leaf, galbanum, mint. Greens often appear in the top of a fragrance to add lift.',
      },
      {
        q: 'What does powdery smell like?',
        a: 'Soft and elegant — iris, violet, vanilla, heliotrope, light musks. Powdery scents read clean, vintage, sometimes nostalgic.',
      },
    ],
  },
  {
    id: 'concentration',
    number: '05',
    title: 'Concentration',
    intro: 'The tiers — what the label on the bottle is actually telling you.',
    entries: [
      {
        q: 'What is Eau de Cologne?',
        a: 'The lightest concentration, traditionally 2–5% fragrance oil. Fresh, splashable, made to evaporate quickly.',
      },
      {
        q: 'What is Eau de Toilette?',
        a: 'Roughly 5–15% fragrance oil. Comfortable for daily wear, brighter on the opening, shorter-lived than parfum.',
      },
      {
        q: 'What is Eau de Parfum?',
        a: 'Roughly 15–20% fragrance oil. The most common modern concentration — substantial wear time and projection without becoming heavy.',
      },
      {
        q: 'What is Extrait de Parfum?',
        a: 'The highest concentration, 20–40% fragrance oil. Richest, longest-lasting, usually applied sparingly and worn close to the skin.',
      },
      {
        q: 'Which perfume type lasts the longest?',
        a: 'Extrait de Parfum, then Eau de Parfum. Concentration is the most reliable predictor of longevity, but the choice of materials matters too.',
      },
      {
        q: 'Is stronger concentration always better?',
        a: 'No. Lighter fragrances often wear better in heat, around food, or in close-quarters settings like the office. Concentration is a tool, not a hierarchy.',
      },
    ],
  },
  {
    id: 'choosing',
    number: '06',
    title: 'Choosing a perfume',
    intro: 'How to pick something that suits you, the occasion and the weather.',
    entries: [
      {
        q: 'How do I choose the right perfume?',
        a: 'Start from how you want to feel, not from what is popular. Then match it to the occasion, the weather and the scents you already know you love.',
      },
      {
        q: 'What perfume is suitable for daily wear?',
        a: 'Fresh, citrus, clean floral or soft musky scents tend to wear well day after day without becoming tiring.',
      },
      {
        q: 'What perfume is suitable for evening wear?',
        a: 'Amber, woody, spicy, oud, vanilla and deeper musks all suit evenings — they project warmth and feel intentional.',
      },
      {
        q: 'What perfume is suitable for work?',
        a: 'Soft, clean and close-wearing. Avoid heavy sillage in shared spaces; a fragrance should arrive when someone leans in, not before.',
      },
      {
        q: 'What perfume is suitable for hot weather?',
        a: 'Citrus, aquatic, green and light floral compositions stay legible in the heat. Heavier resinous scents tend to bloom too aggressively in summer.',
      },
      {
        q: 'What perfume is suitable for cold weather?',
        a: 'Warm woods, amber, spice, oud and vanilla perform best when the air is dry and cold. Cold weather slows evaporation, which gives heavy scents room to breathe.',
      },
      {
        q: 'Can perfume match personality?',
        a: 'It can. Many wearers choose scents that mirror or extend how they want to be perceived — elegant, fresh, mysterious, soft, confident.',
      },
      {
        q: 'Should I buy perfume based on trend?',
        a: 'Trends can help you discover new directions, but the perfume worth keeping is the one that feels right on your skin — not the one currently winning on social media.',
      },
    ],
  },
  {
    id: 'performance',
    number: '07',
    title: 'Performance',
    intro: 'Longevity, projection and sillage — three different words for three different things.',
    entries: [
      {
        q: 'What is longevity?',
        a: 'How long the fragrance lives on your skin or clothes before it disappears. Measured in hours.',
      },
      {
        q: 'What is projection?',
        a: 'How far the fragrance reaches off your body — the bubble of scent around you.',
      },
      {
        q: 'What is sillage?',
        a: 'The trail your fragrance leaves behind when you move through a room. From the French for "wake," as in a boat\'s.',
      },
      {
        q: 'Why does my perfume fade quickly?',
        a: 'Dry skin holds fragrance less well than moisturised skin. Heat, sweat and friction also burn off top notes faster. The concentration of the perfume itself is the largest single factor.',
      },
      {
        q: 'How can I make perfume last longer?',
        a: 'Apply to moisturised skin, target pulse points, layer with an unscented or matching lotion, and add a spray to clothing where the fabric will hold the scent.',
      },
      {
        q: 'Where should I apply perfume?',
        a: 'Pulse points — wrists, the side of the neck, behind the ears, the inner elbow, the chest. Warmth lifts the fragrance into the air.',
      },
      {
        q: 'Should I rub perfume after spraying?',
        a: 'No. Friction heats the top notes and burns them off prematurely. Spray, let it dry, and you keep the opening the perfumer composed.',
      },
      {
        q: 'Is it okay to spray perfume on clothes?',
        a: 'Yes, with care. Fabric holds fragrance longer than skin, but darker pigments and delicate materials can stain. Test on an inside seam first.',
      },
    ],
  },
  {
    id: 'buying',
    number: '08',
    title: 'Buying perfume',
    intro: 'How to test, how long to wait, and when blind-buying is a risk worth taking.',
    entries: [
      {
        q: 'Should I test perfume before buying?',
        a: 'Whenever possible, yes — especially for a full bottle. Skin chemistry can shift a fragrance in ways no review can predict.',
      },
      {
        q: 'How long should I wait after testing perfume?',
        a: 'Give it at least fifteen to thirty minutes. The opening tells you very little — what matters is how the heart and base settle on your skin.',
      },
      {
        q: 'Why does perfume smell different after a few hours?',
        a: 'Because that is exactly how it was designed. The top notes evaporate first, then the heart unfolds, then the base lingers. You are smelling the composition the way the perfumer structured it.',
      },
      {
        q: 'How many perfumes should I test at once?',
        a: 'Three to five is the practical limit. Beyond that the nose fatigues and everything starts to blend together.',
      },
      {
        q: 'What is blind buying?',
        a: 'Purchasing a perfume without smelling it first — usually based on reviews, note lists or the brand\'s reputation.',
      },
      {
        q: 'Is blind buying recommended?',
        a: 'It is a calculated risk. Decants and travel sizes can help you cut the risk in half if a tester bottle isn\'t available.',
      },
      {
        q: 'What size perfume should I buy first?',
        a: 'A small bottle or a sample. A 100ml flacon is a long commitment to a scent you have not yet lived with.',
      },
    ],
  },
  {
    id: 'gifts',
    number: '09',
    title: 'Gifts and occasions',
    intro: 'Perfume as a gift — when it works, and how to choose without seeing the recipient\'s face.',
    entries: [
      {
        q: 'Is perfume a good gift?',
        a: 'A very good one — personal, considered and elegant. The gift carries an implied note: I have thought about who you are.',
      },
      {
        q: 'How do I choose perfume as a gift?',
        a: 'Start from the recipient\'s style, age and the scents you have seen them wear before. When in doubt, lean lighter rather than louder.',
      },
      {
        q: 'What perfume is safe for gifting?',
        a: 'Fresh, soft floral, clean musky or light woody compositions tend to please a wider range of tastes than heavy ambers or polarising ouds.',
      },
      {
        q: 'What perfume is good for a romantic gift?',
        a: 'Warm florals, amber, vanilla, soft musk — scents with intimacy built into the composition. Avoid anything too sharp or too professional.',
      },
      {
        q: 'What perfume is good for birthdays?',
        a: 'Something personal and memorable. A fragrance the recipient will associate with the year, not just the occasion.',
      },
      {
        q: 'Can perfume be personalized?',
        a: 'Yes — through engraved bottles, custom packaging, gift notes or a curated selection chosen specifically for the recipient.',
      },
    ],
  },
  {
    id: 'mood',
    number: '10',
    title: 'Lifestyle and mood',
    intro: 'A fragrance wardrobe — different scents for different days of the same life.',
    entries: [
      {
        q: 'Can perfume affect mood?',
        a: 'Yes. Scent reaches the brain through a fast, direct neural pathway, and certain compositions can calm, energise, comfort or focus the wearer.',
      },
      {
        q: 'What perfume gives a clean feeling?',
        a: 'Soft musks, soaps, citruses, aquatic notes and pale florals all read as "freshly showered." The genre is sometimes called clean or soapy.',
      },
      {
        q: 'What perfume feels luxurious?',
        a: 'Oud, amber, sandalwood, iris, rose, saffron and vanilla — materials that have signalled wealth and ceremony across cultures for centuries.',
      },
      {
        q: 'What perfume feels youthful?',
        a: 'Fruity, sweet, light floral and citrus compositions tend to read playful and energetic.',
      },
      {
        q: 'What perfume feels mature and elegant?',
        a: 'Woody, powdery, musky and refined floral compositions carry an older, quieter authority.',
      },
      {
        q: 'Can I have different perfumes for different moods?',
        a: 'Most committed wearers do. A fragrance wardrobe usually grows to cover work, casual days, evenings, weather and the occasional special moment.',
      },
    ],
  },
  {
    id: 'care',
    number: '11',
    title: 'Care and safety',
    intro: 'How to store it, how to travel with it, and what to avoid putting on your skin.',
    entries: [
      {
        q: 'Can perfume irritate skin?',
        a: 'It can, especially for sensitive skin or with certain ingredients like citrus oils, which can cause photosensitivity in direct sun.',
      },
      {
        q: 'Should I apply perfume on broken or irritated skin?',
        a: 'No. Alcohol stings and the irritation can spread. Spray on clothing or hair instead until the skin has healed.',
      },
      {
        q: 'Can perfume be used by children?',
        a: 'Children\'s skin is more reactive than adult skin. Choose products formulated for children rather than splashing on a grown-up fragrance.',
      },
      {
        q: 'Can perfume stain clothes?',
        a: 'Some perfumes — particularly those rich in vanilla, oud or dark resins — can leave a yellow mark on light or delicate fabrics. Test discreetly first.',
      },
      {
        q: 'Can perfume be brought on a flight?',
        a: 'Yes. For cabin baggage, observe the standard liquid limits — usually 100ml per container in a clear sealed pouch.',
      },
      {
        q: 'Is perfume flammable?',
        a: 'Yes. Most fragrances contain a high proportion of alcohol, which is flammable. Keep them away from open flame and direct heat.',
      },
    ],
  },
  {
    id: 'branding',
    number: '12',
    title: 'Branding and identity',
    intro: 'A short detour into how a perfume is actually sold — useful for anyone curious about how the industry works.',
    entries: [
      {
        q: 'What makes a perfume brand feel premium?',
        a: 'A coherent story, a beautiful bottle, considered packaging, a recognisable visual language — and a juice that lives up to all of the above.',
      },
      {
        q: 'Why is perfume branding important?',
        a: 'Perfume is an emotional purchase. The brand carries the feeling the wearer wants to step into, long before they smell the scent itself.',
      },
      {
        q: 'What should a perfume brand story include?',
        a: 'Inspiration, mood, a sense of who the wearer is, the raw materials at the heart of the composition, and the feeling the perfume wants to leave behind.',
      },
      {
        q: 'How important is bottle design?',
        a: 'Critical. The bottle is the only part of a perfume the wearer sees every day, and the only part anyone else sees on the shelf at home.',
      },
      {
        q: 'What makes a perfume memorable?',
        a: 'A distinctive scent profile, an emotional connection, a name that lodges in memory, and a brand identity consistent enough to be recognised at a glance.',
      },
      {
        q: 'Should each perfume have its own story?',
        a: 'Yes — even within a single house. A short narrative gives the wearer a way to imagine the scent before they spray it.',
      },
      {
        q: 'What should be included on a perfume product page?',
        a: 'The name, the brand, the notes (top, heart, base), the concentration, the size, the longevity, the suited season and time of day, the mood, the ingredients of note, and care instructions.',
      },
    ],
  },
]

// Sum of all entries across chapters — used for the cover-page meta line.
export const PERFUME_FAQ_ENTRY_COUNT: number = PERFUME_FAQ.reduce(
  (n, ch) => n + ch.entries.length,
  0,
)
