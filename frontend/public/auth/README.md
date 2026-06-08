# Auth hero image

The login and register pages show a full-bleed hero image on their left brand
panel. Both currently share `login-hero.png`
([login.vue](../../app/pages/auth/login.vue),
[register.vue](../../app/pages/auth/register.vue)). To give register its own art,
save `register-hero.png` here and point register's `heroSrc` at it.

## Drop your image here

Save your AI-generated image as:

```
public/auth/login-hero.png
```

- Served at `/auth/login-hero.png` (referenced by `heroSrc` in `login.vue`).
- PNG or JPG both work. PNG keeps a transparent background if you render just a
  bottle (the scent gradient then shows behind it). Want a different name or
  extension? Change the single `heroSrc` line in `login.vue`.
- **Until the file exists, the panel gracefully falls back** to the scent
  gradient (no broken-image icon) — so you can ship and add the art later.

## Recommended export

- **Aspect ratio:** ~4:5 portrait (the panel is square-to-tall; `object-cover`
  centre-crops, so keep the subject roughly centred with breathing room).
- **Resolution:** ≥ 1600 × 2000 px (retina-crisp on large screens).
- **Format:** WebP or optimised JPG for weight; PNG if you need transparency.
- A bottom/top scrim is already applied in CSS for text legibility, so the
  wordmark + headline stay readable over any image.

The generation prompt lives in the chat / handoff notes.
