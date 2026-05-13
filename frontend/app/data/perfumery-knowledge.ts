// Editorial reference for the "Field notes" daily card on /today.
// Mock-first per HoP convention — backed by a static list, surfaced one-a-day
// via useDailyKnowledge() with a date-seeded index.

export type KnowledgeCategory = 'craft' | 'house' | 'family' | 'nose' | 'history'

export type KnowledgeEntry = {
  id: string
  category: KnowledgeCategory
  // Short label shown above the title (e.g. "On the craft").
  kicker: string
  // Bodoni headline — keep under ~60 chars so it fits one or two lines.
  title: string
  // Inter body — 1–3 sentences. Editorial voice, no marketing fluff.
  body: string
}

export const CATEGORY_LABELS: Record<KnowledgeCategory, string> = {
  craft: 'On the craft',
  house: 'House lineage',
  family: 'Note family',
  nose: 'The nose behind it',
  history: 'From the archive',
}

export const PERFUMERY_KNOWLEDGE: KnowledgeEntry[] = [
  // ── On the craft ──────────────────────────────────────────────────
  {
    id: 'concentration-tiers',
    category: 'craft',
    kicker: 'On the craft',
    title: 'Parfum, Eau de Parfum, Eau de Toilette',
    body: 'The tier names describe how much fragrant oil sits in the alcohol. Parfum runs 20–40%, Eau de Parfum 15–20%, Eau de Toilette 5–15%. More oil means longer wear and a heavier first impression, not necessarily a better composition.',
  },
  {
    id: 'top-heart-base',
    category: 'craft',
    kicker: 'On the craft',
    title: 'Why a fragrance changes through the day',
    body: 'Perfumers layer notes by volatility. Top notes — citrus, herbs — burn off in the first half hour. Heart notes settle the composition. Base notes — woods, musks, resins — anchor the scent and outlast everything above them.',
  },
  {
    id: 'enfleurage',
    category: 'craft',
    kicker: 'On the craft',
    title: 'The fat that catches a flower',
    body: 'Enfleurage presses fresh petals into odourless animal fat for weeks until the fat is saturated with scent. The result is washed with alcohol to lift the perfumed oil out. The method is mostly retired, but Grasse still revives it for jasmine and tuberose.',
  },
  {
    id: 'sillage-vs-longevity',
    category: 'craft',
    kicker: 'On the craft',
    title: 'Sillage is not the same as longevity',
    body: 'Sillage is the trail a fragrance leaves in the air behind you. Longevity is how long it lives on skin. A perfume can wear close and last all day, or project loudly and vanish by lunch.',
  },
  {
    id: 'maceration',
    category: 'craft',
    kicker: 'On the craft',
    title: 'Why a fresh bottle smells sharper',
    body: 'After bottling, perfumes go through maceration — weeks of slow chemical settling that rounds the edges. A juice that smells thin on day one often deepens after a month upright in a cool, dark cupboard.',
  },
  {
    id: 'skin-chemistry',
    category: 'craft',
    kicker: 'On the craft',
    title: 'The same scent, two different people',
    body: 'Skin pH, hydration, diet and even medication shift how a fragrance unfolds. The hotter and oilier the skin, the faster the top notes evaporate — which is why a perfume can feel sweeter on one wearer and drier on another.',
  },
  {
    id: 'pulse-points',
    category: 'craft',
    kicker: 'On the craft',
    title: 'Why we spray the pulse points',
    body: 'Warmth lifts fragrance off skin. Wrists, the inner elbow, the side of the neck and behind the ear are warmer than the surrounding skin, so they push the scent into the air as your blood moves underneath.',
  },
  {
    id: 'dont-rub',
    category: 'craft',
    kicker: 'On the craft',
    title: 'Stop rubbing your wrists together',
    body: 'Friction heats the top notes and burns them off faster than they were meant to leave. Spray, wait, and let the perfume dry on its own — you keep the opening the perfumer composed.',
  },

  // ── House lineage ─────────────────────────────────────────────────
  {
    id: 'house-chanel-no5',
    category: 'house',
    kicker: 'House lineage',
    title: 'Chanel and the first aldehydic',
    body: 'In 1921 Ernest Beaux brought Gabrielle Chanel ten numbered samples. She chose the fifth. No. 5 was the first major perfume built around aldehydes — synthetic molecules that smell like clean skin and laundry — and it rewrote what a luxury fragrance could be.',
  },
  {
    id: 'house-guerlaine',
    category: 'house',
    kicker: 'House lineage',
    title: 'The Guerlinade signature',
    body: 'For over a century Guerlain perfumes carry a recognisable base — bergamot, rose, jasmine, iris, vanilla and tonka — passed down through four generations of in-house perfumers. The blend is the family fingerprint, not a single perfume.',
  },
  {
    id: 'house-dior-miss',
    category: 'house',
    kicker: 'House lineage',
    title: 'Miss Dior arrived before the dress',
    body: 'Christian Dior launched Miss Dior in 1947, the same year as the New Look. He insisted the scent enter the room before the woman did — a green chypre composed by Jean Carles and Paul Vacher to walk ahead of every cinched waist on the runway.',
  },
  {
    id: 'house-ysl-opium',
    category: 'house',
    kicker: 'House lineage',
    title: 'Yves Saint Laurent and Opium',
    body: 'When YSL launched Opium in 1977 the campaign was scandalous and the juice was unmistakable — clove, pepper, myrrh and amber. It built the template for the spicy oriental and made perfume something you wore on purpose, not in apology.',
  },
  {
    id: 'house-hermes-cuir',
    category: 'house',
    kicker: 'House lineage',
    title: 'Hermès started with the leather',
    body: 'Before the silk and the scarves there was a saddlery in Paris. The leather notes that thread through so many Hermès fragrances — Bel Ami, Kelly Calèche, Cuir d\'Ange — trace straight back to the workshop on Rue du Faubourg Saint-Honoré.',
  },
  {
    id: 'house-tom-ford-private',
    category: 'house',
    kicker: 'House lineage',
    title: 'Why Tom Ford has two perfume lines',
    body: 'The Signature line is the wide-shouldered, department-store half. Private Blend is the experimental half — small batches, heavier raw materials, no compromise for mass appeal. The split lets one nose serve two very different rooms.',
  },
  {
    id: 'house-creed-history',
    category: 'house',
    kicker: 'House lineage',
    title: 'A house older than most countries',
    body: 'Creed traces back to a London tailor in 1760 who began making scented gloves and pomades for British royalty. Whatever you think of the modern juice, the family was perfuming aristocrats before the United States existed.',
  },

  // ── Note families ─────────────────────────────────────────────────
  {
    id: 'family-chypre',
    category: 'family',
    kicker: 'Note family',
    title: 'The chypre accord',
    body: 'A chypre is built on a contrast: bright citrus on top — usually bergamot — and a dark mossy-resinous base of oakmoss, labdanum and patchouli. The 1917 Coty Chypre named the family, but the structure predates it by centuries.',
  },
  {
    id: 'family-fougere',
    category: 'family',
    kicker: 'Note family',
    title: 'Fougère: the imagined fern',
    body: 'Ferns do not actually smell. Fougère is a perfumer\'s invention — lavender, oakmoss, coumarin and bergamot — first composed by Houbigant in 1882 as Fougère Royale, and now the spine of nearly every classical men\'s cologne.',
  },
  {
    id: 'family-oriental',
    category: 'family',
    kicker: 'Note family',
    title: 'The amber family, formerly oriental',
    body: 'Vanilla, benzoin, labdanum, opoponax — warm resinous notes layered with spice. The category was long called "oriental"; the industry is moving toward "amber" instead, which describes the accord without the colonial baggage.',
  },
  {
    id: 'family-aquatic',
    category: 'family',
    kicker: 'Note family',
    title: 'Aquatics begin with one molecule',
    body: 'The marine, ozonic feel of nineties perfumes — Cool Water, L\'Eau d\'Issey, Acqua di Giò — comes largely from Calone, a synthetic discovered in 1966. It smells of seawater and watermelon rind, and it built an entire genre.',
  },
  {
    id: 'family-gourmand',
    category: 'family',
    kicker: 'Note family',
    title: 'Angel and the gourmand',
    body: 'Thierry Mugler\'s Angel (1992) was the first major perfume built on edible notes — caramel, chocolate, praline, patchouli — and it defined the gourmand family. Almost every sweet-vanilla scent on the market traces back to it.',
  },
  {
    id: 'family-leather',
    category: 'family',
    kicker: 'Note family',
    title: 'There is no leather in leather',
    body: 'The smoky, animalic note we call "leather" in fragrance is a composed accord — birch tar, styrax, isobutyl quinoline, sometimes castoreum. It evokes a tannery without ever having seen a hide.',
  },
  {
    id: 'family-iris',
    category: 'family',
    kicker: 'Note family',
    title: 'Why iris is the costliest note',
    body: 'Iris butter — orris — comes from the rhizome of the plant, not the flower. The roots are dug, dried, and aged three to five years before they can be processed. A kilo of finished orris absolute can cost more than gold.',
  },

  // ── The nose behind it ────────────────────────────────────────────
  {
    id: 'nose-roudnitska',
    category: 'nose',
    kicker: 'The nose behind it',
    title: 'Edmond Roudnitska',
    body: 'The perfumer behind Diorissimo, Eau Sauvage and Femme. Roudnitska worked from a glass house in the Provence countryside, insisted that perfume was an art form on par with music, and trained his successor — his son Michel — to think the same way.',
  },
  {
    id: 'nose-jacques-polge',
    category: 'nose',
    kicker: 'The nose behind it',
    title: 'Jacques Polge at Chanel',
    body: 'Polge was Chanel\'s in-house perfumer for thirty-six years, from 1978 to 2015. Coco Mademoiselle, Allure, Égoïste — all his. He handed the lab to his son Olivier, who now composes for the same house and runs the same fields of jasmine in Grasse.',
  },
  {
    id: 'nose-jean-claude-ellena',
    category: 'nose',
    kicker: 'The nose behind it',
    title: 'Jean-Claude Ellena\'s minimalism',
    body: 'Ellena composes with the fewest raw materials possible — sometimes only eight or ten. As Hermès\' in-house nose from 2004 to 2016, he treated perfume the way a haiku poet treats a sentence: leave only what is necessary.',
  },
  {
    id: 'nose-sophia-grojsman',
    category: 'nose',
    kicker: 'The nose behind it',
    title: 'Sophia Grojsman, the rose architect',
    body: 'Born in Belarus, trained in New York, Grojsman built her career on overdoses of rose and damascones. Trésor, White Linen, Calyx, Paris by YSL — all hers. She is one of the rare perfumers whose name became a recognisable style.',
  },
  {
    id: 'nose-dominique-ropion',
    category: 'nose',
    kicker: 'The nose behind it',
    title: 'Dominique Ropion at Frédéric Malle',
    body: 'Ropion composed Carnal Flower, Portrait of a Lady and Geranium pour Monsieur for Editions de Parfums Frédéric Malle. The Malle label puts the perfumer\'s name on the bottle — a rare practice in an industry that usually keeps its noses anonymous.',
  },

  // ── From the archive ──────────────────────────────────────────────
  {
    id: 'history-grasse',
    category: 'history',
    kicker: 'From the archive',
    title: 'Why Grasse smells the way it does',
    body: 'Grasse, in the south of France, became Europe\'s perfume capital in the 17th century — first as a tannery town, then a centre for scented leather gloves, then for cultivating jasmine, rose, tuberose and lavender. The land\'s microclimate is still UNESCO-protected.',
  },
  {
    id: 'history-eau-de-cologne',
    category: 'history',
    kicker: 'From the archive',
    title: 'The first eau de cologne',
    body: 'In 1709 an Italian perfumer in Cologne, Germany — Giovanni Maria Farina — wrote to his brother that his new fragrance reminded him of an Italian spring morning. The recipe of citrus and herbs became Eau de Cologne, and 4711 still bottles a version of it today.',
  },
  {
    id: 'history-egypt-kyphi',
    category: 'history',
    kicker: 'From the archive',
    title: 'Kyphi, burned at sunset',
    body: 'Ancient Egyptian temples burned kyphi every evening — a compound of honey, raisins, wine, frankincense, myrrh, juniper and a dozen other resins. It was incense, medicine and offering at once, and recipes survive on temple walls at Edfu.',
  },
  {
    id: 'history-oud-bakhoor',
    category: 'history',
    kicker: 'From the archive',
    title: 'How oud became the Gulf signature',
    body: 'Oud is the dark resin a species of aquilaria tree produces when infected with a particular mould. It has been burned and worn across the Arabian peninsula for centuries — older, in the region, than any European perfume tradition.',
  },
  {
    id: 'history-perfume-organ',
    category: 'history',
    kicker: 'From the archive',
    title: 'The perfumer\'s organ',
    body: 'A perfumer\'s workbench is called an organ — a tiered semicircle of small bottles, sometimes hundreds, arranged so the perfumer can reach every raw material without standing. The shape was lifted, deliberately, from the church instrument.',
  },
  {
    id: 'history-tester-strip',
    category: 'history',
    kicker: 'From the archive',
    title: 'Why the paper strip exists',
    body: 'The mouillette — the thin paper blotter at a perfume counter — has been used since the 19th century. It carries the scent the way skin does, without the heat or chemistry of a wearer, so the perfumer can judge a composition on its own terms.',
  },
]
